<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'administrator')->first();
        $userRole = Role::where('slug', 'user')->first();

        // 1. Akun Administrator
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Utama',
                'email' => 'admin@instansi.go.id',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole?->id,
                'email_verified_at' => now(),
            ]
        );

        // 2. Akun Petugas Tata Usaha
        User::firstOrCreate(
            ['username' => 'petugas'],
            [
                'name' => 'Budi Santoso (Tata Usaha)',
                'email' => 'petugas@instansi.go.id',
                'password' => Hash::make('petugas123'),
                'role_id' => $userRole?->id,
                'email_verified_at' => now(),
            ]
        );

        // 3. Akun Staf Arsip
        User::firstOrCreate(
            ['username' => 'siti'],
            [
                'name' => 'Siti Rahmawati (Arsiparis)',
                'email' => 'siti@instansi.go.id',
                'password' => Hash::make('siti12345'),
                'role_id' => $userRole?->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
