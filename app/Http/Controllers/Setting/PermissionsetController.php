<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class PermissionsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts.setting.permission.permission');
    }

    public function table()
    {
        $permission = Permission::all();
        Log::info($permission);
        $dataPermission = [];
        
        foreach ($permission as $permissions) {
            $dataPermission[] = [
                'id' => $permissions['id'],
                'name' => $permissions['name'],
                'guard_name' => $permissions['guard_name'],
                'created_at' => $permissions['created_at'],
                'updated_at' => $permissions['updated_at'],
            ];
        }
        Log::info($dataPermission);

        return DataTables::of($dataPermission)
        ->addColumn('actions', function ($permission) {
            $permissionName = htmlspecialchars($permission['name'], ENT_QUOTES, 'UTF-8');
            return '
                <button class="btn btn-sm btn-info" onclick="editPermission(' . $permission['id'] . ', \'' . $permissionName . '\')"> <i class="mdi mdi-border-color "></i></button> 
                <button class="btn btn-sm btn-danger" onclick="deletePermission(' . $permission['id'] . ')">  <i class="mdi mdi-trash-can-outline"></i></button>
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name|max:255',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Jika validasi berhasil
        // Simpan data ke database
        Permission::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Permission created successful!',
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi data input
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        try {
            // Cari permission berdasarkan ID
            $permission = Permission::findOrFail($id);

            // Update permission dengan data dari request
            $permission->name = $request->input('name');
            $permission->save();

            // Redirect atau return response
            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil diperbarui',
                'data' => $permission,
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
    public function destroy($id)
    {
        try {
            // Cari role berdasarkan ID
            $user = Permission::findOrFail($id);

            // Hapus role
            $user->delete();

            // Berikan respons JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'permission berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
