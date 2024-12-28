<?php

namespace App\Services\Master\ObatBarang;

use App\Exports\SatuanBarangTemplateExport;
use App\Imports\SatuanBarangImport;
use App\Models\Master\ObatBarang\Satuan;
use Maatwebsite\Excel\Facades\Excel;

class SatuanService
{
    /**
     * Create a new class instance.
     */ 
    public function getAll()
    {
        return Satuan::GetData();
    }

    public function store($satuan, $kode_satuan)
    {
        return Satuan::store($satuan, $kode_satuan);
    }

    public function findData($id)
    {
        return Satuan::findData($id);
    }

    public function UpdateData($id, $satuan, $kode_satuan)
    {
        return Satuan::updateData($id, $satuan, $kode_satuan);
    }

    public function deleteData($id)
    {
        return Satuan::deleteData($id);
    }

    public function exportTemplate()
    {
        return Excel::download(new SatuanBarangTemplateExport, 'template_satuan_barang.xlsx');
    }

    public function importFromExcel($file)
    {
        Excel::import(new SatuanBarangImport, $file);
    }
}
