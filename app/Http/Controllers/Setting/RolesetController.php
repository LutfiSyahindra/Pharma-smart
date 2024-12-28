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

class RolesetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts.setting.role.role');
    }

    public function table()
    {
        // Ambil data role beserta permissions-nya menggunakan eager loading
        $roles = Role::with('permissions')->get();

        // Siapkan data untuk DataTables
        $dataRole = $roles->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'created_at' => $role->created_at,
                'updated_at' => $role->updated_at,
                'permissions' => $role->permissions->map(function ($permission) {
                return '<span class="badge bg-success">' . htmlspecialchars($permission->name, ENT_QUOTES, 'UTF-8') . '</span>';
            })->implode(' '),
            ];
        });

        return DataTables::of($dataRole)
            ->addColumn('actions', function ($role) {
                // Encode nama role untuk menghindari konflik jika mengandung tanda kutip
                $roleName = htmlspecialchars($role['name'], ENT_QUOTES, 'UTF-8');
                return '
                    <button class="btn btn-sm btn-info" onclick="permission(' . $role['id'] . ', \'' . $roleName . '\')"><i class="mdi mdi-wrench-outline "></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteRole(' . $role['id'] . ')">
                    <i class="mdi mdi-trash-can-outline"></i>
                    </button>
                ';
            })
            ->rawColumns(['actions','permissions'])
            ->make(true);
    }

    public function Getpermission($roleId)
    {
        // Dapatkan permission yang terkait dengan roleId
        $permissions = Permission::all(); // Ganti logika ini sesuai dengan struktur database Anda
        $role = Role::find($roleId);
        
        return response()->json([
            'permissions' => $permissions,
            'role_permissions' => $role ? $role->permissions()->pluck('id') : [], // ID permission yang sudah terhubung dengan role
        ]);
    }

    public function assignPermissions(Request $request, $roleId)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($roleId);

        // Sinkronisasi permissions dengan role
        $role->permissions()->sync($validated['permissions']);

        return response()->json([
            'status' => 'success',
            'message' => 'Permissions successfully assigned to the role.',
        ]);
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
            'name' => 'required|unique:roles,name|max:255',
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
        Role::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successful!',
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Cari role berdasarkan ID
            $role = Role::findOrFail($id);

            // Hapus role
            $role->delete();

            // Berikan respons JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dihapus.'
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
