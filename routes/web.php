<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Redirect root to dashboard
    Route::get('/', fn () => redirect()->route('dashboard'));

    // Logout
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profil Pengguna
    |--------------------------------------------------------------------------
    */
    Route::prefix('profil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/ganti-sandi', [ProfileController::class, 'updatePassword'])->name('password');
    });

    /*
    |--------------------------------------------------------------------------
    | Surat Masuk
    |--------------------------------------------------------------------------
    */
    Route::prefix('surat-masuk')->name('surat-masuk.')->group(function () {
        // Print (open new tab)
        Route::get('/cetak', [SuratMasukController::class, 'print'])->name('print')->middleware('permission:surat_masuk,export');

        // Exports
        Route::get('/ekspor/pdf', [SuratMasukController::class, 'exportPdf'])->name('export.pdf')->middleware('permission:surat_masuk,export');
        Route::get('/ekspor/excel', [SuratMasukController::class, 'exportExcel'])->name('export.excel')->middleware('permission:surat_masuk,export');
        Route::get('/ekspor/csv', [SuratMasukController::class, 'exportCsv'])->name('export.csv')->middleware('permission:surat_masuk,export');

        // Bulk Delete
        Route::delete('/bulk-destroy', [SuratMasukController::class, 'bulkDestroy'])->name('bulk-destroy')->middleware('permission:surat_masuk,delete');

        // Download file
        Route::get('/{suratMasuk}/unduh', [SuratMasukController::class, 'download'])->name('download')->middleware('permission:surat_masuk,view');

        // Standard Resource Routes (CRUD)
        Route::get('/', [SuratMasukController::class, 'index'])->name('index')->middleware('permission:surat_masuk,view');
        Route::get('/tambah', [SuratMasukController::class, 'create'])->name('create')->middleware('permission:surat_masuk,create');
        Route::post('/', [SuratMasukController::class, 'store'])->name('store')->middleware('permission:surat_masuk,create');
        Route::get('/{suratMasuk}', [SuratMasukController::class, 'show'])->name('show')->middleware('permission:surat_masuk,view');
        Route::get('/{suratMasuk}/ubah', [SuratMasukController::class, 'edit'])->name('edit')->middleware('permission:surat_masuk,update');
        Route::put('/{suratMasuk}', [SuratMasukController::class, 'update'])->name('update')->middleware('permission:surat_masuk,update');
        Route::delete('/{suratMasuk}', [SuratMasukController::class, 'destroy'])->name('destroy')->middleware('permission:surat_masuk,delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Surat Keluar
    |--------------------------------------------------------------------------
    */
    Route::prefix('surat-keluar')->name('surat-keluar.')->group(function () {
        // Print (open new tab)
        Route::get('/cetak', [SuratKeluarController::class, 'print'])->name('print')->middleware('permission:surat_keluar,export');

        // Exports
        Route::get('/ekspor/pdf', [SuratKeluarController::class, 'exportPdf'])->name('export.pdf')->middleware('permission:surat_keluar,export');
        Route::get('/ekspor/excel', [SuratKeluarController::class, 'exportExcel'])->name('export.excel')->middleware('permission:surat_keluar,export');
        Route::get('/ekspor/csv', [SuratKeluarController::class, 'exportCsv'])->name('export.csv')->middleware('permission:surat_keluar,export');

        // Bulk Delete
        Route::delete('/bulk-destroy', [SuratKeluarController::class, 'bulkDestroy'])->name('bulk-destroy')->middleware('permission:surat_keluar,delete');

        // Download file
        Route::get('/{suratKeluar}/unduh', [SuratKeluarController::class, 'download'])->name('download')->middleware('permission:surat_keluar,view');

        // Standard Resource Routes (CRUD)
        Route::get('/', [SuratKeluarController::class, 'index'])->name('index')->middleware('permission:surat_keluar,view');
        Route::get('/tambah', [SuratKeluarController::class, 'create'])->name('create')->middleware('permission:surat_keluar,create');
        Route::post('/', [SuratKeluarController::class, 'store'])->name('store')->middleware('permission:surat_keluar,create');
        Route::get('/{suratKeluar}', [SuratKeluarController::class, 'show'])->name('show')->middleware('permission:surat_keluar,view');
        Route::get('/{suratKeluar}/ubah', [SuratKeluarController::class, 'edit'])->name('edit')->middleware('permission:surat_keluar,update');
        Route::put('/{suratKeluar}', [SuratKeluarController::class, 'update'])->name('update')->middleware('permission:surat_keluar,update');
        Route::delete('/{suratKeluar}', [SuratKeluarController::class, 'destroy'])->name('destroy')->middleware('permission:surat_keluar,delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin: User Management
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:users,view')->prefix('pengguna')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/tambah', [UserController::class, 'create'])->name('create')->middleware('permission:users,create');
        Route::post('/', [UserController::class, 'store'])->name('store')->middleware('permission:users,create');
        Route::get('/{user}/ubah', [UserController::class, 'edit'])->name('edit')->middleware('permission:users,update');
        Route::put('/{user}', [UserController::class, 'update'])->name('update')->middleware('permission:users,update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy')->middleware('permission:users,delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin: Roles Management
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:roles,view')->prefix('wewenang')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/tambah', [RoleController::class, 'create'])->name('create')->middleware('permission:roles,create');
        Route::post('/', [RoleController::class, 'store'])->name('store')->middleware('permission:roles,create');
        Route::get('/{role}/ubah', [RoleController::class, 'edit'])->name('edit')->middleware('permission:roles,update');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update')->middleware('permission:roles,update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy')->middleware('permission:roles,delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin: Permissions Matrix
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:permissions,view')->prefix('hak-akses')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::put('/', [PermissionController::class, 'update'])->name('update')->middleware('permission:permissions,update');
    });
});
