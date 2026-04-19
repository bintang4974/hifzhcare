<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassOverviewExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
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
            'Rata-rata Progress (%)',
            'Hafalan Terverifikasi',
            'Total Sertifikat',
            'Kapasitas',
            'Utilisasi Kapasitas (%)',
        ];
    }

    public function map($class): array
    {
        $capacity = $class->capacity ?? 30;
        $utilization = ($class->total_santri / $capacity) * 100;

        return [
            $class->name,
            $class->ustadzProfiles->first()?->user->name ?? '-',
            $class->total_santri ?? 0,
            number_format($class->avg_progress ?? 0, 2),
            $class->total_verified ?? 0,
            $class->total_certificates ?? 0,
            $capacity,
            number_format($utilization, 2),
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
        return 'Overview Kelas';
    }
}
