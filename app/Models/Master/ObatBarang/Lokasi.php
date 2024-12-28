<?php

namespace App\Models\Master\ObatBarang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi_barang';

    protected $fillable = [
        'lokasi',
        'kode_lokasi',
    ];

    public static function GetData(){
        return Lokasi::all(); // Mengambil semua data
    }

    public static function store($lokasi, $kode_lokasi)
    {
        return self::create([
            'kode_lokasi' => $kode_lokasi,
            'lokasi' => $lokasi,
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

    public static function updateData($id, $lokasi, $kode_lokasi)
    {
        return self::where('id', $id)->update([
            'kode_lokasi' => $kode_lokasi,
            'lokasi' => $lokasi,
        ]);
    }
}
