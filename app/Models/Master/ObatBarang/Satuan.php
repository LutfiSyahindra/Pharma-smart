<?php

namespace App\Models\Master\ObatBarang;

use App\Exports\SatuanBarangTemplateExport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class Satuan extends Model
{
    use HasFactory;

    protected $table = 'satuan_barang';

    protected $fillable = [
        'satuan',
        'kode_satuan',
    ];

    public static function GetData(){
        return Satuan::all(); // Mengambil semua data
    }

    public static function store($satuan, $kode_satuan)
    {
        return self::create([
            'kode_satuan' => $kode_satuan,
            'satuan' => $satuan,
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

    public static function updateData($id, $satuan, $kode_satuan)
    {
        return self::where('id', $id)->update([
            'kode_satuan' => $kode_satuan,
            'satuan' => $satuan,
        ]);
    }

}
