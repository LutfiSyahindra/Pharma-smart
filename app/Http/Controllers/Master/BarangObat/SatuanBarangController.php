<?php

namespace App\Http\Controllers\Master\BarangObat;

use App\Exports\SatuanBarangTemplateExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Master\ObatBarang\SatuanService;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SatuanBarangController extends Controller
{

    protected $satuanService;

    public function __construct(SatuanService $satuanService)
    {
        $this->satuanService = $satuanService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts.master.barangObat.satuanBarang.satuanBarang');
    }

    public function table()
    {
        $satuan = $this->satuanService->getAll();
        $dataSatuan = [];
        
        foreach ($satuan as $satuans) {
            $dataSatuan[] = [
                'id' => $satuans['id'],
                'kode_satuan' => $satuans['kode_satuan'],
                'satuan' => $satuans['satuan'],
                'created_at' => $satuans['created_at'],
                'updated_at' => $satuans['updated_at'],
            ];
        }

        return DataTables::of($dataSatuan)
        ->addIndexColumn()
        ->addColumn('actions', function ($dataSatuan) {
            $satuanNama = htmlspecialchars($dataSatuan['kode_satuan'], ENT_QUOTES, 'UTF-8');
            return '
                <button class="btn btn-sm btn-info" onclick="editSatuan(' . $dataSatuan['id'] . ')"> <i class="mdi mdi-border-color "></i></button> 
                <button class="btn btn-sm btn-danger" onclick="deletePermission(' . $dataSatuan['id'] . ')">  <i class="mdi mdi-trash-can-outline"></i></button>
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'addmore.*.kode_satuan' => 'required|string|max:255',
            'addmore.*.satuan' => 'required|string|max:255',
        ]);

        // Proses dan simpan data satuan
        foreach ($request->addmore as $satuanData) {
            // Panggil metode store dari service untuk menyimpan data
            $this->satuanService->store($satuanData['kode_satuan'], $satuanData['satuan']);
        }

        // Redirect kembali dengan pesan sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Satuan created successful!',
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
    public function edit($id)
    {
        $satuanFind = $this->satuanService->findData($id);

        // Cek jika data ditemukan
        if ($satuanFind) {
            return response()->json([
                'status' => 'success',
                'satuanFind' => $satuanFind
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

        $satuan = $request->input('satuan');
        $kode_satuan = $request->input('kode_satuan');

        try {
            // Cari permission berdasarkan ID
            $satuanUpdate = $this->satuanService->UpdateData($id, $satuan, $kode_satuan);

            // Redirect atau return response
            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil diperbarui',
                'data' => $satuanUpdate,
            ], 200);

        } catch (\Exception $e) {
            // Handle error jika terjadi
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui permission: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->satuanService->deleteData($id);
            return response()->json([
                'success' => true,
                'message' => 'satuanberhasil dihapus.'
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
        return $this->satuanService->exportTemplate();
    }

    public function uploadExcel(Request $request)
    {
        // Validasi file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            // Import data menggunakan service
            $this->satuanService->importFromExcel($request->file('file'));

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
