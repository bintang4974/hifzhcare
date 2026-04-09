<?php

namespace App\Http\Controllers;

use App\Models\SantriProfile;
use App\Models\UstadzProfile;
use App\Models\Certificate;
use App\Models\Hafalan;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SantriDataExport;
use App\Exports\HafalanSummaryExport;
use App\Exports\CertificateSummaryExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('tenant');
    }

    /**
     * Display admin report dashboard
     */
    public function index()
    {
        $pesantrenId = auth()->user()->pesantren_id;

        // Statistics
        $stats = [
            'total_santri' => SantriProfile::where('pesantren_id', $pesantrenId)->count(),
            'total_hafalan' => Hafalan::where('pesantren_id', $pesantrenId)
                ->where('status', 'verified')
                ->count(),
            'total_certificates' => Certificate::where('pesantren_id', $pesantrenId)->count(),
            'total_classes' => Classes::where('pesantren_id', $pesantrenId)->count(),
        ];

        // Classes for filter
        $classes = Classes::where('pesantren_id', $pesantrenId)
            ->orderBy('name')
            ->get();

        // Recent reports (from database if you store them)
        $recentReports = collect([]); // TODO: Implement report history

        return view('reports.index', compact('stats', 'classes', 'recentReports'));
    }

    /**
     * SANTRI DATA REPORT
     */
    public function santriData(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:pdf,excel,csv',
            'class_id' => 'nullable|exists:classes,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $pesantrenId = auth()->user()->pesantren_id;

        // Query
        $query = SantriProfile::where('pesantren_id', $pesantrenId)
            ->with(['user', 'wali.user', 'classes'])
            ->withCount([
                'hafalans as verified_count' => function ($q) {
                    $q->where('status', 'verified');
                },
                'certificates as certificates_count'
            ]);

        // Filters
        if ($request->filled('class_id')) {
            $query->whereHas('classes', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $santris = $query->orderBy('nis')->get();

        // Generate based on format
        switch ($validated['format']) {
            case 'pdf':
                return $this->generateSantriDataPDF($santris);
            case 'excel':
                return $this->generateSantriDataExcel($santris);
            case 'csv':
                return $this->generateSantriDataCSV($santris);
        }
    }

    private function generateSantriDataPDF($santris)
    {
        $pesantren = auth()->user()->pesantren;

        $pdf = Pdf::loadView('reports.pdf.santri-data', [
            'santris' => $santris,
            'pesantren' => $pesantren,
            'generated_at' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Data-Santri-' . date('Y-m-d') . '.pdf');
    }

    private function generateSantriDataExcel($santris)
    {
        return Excel::download(
            new SantriDataExport($santris),
            'Laporan-Data-Santri-' . date('Y-m-d') . '.xlsx'
        );
    }

    private function generateSantriDataCSV($santris)
    {
        return Excel::download(
            new SantriDataExport($santris),
            'Laporan-Data-Santri-' . date('Y-m-d') . '.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    /**
     * SANTRI PROGRESS REPORT
     */
    public function santriProgress(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:pdf,excel',
            'class_id' => 'nullable|exists:classes,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $pesantrenId = auth()->user()->pesantren_id;

        // Query santri with progress
        $query = SantriProfile::where('pesantren_id', $pesantrenId)
            ->with(['user', 'classes'])
            ->withCount([
                'hafalans as verified_count' => function ($q) {
                    $q->where('status', 'verified');
                }
            ]);

        if ($request->filled('class_id')) {
            $query->whereHas('classes', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $santris = $query->orderByDesc('total_juz_completed')->get();

        // Add monthly progress data
        foreach ($santris as $santri) {
            $santri->monthly_progress = $this->getMonthlyProgress($santri->id);
        }

        if ($validated['format'] === 'pdf') {
            return $this->generateProgressPDF($santris);
        } else {
            return $this->generateProgressExcel($santris);
        }
    }

    private function getMonthlyProgress($santriId)
    {
        $santri = SantriProfile::find($santriId);
        
        return Hafalan::where('user_id', $santri->user_id)
            ->where('status', 'verified')
            ->selectRaw('MONTH(verified_at) as month, COUNT(*) as count')
            ->whereYear('verified_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
    }

    private function generateProgressPDF($santris)
    {
        $pesantren = auth()->user()->pesantren;

        $pdf = Pdf::loadView('reports.pdf.santri-progress', [
            'santris' => $santris,
            'pesantren' => $pesantren,
            'generated_at' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Progress-Hafalan-' . date('Y-m-d') . '.pdf');
    }

    private function generateProgressExcel($santris)
    {
        // TODO: Create ProgressExport class
        return response()->json(['message' => 'Excel export for progress coming soon']);
    }

    /**
     * SANTRI RANKING REPORT
     */
    public function santriRanking(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:pdf,excel',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $pesantrenId = auth()->user()->pesantren_id;

        // Top performers
        $query = SantriProfile::where('pesantren_id', $pesantrenId)
            ->with(['user', 'classes'])
            ->withCount([
                'hafalans as verified_count' => function ($q) {
                    $q->where('status', 'verified');
                },
                'certificates'
            ]);

        if ($request->filled('class_id')) {
            $query->whereHas('classes', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $topPerformers = $query->orderByDesc('total_juz_completed')
            ->orderByDesc('verified_count')
            ->limit(20)
            ->get();

        $pesantren = auth()->user()->pesantren;

        if ($validated['format'] === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.santri-ranking', [
                'santris' => $topPerformers,
                'pesantren' => $pesantren,
                'generated_at' => now(),
            ])->setPaper('a4', 'portrait');

            return $pdf->download('Laporan-Ranking-' . date('Y-m-d') . '.pdf');
        }

        // Excel format
        return response()->json(['message' => 'Excel export coming soon']);
    }

    /**
     * CLASS OVERVIEW REPORT
     */
    public function classOverview(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:pdf,excel',
        ]);

        $pesantrenId = auth()->user()->pesantren_id;

        $classes = Classes::where('pesantren_id', $pesantrenId)
            ->with(['ustadz.user', 'santriProfiles'])
            ->withCount(['santriProfiles as total_santri'])
            ->get();

        // Add statistics for each class
        foreach ($classes as $class) {
            // Calculate average progress from loaded santriProfiles
            $avgJuz = $class->santriProfiles->avg('total_juz_completed') ?? 0;
            $class->avg_progress = round(($avgJuz / 30) * 100, 2);
            
            $userIds = $class->santriProfiles->pluck('user_id');
            
            $class->total_verified = Hafalan::whereIn(
                'user_id',
                $userIds
            )->where('status', 'verified')->count();

            $class->total_certificates = Certificate::whereIn(
                'user_id',
                $userIds
            )->count();
        }

        $pesantren = auth()->user()->pesantren;

        if ($validated['format'] === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.class-overview', [
                'classes' => $classes,
                'pesantren' => $pesantren,
                'generated_at' => now(),
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Laporan-Overview-Kelas-' . date('Y-m-d') . '.pdf');
        }

        return response()->json(['message' => 'Excel export coming soon']);
    }

    /**
     * CLASS PERFORMANCE REPORT
     */
    public function classPerformance(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:pdf,excel',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $pesantrenId = auth()->user()->pesantren_id;

        $query = Classes::where('pesantren_id', $pesantrenId)
            ->with(['ustadz.user', 'santriProfiles.user']);

        if ($request->filled('class_id')) {
            $query->where('id', $request->class_id);
        }

        $classes = $query->get();

        // Detailed performance metrics
        foreach ($classes as $class) {
            $userIds = $class->santriProfiles->pluck('user_id');
            
            // Calculate average progress percentage
            $avgJuz = $class->santriProfiles->avg('total_juz_completed') ?? 0;
            $avgProgress = round(($avgJuz / 30) * 100, 2);
            
            // Count santri with 100% completion
            $completedCount = $class->santriProfiles->filter(function ($santri) {
                return $santri->total_juz_completed >= 30;
            })->count();

            $class->metrics = [
                'total_santri' => $class->santriProfiles->count(),
                'active_santri' => $class->santriProfiles->where('graduation_date', null)->count(),
                'avg_progress' => $avgProgress,
                'total_verified' => Hafalan::whereIn('user_id', $userIds)
                    ->where('status', 'verified')->count(),
                'total_certificates' => Certificate::whereIn('user_id', $userIds)->count(),
                'completion_rate' => $completedCount,
            ];

            // Monthly trend
            $class->monthly_trend = Hafalan::whereIn('user_id', $userIds)
                ->where('status', 'verified')
                ->selectRaw('MONTH(verified_at) as month, COUNT(*) as count')
                ->whereYear('verified_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();
        }

        $pesantren = auth()->user()->pesantren;

        if ($validated['format'] === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.class-performance', [
                'classes' => $classes,
                'pesantren' => $pesantren,
                'generated_at' => now(),
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Laporan-Performance-Kelas-' . date('Y-m-d') . '.pdf');
        }

        return response()->json(['message' => 'Excel export coming soon']);
    }

    /**
     * HAFALAN SUMMARY REPORT
     */
    public function hafalanSummary(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:pdf,excel',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $pesantrenId = auth()->user()->pesantren_id;

        $query = Hafalan::where('pesantren_id', $pesantrenId);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Statistics
        $stats = [
            'total_submitted' => $query->count(),
            'total_verified' => (clone $query)->where('status', 'verified')->count(),
            'total_pending' => (clone $query)->where('status', 'pending')->count(),
            'total_rejected' => (clone $query)->where('status', 'rejected')->count(),
            'verification_rate' => 0,
            'avg_verification_time' => 0,
        ];

        $stats['verification_rate'] = $stats['total_submitted'] > 0
            ? ($stats['total_verified'] / $stats['total_submitted']) * 100
            : 0;

        // Average verification time (in hours)
        $stats['avg_verification_time'] = Hafalan::where('pesantren_id', $pesantrenId)
            ->where('status', 'verified')
            ->whereNotNull('verified_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, verified_at)) as avg_hours')
            ->value('avg_hours') ?? 0;

        // Monthly trend
        $monthlyTrend = Hafalan::where('pesantren_id', $pesantrenId)
            ->where('status', 'verified')
            ->selectRaw('MONTH(verified_at) as month, COUNT(*) as count')
            ->whereYear('verified_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // By status distribution
        $statusDistribution = Hafalan::where('pesantren_id', $pesantrenId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $pesantren = auth()->user()->pesantren;

        if ($validated['format'] === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.hafalan-summary', [
                'stats' => $stats,
                'monthlyTrend' => $monthlyTrend,
                'statusDistribution' => $statusDistribution,
                'pesantren' => $pesantren,
                'generated_at' => now(),
            ])->setPaper('a4', 'portrait');

            return $pdf->download('Laporan-Summary-Hafalan-' . date('Y-m-d') . '.pdf');
        }

        return Excel::download(
            new HafalanSummaryExport($stats, $monthlyTrend),
            'Laporan-Summary-Hafalan-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * HAFALAN PER JUZ REPORT
     */
    public function hafalanJuz(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:pdf,excel',
        ]);

        $pesantrenId = auth()->user()->pesantren_id;

        // Get hafalan grouped by juz (simplified - you'd calculate actual juz from surah)
        $juzStats = [];
        for ($juz = 1; $juz <= 30; $juz++) {
            $juzStats[$juz] = [
                'total_hafalan' => 0,
                'total_santri' => 0,
                'completion_rate' => 0,
                'avg_time' => 0,
            ];
        }

        // Get certificates per juz
        $certificatesPerJuz = Certificate::where('pesantren_id', $pesantrenId)
            ->where('certificate_type', 'per_juz')
            ->selectRaw('juz_number, COUNT(*) as count')
            ->groupBy('juz_number')
            ->pluck('count', 'juz_number');

        foreach ($certificatesPerJuz as $juz => $count) {
            if (isset($juzStats[$juz])) {
                $juzStats[$juz]['total_santri'] = $count;
                $juzStats[$juz]['completion_rate'] = 100; // Simplified
            }
        }

        $pesantren = auth()->user()->pesantren;

        if ($validated['format'] === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.hafalan-juz', [
                'juzStats' => $juzStats,
                'pesantren' => $pesantren,
                'generated_at' => now(),
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Laporan-Hafalan-Per-Juz-' . date('Y-m-d') . '.pdf');
        }

        return response()->json(['message' => 'Excel export coming soon']);
    }

    /**
     * CERTIFICATE SUMMARY REPORT
     */
    public function certificateSummary(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:pdf,excel',
            'class_id' => 'nullable|exists:classes,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $pesantrenId = auth()->user()->pesantren_id;

        $query = Certificate::where('pesantren_id', $pesantrenId)
            ->with(['santri.user', 'santri.classes']);

        if ($request->filled('class_id')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('issued_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('issued_at', '<=', $request->end_date);
        }

        $certificates = $query->orderByDesc('issued_at')->get();

        // Statistics
        $stats = [
            'total' => $certificates->count(),
            'per_juz' => $certificates->where('type', 'santri_juz')->count(),
            'general' => $certificates->whereIn('type', ['general_achievement', 'general_consistency'])->count(),
        ];

        // Monthly distribution
        $monthlyDistribution = $certificates->groupBy(function ($cert) {
            return $cert->issued_at?->format('Y-m') ?? 'Not Issued';
        })->map->count();

        $pesantren = auth()->user()->pesantren;

        if ($validated['format'] === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.certificate-summary', [
                'certificates' => $certificates,
                'stats' => $stats,
                'monthlyDistribution' => $monthlyDistribution,
                'pesantren' => $pesantren,
                'generated_at' => now(),
            ])->setPaper('a4', 'portrait');

            return $pdf->download('Laporan-Sertifikat-' . date('Y-m-d') . '.pdf');
        }

        return Excel::download(
            new CertificateSummaryExport($certificates, $stats),
            'Laporan-Sertifikat-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * STAKEHOLDER REPORTS
     */
    public function stakeholder()
    {
        $pesantrenId = auth()->user()->pesantren_id;

        // Recent executive reports
        $recentReports = collect([]); // TODO: Implement

        return view('reports.stakeholder', compact('recentReports'));
    }

    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:dashboard-summary,trend-analysis,performance-overview,financial-summary',
            'period' => 'required|in:this_month,last_month,this_quarter,last_quarter,this_year,custom',
            'start_date' => 'required_if:period,custom|nullable|date',
            'end_date' => 'required_if:period,custom|nullable|date',
            'format' => 'required|in:pdf,excel',
        ]);

        $pesantrenId = auth()->user()->pesantren_id;

        // Calculate date range
        $dates = $this->calculateDateRange($validated['period'], $request->start_date, $request->end_date);

        // Generate based on type
        switch ($validated['report_type']) {
            case 'dashboard-summary':
                return $this->generateDashboardSummary($pesantrenId, $dates, $validated['format']);
            case 'trend-analysis':
                return $this->generateTrendAnalysis($pesantrenId, $dates, $validated['format']);
            case 'performance-overview':
                return $this->generatePerformanceOverview($pesantrenId, $dates, $validated['format']);
            case 'financial-summary':
                return $this->generateFinancialSummary($pesantrenId, $dates, $validated['format']);
        }
    }

    private function calculateDateRange($period, $startDate, $endDate)
    {
        switch ($period) {
            case 'this_month':
                return ['start' => now()->startOfMonth(), 'end' => now()->endOfMonth()];
            case 'last_month':
                return ['start' => now()->subMonth()->startOfMonth(), 'end' => now()->subMonth()->endOfMonth()];
            case 'this_quarter':
                return ['start' => now()->startOfQuarter(), 'end' => now()->endOfQuarter()];
            case 'last_quarter':
                return ['start' => now()->subQuarter()->startOfQuarter(), 'end' => now()->subQuarter()->endOfQuarter()];
            case 'this_year':
                return ['start' => now()->startOfYear(), 'end' => now()->endOfYear()];
            case 'custom':
                return ['start' => $startDate, 'end' => $endDate];
        }
    }

    private function generateDashboardSummary($pesantrenId, $dates, $format)
    {
        $santris = SantriProfile::where('pesantren_id', $pesantrenId)->get();
        
        $data = [
            'total_santri' => $santris->count(),
            'active_santri' => $santris->where('graduation_date', null)->count(),
            'total_ustadz' => UstadzProfile::where('pesantren_id', $pesantrenId)->count(),
            'total_classes' => Classes::where('pesantren_id', $pesantrenId)->count(),
            'total_hafalan' => Hafalan::where('pesantren_id', $pesantrenId)
                ->where('status', 'verified')
                ->whereBetween('verified_at', [$dates['start'], $dates['end']])
                ->count(),
            'total_certificates' => Certificate::where('pesantren_id', $pesantrenId)
                ->whereBetween('issued_at', [$dates['start'], $dates['end']])
                ->count(),
            'completion_rate' => round(($santris->avg('total_juz_completed') / 30) * 100, 2) ?? 0,
        ];

        $pesantren = auth()->user()->pesantren;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.executive-dashboard', [
                'data' => $data,
                'pesantren' => $pesantren,
                'period' => $dates,
                'generated_at' => now(),
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Executive-Dashboard-Summary-' . date('Y-m-d') . '.pdf');
        }

        return response()->json(['message' => 'Excel format coming soon']);
    }

    private function generateTrendAnalysis($pesantrenId, $dates, $format)
    {
        // TODO: Implement trend analysis
        return response()->json(['message' => 'Trend analysis coming soon']);
    }

    private function generatePerformanceOverview($pesantrenId, $dates, $format)
    {
        // TODO: Implement performance overview
        return response()->json(['message' => 'Performance overview coming soon']);
    }

    private function generateFinancialSummary($pesantrenId, $dates, $format)
    {
        // TODO: Implement financial summary
        return response()->json(['message' => 'Financial summary coming soon']);
    }

    public function quickReport(Request $request)
    {
        $type = $request->get('type');
        $pesantrenId = auth()->user()->pesantren_id;

        // Generate quick reports without filters
        switch ($type) {
            case 'monthly-highlights':
                return $this->generateMonthlyHighlights($pesantrenId);
            case 'student-metrics':
                return $this->generateStudentMetrics($pesantrenId);
            case 'achievement-summary':
                return $this->generateAchievementSummary($pesantrenId);
        }
    }

    private function generateMonthlyHighlights($pesantrenId)
    {
        // TODO: Implement
        return response()->json(['message' => 'Coming soon']);
    }

    private function generateStudentMetrics($pesantrenId)
    {
        // TODO: Implement
        return response()->json(['message' => 'Coming soon']);
    }

    private function generateAchievementSummary($pesantrenId)
    {
        // TODO: Implement
        return response()->json(['message' => 'Coming soon']);
    }
}
