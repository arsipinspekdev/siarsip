<x-layouts.app>
    <x-slot name="title">Ubah Wewenang — {{ $role->name }}</x-slot>
    <x-slot name="header">Ubah Wewenang (Role)</x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Wewenang (Roles)', 'url' => route('roles.index')],
        ['label' => 'Ubah ' . $role->name]
    ]" />

    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs p-5 sm:p-7 max-w-xl">
        <div class="mb-5 pb-4 border-b border-neutral-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center border border-warning-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-extrabold text-neutral-900">Perbarui Nama Wewenang</h2>
                <p class="text-xs text-neutral-500">Ubah nama tampilan role sesuai kebutuhan organisasi.</p>
            </div>
        </div>

        @if($role->slug === 'administrator')
            <div class="mb-4 flex items-start gap-2.5 px-3.5 py-3 bg-warning-50 border border-warning-200/80 rounded-xl">
                <svg class="w-4 h-4 text-warning-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-xs font-bold text-warning-800">Perhatian: Ini adalah role Administrator Sistem (bawaan).</p>
                    <p class="text-xs text-warning-700 mt-0.5">Slug sistem tidak dapat diubah, hanya nama tampilan yang dapat diperbarui.</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('roles.update', $role) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <x-form-input name="name" label="Nama Wewenang (Role)" :required="true" :value="$role->name" placeholder="Contoh: Kepala Tata Usaha" />

            <div class="pt-3 border-t border-neutral-100 flex items-center justify-end gap-3">
                <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-bold rounded-xl text-xs border border-neutral-200 transition">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-warning-600 hover:bg-warning-700 text-white font-bold rounded-xl text-xs shadow-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
