<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class supplierBarangTemplateExport implements WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    public function headings(): array
    {
        return [
            'Kode Supplier',
            'Supplier',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'D9D9D9'],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 30,
        ];
    }

    public function title(): string
    {
        return 'Template Satuan Supplier';
    }
}
