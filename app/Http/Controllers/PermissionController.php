<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PermissionController extends Controller
{
    /**
     * Display the visual matrix of Roles x Permissions.
     */
    public function index(): View
    {
        $roles = Role::with('permissions')->orderBy('id')->get();
        $permissions = Permission::all()->groupBy('module');

        $moduleLabels = [
            'surat_masuk'  => 'Modul Surat Masuk',
            'surat_keluar' => 'Modul Surat Keluar',
            'users'        => 'Modul Manajemen Pengguna',
            'roles'        => 'Modul Wewenang & Roles',
            'permissions'  => 'Modul Hak Akses Sistem',
        ];

        return view('permissions.index', compact('roles', 'permissions', 'moduleLabels'));
    }

    /**
     * Update the permission matrix associations.
     */
    public function update(Request $request): RedirectResponse
    {
        $matrix = $request->input('matrix', []); // array of [role_id => [permission_ids...]]
        $roles = Role::all();

        foreach ($roles as $role) {


            $selectedPermIds = isset($matrix[$role->id]) && is_array($matrix[$role->id]) 
                ? array_keys($matrix[$role->id]) 
                : [];

            $role->permissions()->sync($selectedPermIds);
        }

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Pengaturan Matrix Hak Akses berhasil disimpan.');
    }
}
