<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 — Sesi Kedaluwarsa | {{ config('app.name', 'SiARSIP') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/png">

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
    </style>
</head>
<body class="h-full bg-neutral-50 text-neutral-800 flex flex-col justify-between relative overflow-x-hidden antialiased select-none">
    <div class="absolute inset-0 bg-grid pointer-events-none z-0"></div>

    <header class="relative z-10 w-full px-6 py-5 sm:px-10 flex items-center justify-between border-b border-neutral-200/80 bg-white/80 backdrop-blur-md">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group transition">
            <div class="w-9 h-9 rounded-xl bg-warning-500 group-hover:bg-warning-600 flex items-center justify-center text-white shadow-xs transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="font-extrabold text-neutral-900 text-lg tracking-tight">SiARSIP</span>
        </a>
    </header>

    <main class="relative z-10 flex-1 flex items-center justify-center px-4 py-12 sm:px-6">
        <div class="max-w-xl w-full text-center">
            
            <div class="relative inline-block mb-6">
                <div class="w-28 h-28 sm:w-32 sm:h-32 mx-auto rounded-3xl bg-gradient-to-br from-warning-50 to-warning-100 border border-warning-200/80 flex items-center justify-center shadow-sm">
                    <svg class="w-14 h-14 sm:w-16 sm:h-16 text-warning-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="absolute -bottom-2 -right-2 px-3 py-1 bg-warning-600 text-white text-xs font-black rounded-full shadow-md">
                    SESI HABIS
                </div>
            </div>

            <h1 class="text-2xl sm:text-4xl font-extrabold text-neutral-900 tracking-tight mb-3">
                Sesi Anda Telah Berakhir (419)
            </h1>
            
            <p class="text-sm sm:text-base text-neutral-600 leading-relaxed max-w-md mx-auto mb-8 font-medium">
                Halaman ini sudah terlalu lama tidak aktif demi keamanan data arsip. Silakan muat ulang halaman atau masuk kembali.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <button
                    type="button"
                    onclick="window.location.reload()"
                    class="w-full sm:w-auto px-5 py-2.5 bg-white hover:bg-neutral-100 text-neutral-700 font-bold rounded-xl text-sm border border-neutral-300 shadow-xs transition flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Muat Ulang Halaman</span>
                </button>

                <a
                    href="{{ route('login') }}"
                    class="w-full sm:w-auto px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-sm shadow-xs transition flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>Masuk Kembali</span>
                </a>
            </div>

        </div>
    </main>

    <footer class="relative z-10 py-4 text-center text-xs text-neutral-400 border-t border-neutral-200/70 bg-white/50 backdrop-blur-sm">
        &copy; {{ date('Y') }} {{ config('app.name', 'Sistem Pengelolaan Arsip Surat Kedinasan') }}. All rights reserved.
    </footer>
</body>
</html>
