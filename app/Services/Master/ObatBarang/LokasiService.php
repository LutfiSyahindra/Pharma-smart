<?php

namespace App\Services\Master\ObatBarang;

use App\Exports\LokasiBarangObatTemplateExport;
use App\Imports\LokasiBarangObatImport;
use App\Models\Master\ObatBarang\Lokasi;
use Maatwebsite\Excel\Facades\Excel;

class LokasiService
{
    /**
     * Create a new class instance.
     */
    public function getAll()
    {
        return Lokasi::GetData();
    }

    public function store($lokasi, $kode_lokasi)
    {
        return Lokasi::store($lokasi, $kode_lokasi);
    }

    public function findData($id)
    {
        return Lokasi::findData($id);
    }

    public function UpdateData($id, $lokasi, $kode_lokasi)
    {
        return Lokasi::updateData($id, $lokasi, $kode_lokasi);
    }

    public function deleteData($id)
    {
        return Lokasi::deleteData($id);
    }

    public function exportTemplate()
    {
        return Excel::download(new LokasiBarangObatTemplateExport, 'template_lokasi_barang.xlsx');
    }

    public function importFromExcel($file)
    {
        Excel::import(new LokasiBarangObatImport, $file);
    }
}
