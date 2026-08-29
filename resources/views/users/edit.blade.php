<x-layouts.app>
    <x-slot name="title">Ubah Pengguna — {{ $user->name }}</x-slot>
    <x-slot name="header">Ubah Data Pengguna</x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Kelola Pengguna', 'url' => route('users.index')],
        ['label' => 'Ubah ' . $user->name]
    ]" />

    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs p-5 sm:p-7 max-w-3xl">
        <div class="mb-5 pb-4 border-b border-neutral-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center border border-warning-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-extrabold text-neutral-900">Perbarui Informasi Akun Pengguna</h2>
                <p class="text-xs text-neutral-500">Ubah data identitas, wewenang, atau setel ulang kata sandi.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <x-form-input name="name" label="Nama Lengkap Staf" :required="true" :value="$user->name" placeholder="Contoh: Budi Santoso, S.Sos" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form-input name="username" label="Nama Pengguna (Username)" :required="true" :value="$user->username" />
                <x-form-input name="email" label="Alamat Email Resmi" type="email" :required="true" :value="$user->email" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form-input name="password" label="Kata Sandi Baru" type="password" :required="false" placeholder="Kosongkan jika tidak diubah" helper="Biarkan kosong jika tetap menggunakan sandi lama" />
                <x-form-select name="role_id" label="Wewenang / Role" :required="true" :options="$roles" :selected="$user->role_id" />
            </div>

            <x-form-file name="photo" label="Foto Profil (Opsional)" :required="false" :existing-file="$user->photo" helper="Format: JPG, PNG (Maks. 2MB)" />

            <div class="pt-4 border-t border-neutral-100 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-bold rounded-xl text-xs border border-neutral-200 transition">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-warning-600 hover:bg-warning-700 text-white font-bold rounded-xl text-xs shadow-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
