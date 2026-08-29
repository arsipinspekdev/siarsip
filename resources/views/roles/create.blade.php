<x-layouts.app>
    <x-slot name="title">Tambah Wewenang Baru</x-slot>
    <x-slot name="header">Tambah Wewenang (Role) Baru</x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Wewenang (Roles)', 'url' => route('roles.index')],
        ['label' => 'Tambah Role Baru']
    ]" />

    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs p-5 sm:p-7 max-w-xl">
        <div class="mb-5 pb-4 border-b border-neutral-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-success-50 text-success-600 flex items-center justify-center border border-success-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-extrabold text-neutral-900">Formulir Wewenang Baru</h2>
                <p class="text-xs text-neutral-500">Buat grup wewenang baru untuk pengelompokan pengguna.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('roles.store') }}" class="space-y-4">
            @csrf
            <x-form-input name="name" label="Nama Wewenang (Role)" :required="true" placeholder="Contoh: Kepala Tata Usaha" helper="Nama yang mudah dikenali dan mewakili kelompok pengguna ini" />

            <div class="pt-3 border-t border-neutral-100 flex items-center justify-end gap-3">
                <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-bold rounded-xl text-xs border border-neutral-200 transition">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-success-600 hover:bg-success-700 text-white font-bold rounded-xl text-xs shadow-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Wewenang
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
