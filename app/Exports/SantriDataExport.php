<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SantriDataExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $santris;

    public function __construct($santris)
    {
        $this->santris = $santris;
    }

    public function collection()
    {
        return $this->santris;
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Lengkap',
            'Kelas',
            'Status',
            'Tanggal Lahir',
            'Alamat',
            'No. HP',
            'Email',
            'Nama Wali',
            'HP Wali',
            'Progress (%)',
            'Total Hafalan',
            'Sertifikat',
            'Tanggal Masuk',
        ];
    }

    public function map($santri): array
    {
        // Get class names from many-to-many relationship
        $classNames = $santri->classes->pluck('name')->join(', ') ?: '-';
        
        // Determine status based on graduation_date and user status
        if (!is_null($santri->graduation_date)) {
            $status = 'Alumni';
        } elseif ($santri->user->status === 'inactive') {
            $status = 'Inactive';
        } else {
            $status = 'Active';
        }
        
        return [
            $santri->nis,
            $santri->user->name,
            $classNames,
            $status,
            $santri->birth_date ? $santri->birth_date->format('d/m/Y') : '-',
            $santri->address ?? '-',
            $santri->user->phone ?? '-',
            $santri->user->email ?? '-',
            $santri->wali->user->name ?? '-',
            $santri->wali->user->phone ?? '-',
            number_format($santri->total_juz_completed / 30 * 100, 2) . '%',
            $santri->verified_count ?? 0,
            $santri->certificates_count ?? 0,
            $santri->entry_date ? $santri->entry_date->format('d/m/Y') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E2E8F0']]],
        ];
    }

    public function title(): string
    {
        return 'Data Santri';
    }
}

class HafalanSummaryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $stats;
    protected $monthlyTrend;

    public function __construct($stats, $monthlyTrend)
    {
        $this->stats = $stats;
        $this->monthlyTrend = $monthlyTrend;
    }

    public function collection()
    {
        return collect([
            (object) [
                'label' => 'Total Submitted',
                'value' => $this->stats['total_submitted'],
            ],
            (object) [
                'label' => 'Total Verified',
                'value' => $this->stats['total_verified'],
            ],
            (object) [
                'label' => 'Total Pending',
                'value' => $this->stats['total_pending'],
            ],
            (object) [
                'label' => 'Total Rejected',
                'value' => $this->stats['total_rejected'],
            ],
            (object) [
                'label' => 'Verification Rate',
                'value' => number_format($this->stats['verification_rate'], 2) . '%',
            ],
            (object) [
                'label' => 'Avg Verification Time',
                'value' => number_format($this->stats['avg_verification_time'], 2) . ' hours',
            ],
        ]);
    }

    public function headings(): array
    {
        return ['Metric', 'Value'];
    }

    public function map($row): array
    {
        return [
            $row->label,
            $row->value,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Hafalan Summary';
    }
}

class CertificateSummaryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $certificates;
    protected $stats;

    public function __construct($certificates, $stats)
    {
        $this->certificates = $certificates;
        $this->stats = $stats;
    }

    public function collection()
    {
        return $this->certificates;
    }

    public function headings(): array
    {
        return [
            'Nomor Sertifikat',
            'Nama Santri',
            'NIS',
            'Kelas',
            'Tipe',
            'Juz',
            'Tanggal Terbit',
        ];
    }

    public function map($certificate): array
    {
        return [
            $certificate->certificate_number,
            $certificate->santri->user->name,
            $certificate->santri->nis,
            $certificate->santri->classModel->name ?? '-',
            $certificate->certificate_type === 'khatam' ? 'Khatam (30 Juz)' : 'Per Juz',
            $certificate->juz_number ?? 'N/A',
            $certificate->issue_date->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FEF3C7']]],
        ];
    }

    public function title(): string
    {
        return 'Sertifikat';
    }
}