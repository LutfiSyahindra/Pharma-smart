<?php

namespace App\Http\Controllers\Master\BarangObat;

use App\Http\Controllers\Controller;
use App\Services\Master\ObatBarang\SupplierService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierBarangObatController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts.master.barangObat.supplierbarangObat.supplierBarangObat');
    }

    public function table()
    {
        $supplier = $this->supplierService->getAll();
        $datasupplier = [];
        
        foreach ($supplier as $suppliers) {
            $datasupplier[] = [
                'id' => $suppliers['id'],
                'kode_supplier' => $suppliers['kode_supplier'],
                'supplier' => $suppliers['supplier'],
                'created_at' => $suppliers['created_at'],
                'updated_at' => $suppliers['updated_at'],
            ];
        }

        return DataTables::of($datasupplier)
        ->addIndexColumn()
        ->addColumn('actions', function ($dataSupplier) {
            $supplierNama = htmlspecialchars($dataSupplier['kode_supplier'], ENT_QUOTES, 'UTF-8');
            return '
                <button class="btn btn-sm btn-info" onclick="editSupplier(' . $dataSupplier['id'] . ')"> <i class="mdi mdi-border-color "></i></button> 
                <button class="btn btn-sm btn-danger" onclick="deleteSupplier(' . $dataSupplier['id'] . ')">  <i class="mdi mdi-trash-can-outline"></i></button>
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
            'addmore.*.kode_supplier' => 'required|string|max:255',
            'addmore.*.supplier' => 'required|string|max:255',
        ]);

        // Proses dan simpan data satuan
        foreach ($request->addmore as $supplierData) {
            // Panggil metode store dari service untuk menyimpan data
            $this->supplierService->store($supplierData['kode_supplier'], $supplierData['supplier']);
        }

        // Redirect kembali dengan pesan sukses
        return response()->json([
            'status' => 'success',
            'message' => 'supplier created successful!',
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
        $supplierFind = $this->supplierService->findData($id);

        // Cek jika data ditemukan
        if ($supplierFind) {
            return response()->json([
                'status' => 'success',
                'supplierFind' => $supplierFind
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

        $supplier = $request->input('supplier');
        $kode_supplier = $request->input('kode_supplier');

        try {
            // Cari permission berdasarkan ID
            $supplierUpdate = $this->supplierService->UpdateData($id, $supplier, $kode_supplier);

            // Redirect atau return response
            return response()->json([
                'success' => true,
                'message' => 'supplier berhasil diperbarui',
                'data' => $supplierUpdate,
            ], 200);

        } catch (\Exception $e) {
            // Handle error jika terjadi
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui supplier: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->supplierService->deleteData($id);
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
        return $this->supplierService->exportTemplate();
    }

    public function uploadExcel(Request $request)
    {
        // Validasi file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            // Import data menggunakan service
            $this->supplierService->importFromExcel($request->file('file'));

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
