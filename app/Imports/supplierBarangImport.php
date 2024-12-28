<?php

namespace App\Imports;

use App\Models\Master\ObatBarang\Supplier;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class supplierBarangImport implements ToModel, WithHeadingRow
{
public function model(array $row)
    {
        return new Supplier([
            'kode_supplier' => $row['kode_supplier'], // Sesuaikan nama kolom dengan header Excel
            'supplier' => $row['supplier'],
        ]);
    }
}
