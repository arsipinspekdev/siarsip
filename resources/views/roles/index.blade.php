<x-layouts.app>
    <x-slot name="title">Wewenang / Roles</x-slot>
    <x-slot name="header">Kelola Wewenang (Roles)</x-slot>

    <x-breadcrumb :items="[['label' => 'Pengaturan Admin'], ['label' => 'Wewenang (Roles)']]" />

    {{-- Header toolbar --}}
    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs px-4 py-3 mb-5 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-sm font-extrabold text-neutral-900">Daftar Wewenang Pengguna</h2>
            <p class="text-xs text-neutral-500 mt-0.5">Grup peran untuk mengelompokkan izin akses pengguna.</p>
        </div>
        @if(auth()->check() && auth()->user()->hasPermission('roles', 'create'))
            <a href="{{ route('roles.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-success-600 hover:bg-success-700 text-white font-bold rounded-xl text-xs shadow-xs transition flex-shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Wewenang
            </a>
        @endif
    </div>

    {{-- Tabel --}}
    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs overflow-hidden max-w-4xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-neutral-50/80 border-b border-neutral-200/90">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500 w-12 text-center">No.</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Nama Role</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Kode Sistem (Slug)</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500 text-center">Jumlah Pengguna</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($roles as $index => $r)
                        <tr class="hover:bg-neutral-50/60 transition">
                            <td class="px-4 py-3 text-center text-xs font-bold text-neutral-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-xs font-bold text-neutral-900">
                                {{ $r->name }}
                                @if($r->slug === 'administrator')
                                    <span class="ml-1.5 px-1.5 py-0.5 bg-primary-50 text-primary-700 text-[10px] font-bold rounded border border-primary-100">Super Admin</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs font-mono font-semibold text-neutral-500">{{ $r->slug }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-0.5 bg-neutral-100 border border-neutral-200 rounded-lg text-xs font-bold text-neutral-700">{{ $r->users_count }} Orang</span>
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if(auth()->check() && auth()->user()->hasPermission('roles', 'update'))
                                        <a href="{{ route('roles.edit', $r) }}" class="px-2.5 py-1 bg-warning-50 text-warning-700 hover:bg-warning-100 font-bold rounded-lg text-xs border border-warning-200 transition">Ubah</a>
                                    @endif
                                    @if($r->slug !== 'administrator' && auth()->check() && auth()->user()->hasPermission('roles', 'delete'))
                                        <form method="POST" action="{{ route('roles.destroy', $r) }}" data-confirm-delete data-confirm-message="Hapus role '{{ $r->name }}'?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 bg-danger-50 text-danger-700 hover:bg-danger-100 font-bold rounded-lg text-xs border border-danger-200 transition">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-xs text-neutral-500 font-semibold">Belum ada wewenang / role.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
