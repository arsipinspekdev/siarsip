<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

final class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Modul Surat Masuk
            ['module' => 'surat_masuk', 'action' => 'view', 'label' => 'Lihat Daftar & Detail Surat Masuk'],
            ['module' => 'surat_masuk', 'action' => 'create', 'label' => 'Tambah Surat Masuk Baru'],
            ['module' => 'surat_masuk', 'action' => 'update', 'label' => 'Ubah Data Surat Masuk'],
            ['module' => 'surat_masuk', 'action' => 'delete', 'label' => 'Hapus Data Surat Masuk'],
            ['module' => 'surat_masuk', 'action' => 'export', 'label' => 'Cetak & Unduh Laporan Surat Masuk (PDF/Excel/CSV)'],

            // Modul Surat Keluar
            ['module' => 'surat_keluar', 'action' => 'view', 'label' => 'Lihat Daftar & Detail Surat Keluar'],
            ['module' => 'surat_keluar', 'action' => 'create', 'label' => 'Tambah Surat Keluar Baru'],
            ['module' => 'surat_keluar', 'action' => 'update', 'label' => 'Ubah Data Surat Keluar'],
            ['module' => 'surat_keluar', 'action' => 'delete', 'label' => 'Hapus Data Surat Keluar'],
            ['module' => 'surat_keluar', 'action' => 'export', 'label' => 'Cetak & Unduh Laporan Surat Keluar (PDF/Excel/CSV)'],

            // Modul Manajemen Pengguna
            ['module' => 'users', 'action' => 'view', 'label' => 'Lihat Daftar Pengguna'],
            ['module' => 'users', 'action' => 'create', 'label' => 'Tambah Pengguna Baru'],
            ['module' => 'users', 'action' => 'update', 'label' => 'Ubah Data & Hak Pengguna'],
            ['module' => 'users', 'action' => 'delete', 'label' => 'Hapus Pengguna'],

            // Modul Wewenang / Roles
            ['module' => 'roles', 'action' => 'view', 'label' => 'Lihat Wewenang/Role'],
            ['module' => 'roles', 'action' => 'create', 'label' => 'Tambah Wewenang/Role Baru'],
            ['module' => 'roles', 'action' => 'update', 'label' => 'Ubah Wewenang/Role'],
            ['module' => 'roles', 'action' => 'delete', 'label' => 'Hapus Wewenang/Role'],

            // Modul Hak Akses / Permissions
            ['module' => 'permissions', 'action' => 'view', 'label' => 'Lihat Matrix Hak Akses'],
            ['module' => 'permissions', 'action' => 'update', 'label' => 'Atur Matrix Hak Akses'],
        ];

        $permissionIds = [];
        foreach ($permissions as $perm) {
            $created = Permission::firstOrCreate(
                ['module' => $perm['module'], 'action' => $perm['action']],
                $perm
            );
            $permissionIds[$perm['module'] . '.' . $perm['action']] = $created->id;
        }

        // Role Administrator: Akses Penuh ke Semua Permission
        $adminRole = Role::where('slug', 'administrator')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync(array_values($permissionIds));
        }

        // Role Petugas/User: Akses ke Surat Masuk & Surat Keluar (view, create, update, export) - TANPA delete
        $userRole = Role::where('slug', 'user')->first();
        if ($userRole) {
            $userPerms = [
                $permissionIds['surat_masuk.view'] ?? null,
                $permissionIds['surat_masuk.create'] ?? null,
                $permissionIds['surat_masuk.update'] ?? null,
                $permissionIds['surat_masuk.export'] ?? null,
                $permissionIds['surat_keluar.view'] ?? null,
                $permissionIds['surat_keluar.create'] ?? null,
                $permissionIds['surat_keluar.update'] ?? null,
                $permissionIds['surat_keluar.export'] ?? null,
            ];
            $userRole->permissions()->sync(array_filter($userPerms));
        }
    }
}
