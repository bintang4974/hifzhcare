<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassPerformanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $classes;

    public function __construct($classes)
    {
        $this->classes = $classes;
    }

    public function collection()
    {
        return $this->classes;
    }

    public function headings(): array
    {
        return [
            'Kelas',
            'Ustadz',
            'Total Santri',
            'Santri Aktif',
            'Rata-rata Progress (%)',
            'Hafalan Terverifikasi',
            'Total Sertifikat',
            'Santri Khatam',
            'Tingkat Penyelesaian (%)',
        ];
    }

    public function map($class): array
    {
        $metrics = $class->metrics ?? [];
        $totalSantri = $metrics['total_santri'] ?? 0;
        $completionRate = $totalSantri > 0 ? (($metrics['completion_rate'] ?? 0) / $totalSantri * 100) : 0;

        return [
            $class->name,
            $class->ustadzProfiles->first()?->user->name ?? '-',
            $metrics['total_santri'] ?? 0,
            $metrics['active_santri'] ?? 0,
            number_format($metrics['avg_progress'] ?? 0, 2),
            $metrics['total_verified'] ?? 0,
            $metrics['total_certificates'] ?? 0,
            $metrics['completion_rate'] ?? 0,
            number_format($completionRate, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E2E8F0']],
            ],
        ];
    }

    public function title(): string
    {
        return 'Performance Kelas';
    }
}
