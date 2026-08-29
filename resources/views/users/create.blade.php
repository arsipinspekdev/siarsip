<x-layouts.app>
    <x-slot name="title">Tambah Pengguna Baru</x-slot>
    <x-slot name="header">Tambah Akun Pengguna Baru</x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Kelola Pengguna', 'url' => route('users.index')],
        ['label' => 'Tambah Pengguna']
    ]" />

    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs p-5 sm:p-7 max-w-3xl">
        <div class="mb-5 pb-4 border-b border-neutral-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-success-50 text-success-600 flex items-center justify-center border border-success-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-extrabold text-neutral-900">Formulir Pembuatan Akun Staf</h2>
                <p class="text-xs text-neutral-500">Buat akun untuk staf atau administrator yang berhak mengakses sistem arsip.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <x-form-input name="name" label="Nama Lengkap Staf" :required="true" placeholder="Contoh: Budi Santoso, S.Sos" helper="Nama lengkap beserta gelar jika ada" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form-input name="username" label="Nama Pengguna (Username)" :required="true" placeholder="Contoh: budi_santoso" helper="Hanya huruf, angka, dan garis bawah (_)" />
                <x-form-input name="email" label="Alamat Email Resmi" type="email" :required="true" placeholder="Contoh: budi@instansi.go.id" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form-input name="password" label="Kata Sandi Awal" type="password" :required="true" placeholder="Minimal 6 karakter" helper="Pengguna dapat mengganti sandi ini nanti" />
                <x-form-select name="role_id" label="Wewenang / Role Pengguna" :required="true" :options="$roles" helper="Tentukan hak akses pengguna pada sistem" />
            </div>

            <x-form-file name="photo" label="Foto Profil Staf (Opsional)" :required="false" helper="Format: JPG, PNG (Maks. 2MB)" />

            <div class="pt-4 border-t border-neutral-100 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-bold rounded-xl text-xs border border-neutral-200 transition">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-success-600 hover:bg-success-700 text-white font-bold rounded-xl text-xs shadow-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Akun Pengguna
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
