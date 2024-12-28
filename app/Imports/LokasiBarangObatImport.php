<?php

namespace App\Imports;

use App\Models\Master\ObatBarang\Lokasi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LokasiBarangObatImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Lokasi([
            'kode_lokasi' => $row['kode_lokasi'], // Sesuaikan nama kolom dengan header Excel
            'lokasi' => $row['lokasi'],
        ]);
    }
}
