<!DOCTYPE html>
<html lang="id" class="h-full bg-neutral-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' : '' }}{{ config('app.name', 'Arsip Surat') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Chart.js CDN for dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full text-neutral-800 bg-neutral-50 flex flex-col font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        {{-- ==================== SIDEBAR DESKTOP ==================== --}}
        <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-white border-r border-neutral-200/90 flex-shrink-0 z-20">
            {{-- Logo / Header Instansi --}}
            <div class="p-5 border-b border-neutral-200/80 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-600 to-primary-700 text-white flex items-center justify-center font-bold text-xl shadow-xs flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-neutral-900 tracking-tight leading-tight">ARSIP SURAT</h2>
                    <p class="text-xs font-semibold text-neutral-500">Sistem Dokumen Dinas</p>
                </div>
            </div>

            {{-- Navigation Links --}}
            <nav class="flex-1 px-3.5 py-4 overflow-y-auto space-y-1" aria-label="Menu Utama">
                @php
                    $navItems = [
                        [
                            'label' => 'Beranda',
                            'url' => route('dashboard'),
                            'active' => request()->routeIs('dashboard'),
                            'icon' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                            'show' => true,
                        ],
                        [
                            'label' => 'Surat Masuk',
                            'url' => route('surat-masuk.index'),
                            'active' => request()->routeIs('surat-masuk.*'),
                            'icon' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>',
                            'show' => auth()->check() && auth()->user()->hasPermission('surat_masuk', 'view'),
                        ],
                        [
                            'label' => 'Surat Keluar',
                            'url' => route('surat-keluar.index'),
                            'active' => request()->routeIs('surat-keluar.*'),
                            'icon' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>',
                            'show' => auth()->check() && auth()->user()->hasPermission('surat_keluar', 'view'),
                        ],
                    ];
                @endphp

                <p class="px-3 text-[11px] font-bold text-neutral-400 uppercase tracking-wider mb-1.5 mt-1">MENU UTAMA</p>

                @foreach($navItems as $item)
                    @if($item['show'])
                        <a
                            href="{{ $item['url'] }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150 {{ $item['active'] ? 'bg-primary-50 text-primary-700 font-bold shadow-xs' : 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900' }}"
                        >
                            <span class="{{ $item['active'] ? 'text-primary-600' : 'text-neutral-400' }}">
                                {!! $item['icon'] !!}
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach

                {{-- Menu Khusus Manajemen / Pengaturan Sistem --}}
                @php
                    $canViewUsers = auth()->check() && auth()->user()->hasPermission('users', 'view');
                    $canViewRoles = auth()->check() && auth()->user()->hasPermission('roles', 'view');
                    $canViewPermissions = auth()->check() && auth()->user()->hasPermission('permissions', 'view');
                    $hasAdminSection = $canViewUsers || $canViewRoles || $canViewPermissions;
                @endphp

                @if($hasAdminSection)
                    <div class="pt-4 mt-4 border-t border-neutral-200/80">
                        <p class="px-3 text-[11px] font-bold text-neutral-400 uppercase tracking-wider mb-1.5">PENGATURAN SISTEM</p>
                        
                        @if($canViewUsers)
                            <a
                                href="{{ route('users.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150 {{ request()->routeIs('users.*') ? 'bg-primary-50 text-primary-700 font-bold shadow-xs' : 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900' }}"
                            >
                                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('users.*') ? 'text-primary-600' : 'text-neutral-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Kelola Pengguna</span>
                            </a>
                        @endif

                        @if($canViewRoles)
                            <a
                                href="{{ route('roles.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150 {{ request()->routeIs('roles.*') ? 'bg-primary-50 text-primary-700 font-bold shadow-xs' : 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900' }}"
                            >
                                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('roles.*') ? 'text-primary-600' : 'text-neutral-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Wewenang (Role)</span>
                            </a>
                        @endif

                        @if($canViewPermissions)
                            <a
                                href="{{ route('permissions.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150 {{ request()->routeIs('permissions.*') ? 'bg-primary-50 text-primary-700 font-bold shadow-xs' : 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900' }}"
                            >
                                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('permissions.*') ? 'text-primary-600' : 'text-neutral-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <span>Matrix Hak Akses</span>
                            </a>
                        @endif
                    </div>
                @endif
            </nav>

            {{-- User Quick Profile in Sidebar Footer --}}
            <div class="p-3 border-t border-neutral-200/80 bg-neutral-50/50">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-neutral-100 transition group">
                    <img src="{{ auth()->user()?->avatar_url }}" alt="Avatar" class="w-9 h-9 rounded-lg object-cover border border-neutral-300 flex-shrink-0" />
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-neutral-900 truncate group-hover:text-primary-600">{{ auth()->user()?->name }}</p>
                        <p class="text-[11px] font-medium text-neutral-500 truncate">{{ auth()->user()?->role?->name ?? 'Pengguna' }}</p>
                    </div>
                </a>
            </div>
        </aside>

        {{-- ==================== MAIN CONTENT WRAPPER ==================== --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            {{-- Top Navbar --}}
            <header class="bg-white border-b border-neutral-200/90 flex-shrink-0 z-10">
                <div class="px-5 sm:px-8 py-3.5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button id="open-sidebar-btn" type="button" aria-label="Buka Menu" class="lg:hidden p-1.5 text-neutral-600 hover:bg-neutral-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        <div>
                            <h1 class="text-xl sm:text-2xl font-extrabold text-neutral-900 tracking-tight leading-tight">
                                {{ $header ?? $title ?? 'Sistem Arsip Dokumen' }}
                            </h1>
                        </div>
                    </div>

                    {{-- User Dropdown --}}
                    <div class="relative">
                        <button
                            id="user-menu-btn"
                            type="button"
                            aria-expanded="false"
                            aria-haspopup="true"
                            class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg border border-neutral-200 hover:border-neutral-300 hover:bg-neutral-50 transition"
                        >
                            <img src="{{ auth()->user()?->avatar_url }}" alt="Foto Profil" class="w-7 h-7 rounded-md object-cover border border-neutral-300 flex-shrink-0" />
                            <span class="hidden sm:inline text-xs font-bold text-neutral-800">{{ auth()->user()?->name }}</span>
                            <svg class="w-4 h-4 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div
                            id="user-menu-dropdown"
                            class="hidden absolute right-0 mt-2 w-56 bg-white border border-neutral-200 rounded-xl shadow-lg py-1.5 z-50"
                        >
                            <div class="px-4 py-2.5 border-b border-neutral-100">
                                <p class="text-xs font-semibold text-neutral-400">Masuk sebagai:</p>
                                <p class="text-xs font-bold text-neutral-900 truncate">{{ auth()->user()?->email }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900 transition">
                                <svg class="w-4 h-4 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Profil Saya &amp; Sandi</span>
                            </a>

                            <div class="border-t border-neutral-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-danger-700 hover:bg-danger-50 transition text-left">
                                    <svg class="w-4 h-4 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Main Page Body --}}
            <main class="flex-1 overflow-y-auto p-5 sm:p-7 lg:p-8 bg-neutral-50/60">
                <div class="max-w-7xl mx-auto space-y-6">
                    {{-- Global Flash Alerts --}}
                    @if(session('success'))
                        <x-alert type="success" :message="session('success')" />
                    @endif

                    @if(session('error'))
                        <x-alert type="danger" :message="session('error')" />
                    @endif

                    @if(session('warning'))
                        <x-alert type="warning" :message="session('warning')" />
                    @endif

                    @if(session('info'))
                        <x-alert type="info" :message="session('info')" />
                    @endif

                    {{-- Page Content Slot --}}
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    {{-- Global Custom Delete Confirmation Modal --}}
    <x-confirm-modal />
</body>
</html>
