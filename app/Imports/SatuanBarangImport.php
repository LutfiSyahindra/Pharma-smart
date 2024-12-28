<?php

namespace App\Imports;

use App\Models\Master\ObatBarang\Satuan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SatuanBarangImport implements ToModel, WithHeadingRow
{
    /**
     * Insert data ke database.
     */
    public function model(array $row)
    {
        return new Satuan([
            'kode_satuan' => $row['kode_satuan'], // Sesuaikan nama kolom dengan header Excel
            'satuan' => $row['satuan'],
        ]);
    }
}
