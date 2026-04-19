<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProgressExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
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
            'Nama Santri',
            'Kelas',
            'Total Hafalan (Juz)',
            'Progress (%)',
            'Sertifikat',
            'Status Verifikasi',
        ];
    }

    public function map($santri): array
    {
        return [
            $santri->nis ?? '-',
            $santri->user->name ?? '-',
            $santri->class_name ?? '-',
            $santri->total_juz_completed ?? 0,
            number_format($santri->progress_percentage ?? 0, 2),
            $santri->certificates_count ?? 0,
            $santri->verified_count ?? 0,
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
        return 'Laporan Progress';
    }
}
