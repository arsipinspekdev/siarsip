<x-layouts.app>
    <x-slot name="title">Manajemen Pengguna</x-slot>
    <x-slot name="header">Kelola Akun Pengguna</x-slot>

    <x-breadcrumb :items="[['label' => 'Pengaturan Admin'], ['label' => 'Pengguna']]" />

    {{-- Toolbar --}}
    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs px-4 py-3 mb-5 flex flex-col md:flex-row items-stretch md:items-center gap-3">
        <form method="GET" action="{{ route('users.index') }}" class="flex-1 flex items-center gap-2">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-neutral-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari nama, username, atau email staf..."
                    class="w-full pl-9 pr-4 py-2 text-sm text-neutral-900 bg-neutral-50 border border-neutral-200/90 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition focus:outline-none placeholder:text-neutral-400"
                />
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-xs transition">Cari</button>
            @if(request('q'))
                <a href="{{ route('users.index') }}" class="px-3 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-semibold rounded-xl text-xs border border-neutral-200 transition">Reset</a>
            @endif
        </form>

        @if(auth()->check() && auth()->user()->hasPermission('users', 'create'))
            <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-success-600 hover:bg-success-700 text-white font-bold rounded-xl text-xs shadow-xs transition flex-shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Tambah Pengguna
            </a>
        @endif
    </div>

    {{-- Tabel --}}
    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-neutral-50/80 border-b border-neutral-200/90">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Foto</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Nama Lengkap</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Username</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Email</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Role</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-neutral-50/60 transition">
                            <td class="px-4 py-3">
                                <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}" class="w-9 h-9 rounded-xl object-cover border border-neutral-200" />
                            </td>
                            <td class="px-4 py-3 font-bold text-neutral-900 text-xs">
                                {{ $u->name }}
                                @if($u->id === auth()->id())
                                    <span class="ml-1.5 px-1.5 py-0.5 bg-primary-50 text-primary-700 text-[10px] font-bold rounded border border-primary-100">Anda</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs font-semibold text-neutral-600">{{ $u->username }}</td>
                            <td class="px-4 py-3 text-xs text-neutral-600">{{ $u->email }}</td>
                            <td class="px-4 py-3">
                                @if($u->role?->slug === 'administrator')
                                    <span class="px-2 py-0.5 bg-primary-50 text-primary-700 font-bold text-xs rounded-lg border border-primary-100">{{ $u->role->name }}</span>
                                @else
                                    <span class="px-2 py-0.5 bg-neutral-100 text-neutral-600 font-semibold text-xs rounded-lg border border-neutral-200">{{ $u->role?->name ?? 'Tanpa Role' }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if(auth()->check() && auth()->user()->hasPermission('users', 'update'))
                                        <a href="{{ route('users.edit', $u) }}" class="px-2.5 py-1 bg-warning-50 text-warning-700 hover:bg-warning-100 font-bold rounded-lg text-xs border border-warning-200 transition">Ubah</a>
                                    @endif
                                    @if($u->id !== auth()->id() && auth()->check() && auth()->user()->hasPermission('users', 'delete'))
                                        <form method="POST" action="{{ route('users.destroy', $u) }}" data-confirm-delete data-confirm-message="Hapus akun pengguna '{{ $u->name }}'?">
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
                            <td colspan="6" class="p-0">
                                <x-empty-state
                                    title="Pengguna Tidak Ditemukan"
                                    message="Belum ada data pengguna yang cocok."
                                    action-label="+ Tambah Pengguna Baru"
                                    :action-url="auth()->check() && auth()->user()->hasPermission('users', 'create') ? route('users.create') : ''"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-pagination :paginator="$users" />
</x-layouts.app>
