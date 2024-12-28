<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class SetuserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts.setting.user.user');
    }
    public function table()
    {
        $users = User::GetData();

        $dataUsers = [];
        
        foreach ($users as $user) {
            $dataUsers[] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'status' => $user['status'],
                'roles' => $user->getRoleNames()->join(', '),
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at'],
            ];
        }
        Log::info($dataUsers);

        return DataTables::of($dataUsers)
        ->addColumn('status', function ($user) {
            // Cek status dan berikan warna badge sesuai
            $badgeColor = match ($user['status']) {
                'aktif' => 'bg-success',
                'non-aktif' => 'bg-secondary',
                'pending' => 'bg-warning',
                default => 'bg-danger',
            };

            return '<span class="badge ' . $badgeColor . '">' . ucfirst($user['status']) . '</span>';
        })
        ->addColumn('roles', function ($user) {
            // Menampilkan roles sebagai badge (optional customization)
            $roles = explode(', ', $user['roles']); // Pecah string roles jika ada lebih dari satu
            $badgeRoles = array_map(fn($role) => '<span class="badge bg-primary">' . ucfirst($role) . '</span>', $roles);

            return implode(' ', $badgeRoles);
        })
        ->addColumn('actions', function ($user) {
            $userName = htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
            return '
                <button class="btn btn-sm btn-warning" onclick="roleUser(' . $user['id'] . ', \'' . $userName . '\')">
                    <i class="mdi mdi-account-key"></i>
                </button>
                <button class="btn btn-sm btn-info" onclick="editUser(' . $user['id'] .')">
                    <i class="mdi mdi-account-convert"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteUser(' . $user['id'] . ')">
                    <i class="mdi mdi-trash-can-outline"></i>
                </button>
            ';
        })

        ->rawColumns(['status', 'actions', 'roles']) // Tambahkan 'status' agar HTML di-render
        ->make(true);

    }

    public function Getrole($id)
    {
        // Ambil semua role
        $roles = Role::all();

        // Temukan user berdasarkan ID
        $user = User::find($id);

        // Role yang dimiliki user (ID saja)
        $userRoles = $user ? $user->roles()->pluck('id') : [];

        // Kirim respons JSON
        return response()->json([
            'roles' => $roles, // Semua role
            'user_roles' => $userRoles, // Role yang dimiliki user
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
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
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Signup successful!',
        ], 200);
    }

    public function assignRole(Request $request, $usersId)
    {
        // Validasi input
        $validated = $request->validate([
            'usersId' => 'required|exists:users,id',
            'role' => 'required|array',
            'role.*' => 'exists:roles,id', // Validasi berdasarkan ID role
        ]);

        // Temukan user
        $user = User::findOrFail($usersId);

        // Assign role menggunakan ID
        $user->syncRoles(Role::whereIn('id', $validated['role'])->pluck('name')->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil diberikan ke user.',
        ]);
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
        $user = User::findOrFail($id); // Pastikan user ditemukan atau gagal dengan error 404
        Log::info($user);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email']));

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Cari role berdasarkan ID
            $user = User::findOrFail($id);

            // Hapus role
            $user->delete();

            // Berikan respons JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'user berhasil dihapus.'
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
