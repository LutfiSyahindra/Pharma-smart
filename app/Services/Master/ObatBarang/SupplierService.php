<?php

namespace App\Services\Master\ObatBarang;

use App\Exports\supplierBarangTemplateExport;
use App\Imports\supplierBarangImport;
use App\Models\Master\ObatBarang\Supplier;
use Maatwebsite\Excel\Facades\Excel;

class SupplierService
{
    /**
     * Create a new class instance.
     */
    public function getAll()
    {
        return Supplier::GetData();
    }

    public function store($supplier, $kode_supplier)
    {
        return Supplier::store($supplier, $kode_supplier);
    }

    public function findData($id)
    {
        return Supplier::findData($id);
    }

    public function UpdateData($id, $supplier, $kode_supplier)
    {
        return Supplier::updateData($id, $supplier, $kode_supplier);
    }

    public function deleteData($id)
    {
        return Supplier::deleteData($id);
    }

    public function exportTemplate()
    {
        return Excel::download(new supplierBarangTemplateExport, 'template_supplier_barang.xlsx');
    }

    public function importFromExcel($file)
    {
        Excel::import(new supplierBarangImport, $file);
    }
}
