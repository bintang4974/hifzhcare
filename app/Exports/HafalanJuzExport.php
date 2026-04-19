<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HafalanJuzExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected $juzStats;

    public function __construct($juzStats)
    {
        $this->juzStats = $juzStats;
    }

    public function array(): array
    {
        $data = [];
        foreach ($this->juzStats as $juz => $stats) {
            $data[] = [
                $juz,
                $stats['total_santri'] ?? 0,
                $stats['santri_completed'] ?? 0,
                number_format($stats['completion_rate'], 2),
                $stats['total_hafalan'] ?? 0,
            ];
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'Juz',
            'Total Santri',
            'Santri Selesai',
            'Completion Rate (%)',
            'Total Hafalan Entries',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F59E0B']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
            'A' => ['alignment' => ['horizontal' => 'center']],
            'B' => ['alignment' => ['horizontal' => 'center']],
            'C' => ['alignment' => ['horizontal' => 'center']],
            'D' => ['alignment' => ['horizontal' => 'center']],
            'E' => ['alignment' => ['horizontal' => 'center']],
        ];
    }

    public function title(): string
    {
        return 'Hafalan Per Juz';
    }
}
