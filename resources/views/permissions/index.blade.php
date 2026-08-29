<x-layouts.app>
    <x-slot name="title">Matrix Hak Akses</x-slot>
    <x-slot name="header">Pengaturan Matrix Hak Akses Sistem</x-slot>

    <x-breadcrumb :items="[['label' => 'Pengaturan Admin'], ['label' => 'Matrix Hak Akses']]" />

    {{-- Info Banner --}}
    <div class="mb-5 flex items-start gap-3 px-4 py-3 bg-primary-50 border border-primary-200/80 rounded-2xl">
        <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            <p class="text-xs font-bold text-primary-900">Cara Penggunaan Matrix Hak Akses</p>
            <p class="text-xs text-primary-800 mt-0.5">Centang kolom izin untuk setiap wewenang (role). Role <strong>Administrator</strong> secara otomatis memiliki semua hak akses. Klik "Simpan Pengaturan" setelah selesai.</p>
        </div>
    </div>

    {{-- Jadikan FORM sebagai container utama --}}
    <form method="POST" action="{{ route('permissions.update') }}" class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs overflow-hidden relative z-10 block">
        @csrf
        @method('PUT')

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-neutral-50/80 border-b border-neutral-200/90">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500 w-56">Modul / Aksi</th>
                        @foreach($roles as $role)
                            <th class="px-4 py-3 text-xs font-bold text-center text-neutral-700 whitespace-nowrap">
                                {{ $role->name }}
                                @if($role->slug === 'administrator')
                                    <span class="block text-[10px] font-bold text-primary-600 mt-0.5">(Super Admin)</span>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($permissions as $module => $perms)
                        {{-- Module Header Row --}}
                        <tr class="bg-neutral-800">
                            <td colspan="{{ $roles->count() + 1 }}" class="px-4 py-2.5">
                                <span class="text-xs font-extrabold text-white uppercase tracking-widest">
                                    {{ $moduleLabels[$module] ?? ucfirst(str_replace('_', ' ', $module)) }}
                                </span>
                            </td>
                        </tr>

                        @php
                            $canUpdate = auth()->check() && auth()->user()->hasPermission('permissions', 'update');
                        @endphp

                        @foreach($perms as $perm)
                            <tr class="hover:bg-neutral-50/60 transition">
                                <td class="px-4 py-3 pl-6 text-xs font-semibold text-neutral-700">{{ $perm->label }}</td>
                                @foreach($roles as $role)
                                    @php
                                        $hasPermission = $role->permissions->contains($perm->id);
                                    @endphp
                                    <td class="px-4 py-3 text-center">
                                        <label class="inline-flex items-center justify-center {{ $canUpdate ? 'cursor-pointer group' : 'cursor-not-allowed opacity-80' }} relative z-20" title="Izin {{ $perm->label }} untuk {{ $role->name }}">
                                            <input
                                                type="checkbox"
                                                name="matrix[{{ $role->id }}][{{ $perm->id }}]"
                                                value="1"
                                                class="w-5 h-5 text-success-600 rounded border border-neutral-300 focus:ring-success-500 {{ $canUpdate ? 'cursor-pointer group-hover:border-success-400' : 'cursor-not-allowed' }} transition"
                                                {{ $hasPermission ? 'checked' : '' }}
                                                {{ !$canUpdate ? 'disabled' : '' }}
                                            />
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Save Button --}}
        <div class="px-5 py-4 bg-neutral-50/80 border-t border-neutral-200/90 flex items-center justify-between relative z-20">
            @if($canUpdate)
                <p class="text-xs text-neutral-500 font-medium">Perubahan akan langsung berlaku setelah disimpan.</p>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-success-600 hover:bg-success-700 text-white font-bold rounded-xl text-xs shadow-xs transition cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Pengaturan Hak Akses
                </button>
            @else
                <p class="text-xs text-neutral-500 font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Anda dalam mode <strong>Hanya Lihat</strong> (Read-Only). Hubungi Administrator jika memerlukan izin untuk mengubah matriks hak akses.</span>
                </p>
                <span class="px-3 py-1 bg-neutral-200 text-neutral-600 font-bold rounded-lg text-xs">Read-Only</span>
            @endif
        </div>
    </form>
</x-layouts.app>
