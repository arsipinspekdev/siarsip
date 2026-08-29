<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan | {{ config('app.name', 'SiARSIP') }}</title>
    
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

        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
        }

        .animate-float {
            animation: float-slow 5s ease-in-out infinite;
        }
    </style>
</head>
<body class="h-full bg-neutral-50 text-neutral-800 flex flex-col justify-between relative overflow-x-hidden antialiased select-none">
    {{-- Background Texture --}}
    <div class="absolute inset-0 bg-grid pointer-events-none z-0"></div>

    {{-- Top Simple Navbar --}}
    <header class="relative z-10 w-full px-6 py-5 sm:px-10 flex items-center justify-between border-b border-neutral-200/80 bg-white/80 backdrop-blur-md">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group transition">
            <div class="w-9 h-9 rounded-xl bg-primary-600 group-hover:bg-primary-700 flex items-center justify-center text-white shadow-xs transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
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
            
            {{-- Illustration / Big 404 Badge --}}
            <div class="relative inline-block mb-6 animate-float">
                <div class="w-28 h-28 sm:w-32 sm:sm:h-32 mx-auto rounded-3xl bg-gradient-to-br from-primary-50 to-primary-100 border border-primary-200/80 flex items-center justify-center shadow-sm">
                    <svg class="w-14 h-14 sm:w-16 sm:h-16 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="absolute -bottom-2 -right-2 px-3 py-1 bg-primary-600 text-white text-xs font-black rounded-full shadow-md">
                    ERROR 404
                </div>
            </div>

            {{-- Text Heading --}}
            <h1 class="text-2xl sm:text-4xl font-extrabold text-neutral-900 tracking-tight mb-3">
                Halaman Tidak Ditemukan
            </h1>
            
            <p class="text-sm sm:text-base text-neutral-600 leading-relaxed max-w-md mx-auto mb-8 font-medium">
                Maaf, tautan atau dokumen arsip yang Anda tuju tidak ditemukan, telah dipindahkan, atau alamat URL salah dimasukkan.
            </p>

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

            {{-- Helpful Search / Link Bar --}}
            <div class="mt-10 pt-8 border-t border-neutral-200/80 max-w-sm mx-auto text-center">
                <p class="text-xs text-neutral-500 font-medium mb-3">Tautan Cepat:</p>
                <div class="flex items-center justify-center gap-4 text-xs font-bold text-primary-600">
                    <a href="{{ route('surat-masuk.index') }}" class="hover:underline hover:text-primary-700">Surat Masuk</a>
                    <span class="text-neutral-300">&bull;</span>
                    <a href="{{ route('surat-keluar.index') }}" class="hover:underline hover:text-primary-700">Surat Keluar</a>
                    <span class="text-neutral-300">&bull;</span>
                    <a href="{{ route('profile.edit') }}" class="hover:underline hover:text-primary-700">Profil Saya</a>
                </div>
            </div>

        </div>
    </main>

    {{-- Minimal Footer --}}
    <footer class="relative z-10 py-4 text-center text-xs text-neutral-400 border-t border-neutral-200/70 bg-white/50 backdrop-blur-sm">
        &copy; {{ date('Y') }} {{ config('app.name', 'Sistem Pengelolaan Arsip Surat Kedinasan') }}. All rights reserved.
    </footer>
</body>
</html>
