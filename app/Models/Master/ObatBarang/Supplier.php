<?php

namespace App\Models\Master\ObatBarang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier_barang';

    protected $fillable = [
        'supplier',
        'kode_supplier',
    ];

    public static function GetData(){
        return Supplier::all(); // Mengambil semua data
    }

    public static function store($supplier, $kode_supplier)
    {
        return self::create([
            'kode_supplier' => $kode_supplier,
            'supplier' => $supplier,
        ]);
    }

    public static function findData($id)
    {
        return self::findOrFail($id);
    }

    public static function deleteData($id)
    {
        return self::where('id', $id)->delete();
    }

    public static function updateData($id, $supplier, $kode_supplier)
    {
        return self::where('id', $id)->update([
            'kode_supplier' => $kode_supplier,
            'supplier' => $supplier,
        ]);
    }
}
