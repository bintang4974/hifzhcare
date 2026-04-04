<?php

namespace App\Http\Controllers;

use App\Models\SantriProfile;
use App\Models\Hafalan;
use App\Models\Certificate;
use App\Models\Classes;
use App\Models\User;
use App\Models\Pesantren;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $pesantren;

    public function __construct()
    {
        // Middleware protection is handled in routes
        $this->pesantren = Pesantren::first(); // Get default/current pesantren
    }

    /**
     * Display the reports index page
     */
    public function index()
    {
        $stats = [
            'total_santri' => SantriProfile::where('pesantren_id', $this->pesantren->id)->count(),
            'total_hafalan' => Hafalan::where('pesantren_id', $this->pesantren->id)->count(),
            'total_certificates' => Certificate::where('pesantren_id', $this->pesantren->id)->count(),
            'total_classes' => Classes::where('pesantren_id', $this->pesantren->id)->count(),
        ];

        $classes = Classes::where('pesantren_id', $this->pesantren->id)->get();
        $recentReports = collect([]); // Placeholder for recent reports

        return view('reports.index', compact('stats', 'classes', 'recentReports'));
    }

    /**
     * SANTRI REPORTS
     */

    /**
     * Generate Santri Data Report
     */
    public function santriData(Request $request)
    {
        $query = SantriProfile::with(['user', 'waliProfile.user', 'classes', 'hafalans'])
            ->where('pesantren_id', $this->pesantren->id);

        // Apply filters
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('classes', function ($q) {
                $q->where('class_id', request('class_id'));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $santris = $query->orderBy('created_at', 'desc')->get();

        return $this->generateReport('Laporan Data Santri', 'santri_data', $santris, $request->format ?? 'pdf', [
            'columns' => ['No', 'Nama Santri', 'Kelas', 'No. Induk', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Wali'],
            'data' => $santris->map(function ($santri, $index) {
                return [
                    $index + 1,
                    $santri->user->name ?? '-',
                    $santri->classes->pluck('name')->join(', ') ?? '-',
                    $santri->student_id ?? '-',
                    $santri->gender ?? '-',
                    $santri->birth_place ?? '-',
                    $santri->birth_date ? $santri->birth_date->format('d/m/Y') : '-',
                    $santri->waliProfile?->user->name ?? '-',
                ];
            })->toArray()
        ]);
    }

    /**
     * Generate Santri Progress Report
     */
    public function santriProgress(Request $request)
    {
        $query = SantriProfile::with(['user', 'hafalans'])
            ->where('pesantren_id', $this->pesantren->id);

        // Apply filters
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('classes', function ($q) {
                $q->where('class_id', request('class_id'));
            });
        }

        $santris = $query->orderBy('created_at', 'desc')->get();

        $data = $santris->map(function ($santri) {
            $totalHafalan = $santri->hafalans->count();
            $verifiedHafalan = $santri->hafalans->whereNotNull('verified_at')->count();
            $percentageVerified = $totalHafalan > 0 ? round(($verifiedHafalan / $totalHafalan) * 100, 2) : 0;

            return [
                $santri->user->name ?? '-',
                $santri->student_id ?? '-',
                $totalHafalan,
                $verifiedHafalan,
                $totalHafalan - $verifiedHafalan,
                $percentageVerified . '%'
            ];
        })->toArray();

        return $this->generateReport('Laporan Progress Hafalan', 'santri_progress', $data, $request->format ?? 'pdf', [
            'columns' => ['Nama Santri', 'No. Induk', 'Total Hafalan', 'Terverifikasi', 'Pending', 'Progress %'],
            'data' => $data
        ]);
    }

    /**
     * Generate Santri Ranking Report
     */
    public function santriRanking(Request $request)
    {
        $query = SantriProfile::with(['user', 'hafalans'])
            ->where('pesantren_id', $this->pesantren->id);

        if ($request->filled('class_id')) {
            $query->whereHas('classes', function ($q) {
                $q->where('class_id', request('class_id'));
            });
        }

        $santris = $query->orderBy('created_at', 'desc')->get();

        // Calculate rankings
        $rankings = $santris->map(function ($santri) {
            $verifiedHafalan = $santri->hafalans->whereNotNull('verified_at')->count();
            return [
                'santri' => $santri,
                'verified_count' => $verifiedHafalan
            ];
        })->sortByDesc('verified_count')->values();

        $data = $rankings->map(function ($item, $index) {
            return [
                $index + 1,
                $item['santri']->user->name ?? '-',
                $item['santri']->student_id ?? '-',
                $item['verified_count'],
                $item['santri']->hafalans->count()
            ];
        })->toArray();

        return $this->generateReport('Laporan Ranking & Achievement', 'santri_ranking', $data, $request->format ?? 'pdf', [
            'columns' => ['Ranking', 'Nama Santri', 'No. Induk', 'Hafalan Terverifikasi', 'Total Hafalan'],
            'data' => $data
        ]);
    }

    /**
     * CLASS REPORTS
     */

    /**
     * Generate Class Overview Report
     */
    public function classOverview(Request $request)
    {
        $classes = Classes::with(['santris', 'ustadzs', 'hafalans'])
            ->where('pesantren_id', $this->pesantren->id)
            ->orderBy('name')
            ->get();

        $data = $classes->map(function ($class) {
            return [
                $class->name,
                $class->santris->count(),
                $class->ustadzs->count(),
                $class->hafalans->count(),
                $class->hafalans->whereNotNull('verified_at')->count(),
            ];
        })->toArray();

        return $this->generateReport('Laporan Overview Kelas', 'class_overview', $data, $request->format ?? 'pdf', [
            'columns' => ['Nama Kelas', 'Total Santri', 'Total Ustadz', 'Total Hafalan', 'Terverifikasi'],
            'data' => $data
        ]);
    }

    /**
     * Generate Class Performance Report
     */
    public function classPerformance(Request $request)
    {
        $query = Classes::with(['santris', 'hafalans'])
            ->where('pesantren_id', $this->pesantren->id);

        if ($request->filled('class_id')) {
            $query->where('id', $request->class_id);
        }

        $classes = $query->orderBy('name')->get();

        $data = $classes->map(function ($class) {
            $totalHafalan = $class->hafalans->count();
            $verifiedHafalan = $class->hafalans->whereNotNull('verified_at')->count();
            $percentageVerified = $totalHafalan > 0 ? round(($verifiedHafalan / $totalHafalan) * 100, 2) : 0;

            return [
                $class->name,
                $class->santris->count(),
                $totalHafalan,
                $verifiedHafalan,
                $totalHafalan - $verifiedHafalan,
                $percentageVerified . '%'
            ];
        })->toArray();

        return $this->generateReport('Laporan Performance Kelas', 'class_performance', $data, $request->format ?? 'pdf', [
            'columns' => ['Nama Kelas', 'Total Santri', 'Total Hafalan', 'Terverifikasi', 'Pending', 'Progress %'],
            'data' => $data
        ]);
    }

    /**
     * HAFALAN REPORTS
     */

    /**
     * Generate Hafalan Summary Report
     */
    public function hafalanSummary(Request $request)
    {
        $query = Hafalan::with(['santriProfile.user', 'surah'])
            ->where('pesantren_id', $this->pesantren->id);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $hafalans = $query->get();

        $totalHafalan = $hafalans->count();
        $totalVerified = $hafalans->whereNotNull('verified_at')->count();
        $totalPending = $hafalans->whereNull('verified_at')->count();
        $percentageVerified = $totalHafalan > 0 ? round(($totalVerified / $totalHafalan) * 100, 2) : 0;

        $summaryData = [
            ['Metrik', 'Jumlah'],
            ['Total Hafalan', $totalHafalan],
            ['Terverifikasi', $totalVerified],
            ['Pending Verifikasi', $totalPending],
            ['Persentase Terverifikasi', $percentageVerified . '%'],
        ];

        return $this->generateReport('Laporan Summary Hafalan', 'hafalan_summary', $summaryData, $request->format ?? 'pdf', [
            'columns' => $summaryData[0],
            'data' => array_slice($summaryData, 1)
        ]);
    }

    /**
     * Generate Hafalan per Juz Report
     */
    public function hafalanJuz(Request $request)
    {
        $query = Hafalan::with(['santriProfile.user'])
            ->where('pesantren_id', $this->pesantren->id);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $hafalans = $query->get();

        // Group by juz
        $juzData = $hafalans->groupBy('juz_number')->map(function ($juzHafalans) {
            $total = $juzHafalans->count();
            $verified = $juzHafalans->whereNotNull('verified_at')->count();
            $percentage = $total > 0 ? round(($verified / $total) * 100, 2) : 0;

            return [
                'Juz',
                'Total Hafalan',
                'Terverifikasi',
                'Pending',
                'Progress %'
            ];
        });

        $data = $hafalans->groupBy('juz_number')->map(function ($juzHafalans, $juzNumber) {
            $total = $juzHafalans->count();
            $verified = $juzHafalans->whereNotNull('verified_at')->count();
            $percentage = $total > 0 ? round(($verified / $total) * 100, 2) : 0;

            return [
                'Juz ' . $juzNumber,
                $total,
                $verified,
                $total - $verified,
                $percentage . '%'
            ];
        })->values()->toArray();

        return $this->generateReport('Laporan Hafalan per Juz', 'hafalan_juz', $data, $request->format ?? 'pdf', [
            'columns' => ['Juz', 'Total Hafalan', 'Terverifikasi', 'Pending', 'Progress %'],
            'data' => $data
        ]);
    }

    /**
     * CERTIFICATE REPORTS
     */

    /**
     * Generate Certificate Summary Report
     */
    public function certificateSummary(Request $request)
    {
        $query = Certificate::with(['user', 'template'])
            ->where('pesantren_id', $this->pesantren->id);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('issued_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('user.santriProfile.classes', function ($q) {
                $q->where('class_id', request('class_id'));
            });
        }

        $certificates = $query->get();

        // Summary by status
        $summaryData = [];
        $statuses = ['pending', 'approved', 'issued', 'rejected'];

        foreach ($statuses as $status) {
            $count = $certificates->where('status', $status)->count();
            $displayStatus = match($status) {
                'pending' => 'Menunggu Persetujuan',
                'approved' => 'Disetujui',
                'issued' => 'Diterbitkan',
                'rejected' => 'Ditolak',
                default => $status
            };
            $summaryData[] = [$displayStatus, $count];
        }

        // Add total
        $summaryData[] = ['Total Sertifikat', $certificates->count()];

        return $this->generateReport('Laporan Summary Sertifikat', 'certificate_summary', $summaryData, $request->format ?? 'pdf', [
            'columns' => ['Status', 'Jumlah'],
            'data' => $summaryData
        ]);
    }

    /**
     * Generate and Output Report
     */
    private function generateReport($title, $filename, $data, $format = 'pdf', $columns = [])
    {
        switch ($format) {
            case 'excel':
                return $this->exportExcel($title, $filename, $columns);
            case 'csv':
                return $this->exportCsv($title, $filename, $columns);
            default:
                return $this->exportPdf($title, $filename, $columns);
        }
    }

    /**
     * Export to PDF
     */
    private function exportPdf($title, $filename, $columns = [])
    {
        $data = [
            'title' => $title,
            'columns' => $columns['columns'] ?? [],
            'data' => $columns['data'] ?? [],
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'pesantren' => $this->pesantren->name ?? 'Pesantren'
        ];

        $pdf = Pdf::loadView('reports.pdf-template', $data);
        
        return $pdf->download($filename . '_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Export to Excel
     */
    private function exportExcel($title, $filename, $columns = [])
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:Z1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Set headers
        $colNum = 1;
        foreach ($columns['columns'] ?? [] as $header) {
            $sheet->setCellValueByColumnAndRow($colNum, 3, $header);
            $colNum++;
        }

        // Set data
        $rowNum = 4;
        foreach ($columns['data'] ?? [] as $row) {
            $colNum = 1;
            foreach ($row as $cell) {
                $sheet->setCellValueByColumnAndRow($colNum, $rowNum, $cell);
                $colNum++;
            }
            $rowNum++;
        }

        // Auto-fit columns
        foreach (range(1, $colNum - 1) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = $filename . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Export to CSV
     */
    private function exportCsv($title, $filename, $columns = [])
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $colNum = 1;
        foreach ($columns['columns'] ?? [] as $header) {
            $sheet->setCellValueByColumnAndRow($colNum, 1, $header);
            $colNum++;
        }

        // Set data
        $rowNum = 2;
        foreach ($columns['data'] ?? [] as $row) {
            $colNum = 1;
            foreach ($row as $cell) {
                $sheet->setCellValueByColumnAndRow($colNum, $rowNum, $cell);
                $colNum++;
            }
            $rowNum++;
        }

        $writer = new Csv($spreadsheet);
        $filename = $filename . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
