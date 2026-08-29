<x-layouts.app>
    <x-slot name="title">Daftar Surat Keluar</x-slot>
    <x-slot name="header">Surat Keluar</x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Surat Keluar']
    ]" />

    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs">

        {{-- ============ TOP TOOLBAR ============ --}}
        <div class="p-4 sm:p-5 border-b border-neutral-200/80 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3.5">
            {{-- Search --}}
            <form method="GET" action="{{ route('surat-keluar.index') }}" class="flex-1 flex items-center gap-2">
                <div class="relative flex-1 max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari nomor, tujuan, perihal..."
                        class="w-full pl-10 pr-3.5 py-2 text-sm text-neutral-900 bg-neutral-50/80 border border-neutral-300/80 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition focus:outline-none placeholder:text-neutral-400"
                    />
                </div>
                <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-sm transition">
                    Cari
                </button>
                @if(request('q'))
                    <a href="{{ route('surat-keluar.index') }}" class="px-3.5 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-600 font-semibold rounded-xl text-sm border border-neutral-200 transition">
                        Reset
                    </a>
                @endif
            </form>

            {{-- Ekspor & Tambah --}}
            <div class="flex items-center gap-2.5 flex-wrap">
                @if(auth()->check() && auth()->user()->hasPermission('surat_keluar', 'export'))
                    {{-- Ekspor dropdown --}}
                    <div class="relative">
                        <button
                            data-dropdown-btn
                            type="button"
                            aria-expanded="false"
                            class="inline-flex items-center gap-2 px-3.5 py-2 bg-neutral-50 hover:bg-neutral-100 text-neutral-700 font-semibold rounded-xl text-sm border border-neutral-300/80 transition select-none"
                        >
                            <svg class="w-4 h-4 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Ekspor &amp; Rekap</span>
                            <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div data-dropdown-menu class="hidden absolute right-0 mt-2 w-64 bg-white border border-neutral-200 rounded-xl shadow-2xl py-1.5 z-50 text-left">
                            <div class="px-3.5 py-2 border-b border-neutral-100">
                                <p class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">EKSPOR SEMUA DATA</p>
                            </div>
                            <a href="{{ route('surat-keluar.export.pdf', request()->query()) }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-neutral-700 hover:bg-danger-50 hover:text-danger-700 transition">
                                <svg class="w-4 h-4 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                Ekspor PDF (.pdf)
                            </a>
                            <a href="{{ route('surat-keluar.export.excel', request()->query()) }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-neutral-700 hover:bg-success-50 hover:text-success-700 transition">
                                <svg class="w-4 h-4 text-success-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Ekspor Excel (.xlsx)
                            </a>
                            <a href="{{ route('surat-keluar.export.csv', request()->query()) }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-neutral-700 hover:bg-primary-50 hover:text-primary-700 transition">
                                <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7C5 4 4 5 4 7z" /></svg>
                                Ekspor CSV (.csv)
                            </a>
                            <div class="border-t border-neutral-100 my-1"></div>
                            <a href="{{ route('surat-keluar.print', request()->query()) }}" target="_blank" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-neutral-700 hover:bg-neutral-100 transition">
                                <svg class="w-4 h-4 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                Cetak Lembar Rekap
                            </a>
                        </div>
                    </div>
                @endif

                @if(auth()->check() && auth()->user()->hasPermission('surat_keluar', 'create'))
                    <a href="{{ route('surat-keluar.create') }}" class="px-4 py-2 bg-success-600 hover:bg-success-700 text-white font-bold rounded-xl text-sm shadow-xs transition flex items-center gap-1.5 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        + Tambah Surat
                    </a>
                @endif
            </div>
        </div>

        {{-- Bulk Action Bar --}}
        @if(auth()->check() && auth()->user()->hasPermission('surat_keluar', 'delete'))
            <div id="bulk-action-container" class="hidden px-5 py-3 bg-danger-50/80 border-b border-danger-200 flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-danger-900 font-bold text-xs sm:text-sm">
                    <svg class="w-5 h-5 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><span id="selected-count">0</span> data surat dipilih</span>
                </div>
                <form method="POST" action="{{ route('surat-keluar.bulk-destroy') }}" id="bulk-delete-form" data-confirm-delete data-confirm-message="Hapus semua surat keluar yang dipilih?">
                    @csrf
                    @method('DELETE')
                    <div id="bulk-hidden-inputs"></div>
                    <button type="submit" class="px-3.5 py-1.5 bg-danger-600 hover:bg-danger-700 text-white font-bold rounded-lg text-xs shadow-xs transition">
                        Hapus Terpilih
                    </button>
                </form>
            </div>
        @endif

        {{-- ============ TABLE ============ --}}
        <div class="overflow-x-auto {{ $suratKeluar->count() > 10 ? 'max-h-[640px] overflow-y-auto' : '' }}">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead class="bg-neutral-50/80 border-b border-neutral-200 text-neutral-500 text-xs font-bold uppercase tracking-wider sticky top-0 z-10">
                    <tr>
                        @if(auth()->check() && auth()->user()->hasPermission('surat_keluar', 'delete'))
                            <th class="py-3 px-4 w-10 text-center bg-neutral-50/80">
                                <input type="checkbox" id="select-all" class="w-4 h-4 text-primary-600 rounded border-neutral-300 focus:ring-primary-500 cursor-pointer" title="Pilih Semua" />
                            </th>
                        @endif
                        <th class="py-3 px-4 whitespace-nowrap bg-neutral-50/80">No. Agenda</th>
                        <th class="py-3 px-4 bg-neutral-50/80">Nomor Surat</th>
                        <th class="py-3 px-4 whitespace-nowrap bg-neutral-50/80">Tgl. Surat</th>
                        <th class="py-3 px-4 bg-neutral-50/80">Tujuan</th>
                        <th class="py-3 px-4 bg-neutral-50/80">Perihal</th>
                        <th class="py-3 px-4 text-center whitespace-nowrap bg-neutral-50/80">Lampiran</th>
                        <th class="py-3 px-4 text-center bg-neutral-50/80">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 text-sm">
                    @forelse($suratKeluar as $sk)
                        <tr class="hover:bg-neutral-50/60 transition-colors">
                            @if(auth()->check() && auth()->user()->hasPermission('surat_keluar', 'delete'))
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $sk->id }}" class="row-checkbox w-4 h-4 text-primary-600 rounded border-neutral-300 focus:ring-primary-500 cursor-pointer" />
                                </td>
                            @endif
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 bg-success-50 text-success-700 font-extrabold text-xs rounded-md border border-success-200/60">
                                    {{ $sk->no_agenda_formatted }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold text-neutral-900 max-w-[180px]">
                                <span class="block truncate" title="{{ $sk->nomor_surat }}">{{ $sk->nomor_surat }}</span>
                            </td>
                            <td class="py-3 px-4 font-semibold text-neutral-800 whitespace-nowrap text-xs">
                                {{ $sk->tanggal_surat?->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4 text-neutral-700 font-medium max-w-[160px]">
                                <span class="block truncate" title="{{ $sk->tujuan_surat }}">{{ $sk->tujuan_surat }}</span>
                            </td>
                            <td class="py-3 px-4 text-neutral-600 max-w-[220px]">
                                <p class="line-clamp-2 text-xs leading-relaxed">{{ $sk->perihal }}</p>
                            </td>

                            {{-- Lampiran: 2 link langsung —  PDF dan Asli --}}
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                @if($sk->hasFile())
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('surat-keluar.download', [$sk, 'type' => 'pdf']) }}"
                                           title="Unduh versi PDF"
                                           class="inline-flex items-center gap-1 px-2 py-1 bg-danger-50 hover:bg-danger-100 text-danger-700 border border-danger-200/70 rounded-md text-[11px] font-bold transition">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            PDF
                                        </a>
                                        <a href="{{ route('surat-keluar.download', [$sk, 'type' => 'original']) }}"
                                           title="Unduh file asli (.{{ $sk->original_extension ?: 'file' }})"
                                           class="inline-flex items-center gap-1 px-2 py-1 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 border border-neutral-200 rounded-md text-[11px] font-bold transition">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Asli
                                        </a>
                                    </div>
                                @else
                                    <span class="text-xs text-neutral-300">—</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('surat-keluar.show', $sk) }}" class="px-2.5 py-1 bg-neutral-100 hover:bg-primary-50 hover:text-primary-700 text-neutral-700 font-bold rounded-lg text-xs transition">
                                        Lihat
                                    </a>
                                    @if(auth()->check() && auth()->user()->hasPermission('surat_keluar', 'update'))
                                        <a href="{{ route('surat-keluar.edit', $sk) }}" class="px-2.5 py-1 bg-neutral-100 hover:bg-warning-50 hover:text-warning-700 text-neutral-700 font-bold rounded-lg text-xs transition">
                                            Ubah
                                        </a>
                                    @endif
                                    @if(auth()->check() && auth()->user()->hasPermission('surat_keluar', 'delete'))
                                        <form method="POST" action="{{ route('surat-keluar.destroy', $sk) }}" data-confirm-delete data-confirm-message="Hapus Surat Keluar nomor {{ $sk->nomor_surat }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 bg-neutral-100 hover:bg-danger-50 hover:text-danger-700 text-neutral-700 font-bold rounded-lg text-xs transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-0">
                                <x-empty-state
                                    title="Data Surat Keluar Tidak Ditemukan"
                                    message="{{ request('q') ? 'Tidak ditemukan data dengan kata kunci tersebut.' : 'Belum ada data surat keluar yang tersimpan.' }}"
                                    action-label="+ Tambah Surat Keluar"
                                    :action-url="auth()->check() && auth()->user()->hasPermission('surat_keluar', 'create') ? route('surat-keluar.create') : ''"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($suratKeluar->hasPages())
            <div class="px-5 py-4 border-t border-neutral-200 bg-neutral-50/50 rounded-b-2xl">
                {{ $suratKeluar->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</x-layouts.app>
