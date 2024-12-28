<?php

use App\Http\Controllers\Master\BarangObat\lokasiBarangObatController;
use App\Http\Controllers\Master\BarangObat\SatuanBarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Setting\PermissionsetController;
use App\Http\Controllers\Setting\RolesetController;
use App\Http\Controllers\Setting\UsersetController;
use App\Http\Controllers\Setting\SetuserController;
use Hamcrest\Core\Set;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Dashboard dengan middleware auth dan verified
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute yang membutuhkan autentikasi
Route::middleware('auth')->group(function () {
    // Profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'settings'], function () {
        // User
        Route::get('/user', [SetuserController::class, 'index'])->name('settings.user');
        Route::post('/user/store', [SetuserController::class, 'store'])->name('setting.user.store');
        Route::get('/user/table', [SetuserController::class, 'table'])->name('setting.user.table');
        Route::get('/user/{id}/roles', [SetuserController::class, 'Getrole'])->name('setting.user.getrole');
        Route::get('/user/{id}/edit', [SetuserController::class, 'edit'])->name('setting.user.edit');
        Route::put('/user/{id}/update', [SetuserController::class, 'update'])->name('setting.user.update');
        Route::post('/user/{usersId}/assign-roles', [SetuserController::class, 'assignRole'])->name('setting.user.assign-roles');
        Route::delete('/user/{id}/delete', [SetuserController::class, 'destroy'])->name('setting.user.destroy');

        
        // Role
        Route::get('/role', [RolesetController::class, 'index'])->name('settings.role');
        Route::post('/role/store', [RolesetController::class, 'store'])->name('setting.role.store');
        Route::get('/role/table', [RolesetController::class, 'table'])->name('setting.role.table');
        Route::get('/role/{roleId}/permissions', [RolesetController::class, 'Getpermission'])->name('setting.role.getpermissions');
        Route::post('/role/{roleId}/assign-permissions', [RolesetController::class, 'assignPermissions'])->name('setting.role.assign-permissions');
        Route::delete('/role/{id}', [RolesetController::class, 'destroy'])->name('setting.role.destroy');

        // Permissions
        Route::get('/permissions', [PermissionsetController::class, 'index'])->name('settings.permissions');
        Route::post('/permissions/store', [PermissionsetController::class, 'store'])->name('setting.permissions.store');
        Route::get('/permissions/table', [PermissionsetController::class, 'table'])->name('setting.permissions.table');
        Route::put('/permissions/{id}', [PermissionsetController::class, 'update'])->name('setting.permissions.update');
        Route::delete('/permissions/{id}/delete', [PermissionsetController::class, 'destroy'])->name('setting.permissions.delete');
    });

    Route::group(['prefix' => 'master_Barang_Obat'], function () {
        // Satuan Barang
        Route::get('/satuanBarang', [SatuanBarangController::class, 'index'])->name('master.satuanBarang');
        Route::get('/satuanBarang/table', [SatuanBarangController::class, 'table'])->name('master.satuanBarang.table');
        Route::post('/satuanBarang/store', [SatuanBarangController::class, 'store'])->name('master.satuanBarang.store');
        Route::get('/satuanBarang/{id}/edit', [SatuanBarangController::class, 'edit'])->name('master.satuanBarang.edit');
        Route::put('/satuanBarang/{id}/update', [SatuanBarangController::class, 'update'])->name('master.satuanBarang.update');
        Route::delete('/satuanBarang/{id}/delete', [SatuanBarangController::class, 'destroy'])->name('master.satuanBarang.delete');
        Route::get('/satuanBarang/downloadTemplate', [SatuanBarangController::class, 'downloadTemplate'])->name('master.satuanBarang.downloadTemplate');
        Route::post('/satuanBarang/uploadExcel', [SatuanBarangController::class, 'uploadExcel'])->name('master.satuanBarang.uploadExcel');

        // Lokasi Barang
        Route::get('/lokasiBarang', [lokasiBarangObatController::class, 'index'])->name('master.lokasiBarang');
        Route::get('/lokasiBarang/table', [lokasiBarangObatController::class, 'table'])->name('master.lokasiBarang.table');
        Route::post('/lokasiBarang/store', [lokasiBarangObatController::class, 'store'])->name('master.lokasiBarang.store');
        Route::get('/lokasiBarang/{id}/edit', [lokasiBarangObatController::class, 'edit'])->name('master.lokasiBarang.edit');
        Route::put('/lokasiBarang/{id}/update', [lokasiBarangObatController::class, 'update'])->name('master.lokasiBarang.update');
        Route::delete('/lokasiBarang/{id}/delete', [lokasiBarangObatController::class, 'destroy'])->name('master.lokasiBarang.delete');
        Route::get('/lokasiBarang/downloadTemplate', [lokasiBarangObatController::class, 'downloadTemplate'])->name('master.lokasiBarang.downloadTemplate');
        Route::post('/lokasiBarang/uploadTemplate', [lokasiBarangObatController::class, 'uploadExcel'])->name('master.lokasiBarang.uploadTemplate');

        // Supplier Barang
        Route::get('/lokasiBarang', [lokasiBarangObatController::class, 'index'])->name('master.lokasiBarang');
        Route::get('/lokasiBarang/table', [lokasiBarangObatController::class, 'table'])->name('master.lokasiBarang.table');
        Route::post('/lokasiBarang/store', [lokasiBarangObatController::class, 'store'])->name('master.lokasiBarang.store');
        Route::get('/lokasiBarang/{id}/edit', [lokasiBarangObatController::class, 'edit'])->name('master.lokasiBarang.edit');
        Route::put('/lokasiBarang/{id}/update', [lokasiBarangObatController::class, 'update'])->name('master.lokasiBarang.update');
        Route::delete('/lokasiBarang/{id}/delete', [lokasiBarangObatController::class, 'destroy'])->name('master.lokasiBarang.delete');
        Route::get('/lokasiBarang/downloadTemplate', [lokasiBarangObatController::class, 'downloadTemplate'])->name('master.lokasiBarang.downloadTemplate');
        Route::post('/lokasiBarang/uploadTemplate', [lokasiBarangObatController::class, 'uploadExcel'])->name('master.lokasiBarang.uploadTemplate');
    });

});

// Rute autentikasi
require __DIR__ . '/auth.php';
