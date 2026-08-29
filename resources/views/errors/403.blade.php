<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak | {{ config('app.name', 'SiARSIP') }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .bg-grid {
            background-image:
                linear-gradient(to right, rgba(0, 0, 0, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 0, 0, 0.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        @keyframes pulse-subtle {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.03); }
        }

        .animate-pulse-badge {
            animation: pulse-subtle 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="h-full bg-neutral-50 text-neutral-800 flex flex-col justify-between relative overflow-x-hidden antialiased select-none">
    {{-- Background Texture --}}
    <div class="absolute inset-0 bg-grid pointer-events-none z-0"></div>

    {{-- Top Simple Navbar --}}
    <header class="relative z-10 w-full px-6 py-5 sm:px-10 flex items-center justify-between border-b border-neutral-200/80 bg-white/80 backdrop-blur-md">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group transition">
            <div class="w-9 h-9 rounded-xl bg-danger-600 group-hover:bg-danger-700 flex items-center justify-center text-white shadow-xs transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <span class="font-extrabold text-neutral-900 text-lg tracking-tight">SiARSIP</span>
        </a>

        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-bold rounded-xl text-xs sm:text-sm border border-neutral-200 transition">
                    Ke Beranda &rarr;
                </a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-xs sm:text-sm shadow-xs transition">
                    Masuk ke Sistem &rarr;
                </a>
            @endauth
        </div>
    </header>

    {{-- Main Content Container --}}
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 py-12 sm:px-6">
        <div class="max-w-xl w-full text-center">
            
            {{-- Illustration / Lock Badge --}}
            <div class="relative inline-block mb-6 animate-pulse-badge">
                <div class="w-28 h-28 sm:w-32 sm:h-32 mx-auto rounded-3xl bg-gradient-to-br from-danger-50 to-danger-100 border border-danger-200/80 flex items-center justify-center shadow-sm">
                    <svg class="w-14 h-14 sm:w-16 sm:h-16 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div class="absolute -bottom-2 -right-2 px-3 py-1 bg-danger-600 text-white text-xs font-black rounded-full shadow-md">
                    AKSES DITOLAK
                </div>
            </div>

            {{-- Text Heading --}}
            <h1 class="text-2xl sm:text-4xl font-extrabold text-neutral-900 tracking-tight mb-3">
                Tidak Memiliki Hak Akses (403)
            </h1>
            
            {{-- Message Description --}}
            <div class="bg-white border border-neutral-200/90 rounded-2xl p-4 sm:p-5 shadow-xs max-w-lg mx-auto mb-8 text-left">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-danger-50 text-danger-600 flex items-center justify-center flex-shrink-0 mt-0.5 border border-danger-100">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-neutral-900">Pemberitahuan Sistem Keamanan:</p>
                        <p class="text-xs sm:text-sm text-neutral-600 mt-1 leading-relaxed">
                            {{ !empty($exception->getMessage()) ? $exception->getMessage() : 'Akun Anda saat ini tidak memiliki izin (permission) yang diperlukan untuk membuka halaman atau melakukan tindakan ini.' }}
                        </p>
                        @auth
                            <p class="text-[11px] text-neutral-400 mt-2">
                                Wewenang Anda saat ini: <strong class="text-neutral-700">{{ auth()->user()->role?->name ?? 'Tanpa Role' }}</strong>
                            </p>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <button
                    type="button"
                    onclick="window.history.back()"
                    class="w-full sm:w-auto px-5 py-2.5 bg-white hover:bg-neutral-100 text-neutral-700 font-bold rounded-xl text-sm border border-neutral-300 shadow-xs transition flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Halaman Sebelumnya</span>
                </button>

                <a
                    href="{{ route('dashboard') }}"
                    class="w-full sm:w-auto px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-sm shadow-xs transition flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>

            {{-- Support note --}}
            <p class="mt-8 text-xs text-neutral-400 max-w-sm mx-auto">
                Butuh akses ke modul ini? Silakan hubungi <strong>Administrator</strong> untuk memperbarui izin wewenang Anda di Matrix Hak Akses.
            </p>

        </div>
    </main>

    {{-- Minimal Footer --}}
    <footer class="relative z-10 py-4 text-center text-xs text-neutral-400 border-t border-neutral-200/70 bg-white/50 backdrop-blur-sm">
        &copy; {{ date('Y') }} {{ config('app.name', 'Sistem Pengelolaan Arsip Surat Kedinasan') }}. All rights reserved.
    </footer>
</body>
</html>
