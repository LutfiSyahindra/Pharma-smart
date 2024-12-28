<?php

namespace App\Http\Controllers\Master\BarangObat;

use App\Http\Controllers\Controller;
use App\Services\Master\ObatBarang\LokasiService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class lokasiBarangObatController extends Controller
{

    protected $lokasiService;

    public function __construct(LokasiService $lokasiService)
    {
        $this->lokasiService = $lokasiService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts.master.barangObat.lokasiBarangObat.lokasiBarangObat');
    }

    public function table()
    {
        $lokasi = $this->lokasiService->getAll();
        $datalokasi = [];
        
        foreach ($lokasi as $lokasis) {
            $datalokasi[] = [
                'id' => $lokasis['id'],
                'kode_lokasi' => $lokasis['kode_lokasi'],
                'lokasi' => $lokasis['lokasi'],
                'created_at' => $lokasis['created_at'],
                'updated_at' => $lokasis['updated_at'],
            ];
        }

        return DataTables::of($datalokasi)
        ->addIndexColumn()
        ->addColumn('actions', function ($dataLokasi) {
            $lokasiNama = htmlspecialchars($dataLokasi['kode_lokasi'], ENT_QUOTES, 'UTF-8');
            return '
                <button class="btn btn-sm btn-info" onclick="editLokasi(' . $dataLokasi['id'] . ')"> <i class="mdi mdi-border-color "></i></button> 
                <button class="btn btn-sm btn-danger" onclick="deleteLokasi(' . $dataLokasi['id'] . ')">  <i class="mdi mdi-trash-can-outline"></i></button>
            ';
        })
        ->rawColumns(['actions'])
        ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'addmore.*.kode_lokasi' => 'required|string|max:255',
            'addmore.*.lokasi' => 'required|string|max:255',
        ]);

        // Proses dan simpan data satuan
        foreach ($request->addmore as $lokasiData) {
            // Panggil metode store dari service untuk menyimpan data
            $this->lokasiService->store($lokasiData['kode_lokasi'], $lokasiData['lokasi']);
        }

        // Redirect kembali dengan pesan sukses
        return response()->json([
            'status' => 'success',
            'message' => 'lokasi created successful!',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lokasiFind = $this->lokasiService->findData($id);

        // Cek jika data ditemukan
        if ($lokasiFind) {
            return response()->json([
                'status' => 'success',
                'lokasiFind' => $lokasiFind
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $lokasi = $request->input('lokasi');
        $kode_lokasi = $request->input('kode_lokasi');

        try {
            // Cari permission berdasarkan ID
            $lokasiUpdate = $this->lokasiService->UpdateData($id, $lokasi, $kode_lokasi);

            // Redirect atau return response
            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil diperbarui',
                'data' => $lokasiUpdate,
            ], 200);

        } catch (\Exception $e) {
            // Handle error jika terjadi
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui lokasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->lokasiService->deleteData($id);
            return response()->json([
                'success' => true,
                'message' => 'lokasi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    
    }

    public function downloadTemplate()
    {
        return $this->lokasiService->exportTemplate();
    }

    public function uploadExcel(Request $request)
    {
        // Validasi file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            // Import data menggunakan service
            $this->lokasiService->importFromExcel($request->file('file'));

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diupload.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
