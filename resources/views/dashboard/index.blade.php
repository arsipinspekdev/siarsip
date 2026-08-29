<x-layouts.app>
    <x-slot name="title">Beranda</x-slot>
    <x-slot name="header">Beranda Arsip</x-slot>

    {{-- Welcome Hero Card --}}
    <div class="bg-gradient-to-r from-primary-700 via-primary-600 to-primary-800 rounded-2xl p-6 text-white shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 bg-white/15 rounded-md text-xs font-bold uppercase tracking-wider">Dashboard Kedinasan</span>
                <span class="text-xs text-primary-200 font-medium">&bull; {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h2 class="text-2xl font-extrabold mt-1 tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h2>
            <p class="text-xs sm:text-sm text-primary-100 mt-1">
                Anda masuk sebagai <strong class="text-white">{{ auth()->user()->role?->name ?? 'Pengguna' }}</strong>. Pantau dan kelola arsip persuratan kedinasan secara terpadu.
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap flex-shrink-0">
            @if(auth()->check() && auth()->user()->hasPermission('surat_masuk', 'create'))
                <a href="{{ route('surat-masuk.create') }}" class="px-4 py-2 bg-white hover:bg-neutral-100 text-primary-800 font-bold rounded-xl text-xs sm:text-sm shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-primary-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Input Surat Masuk</span>
                </a>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('surat_keluar', 'create'))
                <a href="{{ route('surat-keluar.create') }}" class="px-4 py-2 bg-primary-800/80 hover:bg-primary-900 text-white font-bold rounded-xl text-xs sm:text-sm shadow-xs transition flex items-center gap-1.5 border border-primary-500/50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Input Surat Keluar</span>
                </a>
            @endif
        </div>
    </div>

    {{-- 3 Kartu Statistik Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
        {{-- Total Surat Masuk --}}
        <x-card-stat
            title="Total Surat Masuk"
            :value="$totalSuratMasuk"
            color="primary"
            :href="auth()->check() && auth()->user()->hasPermission('surat_masuk', 'view') ? route('surat-masuk.index') : null"
            icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>'
        />

        {{-- Total Surat Keluar --}}
        <x-card-stat
            title="Total Surat Keluar"
            :value="$totalSuratKeluar"
            color="success"
            :href="auth()->check() && auth()->user()->hasPermission('surat_keluar', 'view') ? route('surat-keluar.index') : null"
            icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>'
        />

        {{-- Total Pengguna --}}
        <x-card-stat
            title="Total Pengguna Sistem"
            :value="$totalUsers"
            color="warning"
            :href="auth()->check() && auth()->user()->hasPermission('users', 'view') ? route('users.index') : null"
            icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>'
        />
    </div>

    {{-- Grafik Statistik Bulanan --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Grafik Surat Masuk --}}
        <div class="bg-white border border-neutral-200/90 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3 border-b border-neutral-100 pb-3">
                <div>
                    <h3 class="text-base font-extrabold text-neutral-900 tracking-tight">Tren Surat Masuk</h3>
                    <p class="text-xs text-neutral-500">Volume arsip surat masuk per bulan</p>
                </div>
                <span class="w-3 h-3 rounded-full bg-primary-600"></span>
            </div>
            <div class="h-56 w-full relative">
                <canvas id="chartSuratMasuk"></canvas>
            </div>
        </div>

        {{-- Grafik Surat Keluar --}}
        <div class="bg-white border border-neutral-200/90 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3 border-b border-neutral-100 pb-3">
                <div>
                    <h3 class="text-base font-extrabold text-neutral-900 tracking-tight">Tren Surat Keluar</h3>
                    <p class="text-xs text-neutral-500">Volume arsip surat keluar per bulan</p>
                </div>
                <span class="w-3 h-3 rounded-full bg-success-600"></span>
            </div>
            <div class="h-56 w-full relative">
                <canvas id="chartSuratKeluar"></canvas>
            </div>
        </div>
    </div>

    {{-- Daftar Dokumen Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- 5 Surat Masuk Terbaru --}}
        <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs overflow-hidden flex flex-col">
            <div class="p-4 sm:p-5 border-b border-neutral-100 bg-neutral-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-neutral-900">Surat Masuk Terbaru</h3>
                    <p class="text-xs text-neutral-500">Arsip dokumen baru diterima</p>
                </div>
                <a href="{{ route('surat-masuk.index') }}" class="px-3 py-1.5 bg-white hover:bg-neutral-100 text-primary-700 font-bold rounded-lg text-xs border border-neutral-200 transition">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-neutral-100 flex-1">
                @forelse($latestSuratMasuk as $sm)
                    <div class="p-3.5 sm:p-4 hover:bg-neutral-50/70 transition flex items-center justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 bg-primary-50 text-primary-700 text-[11px] font-bold rounded border border-primary-100">
                                    {{ $sm->no_agenda_formatted }}
                                </span>
                                <span class="text-xs text-neutral-400">
                                    {{ $sm->tanggal_terima?->format('d/m/Y') }}
                                </span>
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-neutral-900 truncate">{{ $sm->nomor_surat }}</h4>
                            <p class="text-xs text-neutral-500 line-clamp-1 mt-0.5">
                                <span class="font-semibold text-neutral-700">Dari:</span> {{ $sm->asal_surat }} &bull; <span class="font-semibold text-neutral-700">Perihal:</span> {{ $sm->perihal }}
                            </p>
                        </div>
                        <a href="{{ route('surat-masuk.show', $sm) }}" class="px-2.5 py-1 bg-neutral-100 hover:bg-primary-50 text-neutral-700 hover:text-primary-700 font-bold rounded-lg text-xs flex items-center justify-center flex-shrink-0 transition">
                            Lihat
                        </a>
                    </div>
                @empty
                    <div class="p-6 text-center text-neutral-400 text-xs">
                        Belum ada data surat masuk.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 5 Surat Keluar Terbaru --}}
        <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs overflow-hidden flex flex-col">
            <div class="p-4 sm:p-5 border-b border-neutral-100 bg-neutral-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-neutral-900">Surat Keluar Terbaru</h3>
                    <p class="text-xs text-neutral-500">Arsip dokumen baru diterbitkan</p>
                </div>
                <a href="{{ route('surat-keluar.index') }}" class="px-3 py-1.5 bg-white hover:bg-neutral-100 text-success-700 font-bold rounded-lg text-xs border border-neutral-200 transition">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-neutral-100 flex-1">
                @forelse($latestSuratKeluar as $sk)
                    <div class="p-3.5 sm:p-4 hover:bg-neutral-50/70 transition flex items-center justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 bg-success-50 text-success-700 text-[11px] font-bold rounded border border-success-100">
                                    {{ $sk->no_agenda_formatted }}
                                </span>
                                <span class="text-xs text-neutral-400">
                                    {{ $sk->tanggal_surat?->format('d/m/Y') }}
                                </span>
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-neutral-900 truncate">{{ $sk->nomor_surat }}</h4>
                            <p class="text-xs text-neutral-500 line-clamp-1 mt-0.5">
                                <span class="font-semibold text-neutral-700">Tujuan:</span> {{ $sk->tujuan_surat }} &bull; <span class="font-semibold text-neutral-700">Perihal:</span> {{ $sk->perihal }}
                            </p>
                        </div>
                        <a href="{{ route('surat-keluar.show', $sk) }}" class="px-2.5 py-1 bg-neutral-100 hover:bg-primary-50 text-neutral-700 hover:text-primary-700 font-bold rounded-lg text-xs flex items-center justify-center flex-shrink-0 transition">
                            Lihat
                        </a>
                    </div>
                @empty
                    <div class="p-6 text-center text-neutral-400 text-xs">
                        Belum ada data surat keluar.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Pass chart data to JavaScript --}}
    <script>
        window.dashboardDataMasuk = @json($chartMasuk);
        window.dashboardDataKeluar = @json($chartKeluar);
    </script>
</x-layouts.app>
