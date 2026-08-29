<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name', 'SiARSIP') }}</title>
    <meta name="description" content="Sistem Pengelolaan Arsip Surat Kedinasan — Masuk untuk mengelola surat masuk dan keluar.">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Animasi partikel background */
        .bg-grid {
            background-image:
                linear-gradient(to right, rgba(255,255,255,0.06) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        @keyframes float-up {
            0%   { transform: translateY(0) scale(1); opacity: 0.7; }
            100% { transform: translateY(-120px) scale(0.4); opacity: 0; }
        }
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            animation: float-up 8s ease-in-out infinite alternate;
        }

        /* Input focus glow */
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        @keyframes slide-in {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-in {
            animation: slide-in 0.5s ease-out both;
        }
    </style>
</head>
<body class="h-full bg-white overflow-hidden">
    <div class="flex h-full min-h-screen">

        {{-- ====== SISI KIRI — Hero / Branding ====== --}}
        <div class="hidden lg:flex lg:w-[52%] xl:w-[55%] relative flex-col justify-between overflow-hidden"
             style="background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 45%, #2563eb 100%);">

            <!-- Grid texture -->
            <div class="absolute inset-0 bg-grid opacity-100"></div>

            <!-- Decorative blobs -->
            <div class="blob w-80 h-80 bg-blue-400/30 top-[-60px] left-[-60px]" style="animation-delay: 0s;"></div>
            <div class="blob w-64 h-64 bg-indigo-300/20 bottom-[10%] right-[5%]" style="animation-delay: 3s;"></div>
            <div class="blob w-48 h-48 bg-sky-300/20 bottom-[30%] left-[10%]" style="animation-delay: 5s;"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col h-full px-12 py-12 justify-between">
                <!-- Logo top -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center border border-white/20">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                        </svg>
                    </div>
                    <span class="text-white font-extrabold text-base tracking-tight">SiARSIP</span>
                </div>

                <!-- Main hero text -->
                <div class="py-16">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full mb-6">
                        <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                        <span class="text-white/90 text-xs font-semibold">Sistem Aktif & Aman</span>
                    </div>

                    <h1 class="text-4xl xl:text-5xl font-extrabold text-white leading-tight mb-4">
                        Kelola Arsip<br>
                        <span class="text-blue-200">Surat Kedinasan</span><br>
                        dengan Mudah
                    </h1>
                    <p class="text-blue-100/80 text-base leading-relaxed max-w-md">
                        Platform pengelolaan surat masuk dan keluar yang terintegrasi, aman, dan mudah digunakan untuk instansi pemerintah.
                    </p>

                    <!-- Feature pills -->
                    <div class="flex flex-wrap gap-2.5 mt-8">
                        @foreach(['Surat Masuk & Keluar', 'Ekspor PDF / Excel', 'Cetak Rekap', 'Arsip Digital'] as $feat)
                            <span class="px-3 py-1.5 bg-white/10 border border-white/20 rounded-full text-white/90 text-xs font-semibold backdrop-blur-sm">
                                ✓ {{ $feat }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Stats row -->
                <div class="grid grid-cols-3 gap-4">
                    @foreach([['Surat Tercatat', 'Seluruh arsip terkelola'], ['100% Digital', 'Tanpa kertas berserakan'], ['Akses Mudah', 'Kapan saja, di mana saja']] as $stat)
                        <div class="p-4 bg-white/8 backdrop-blur-sm border border-white/15 rounded-2xl">
                            <p class="text-white font-extrabold text-sm">{{ $stat[0] }}</p>
                            <p class="text-blue-200/70 text-xs mt-0.5 leading-snug">{{ $stat[1] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ====== SISI KANAN — Form Login ====== --}}
        <div class="flex-1 flex flex-col justify-center items-center px-6 sm:px-12 lg:px-16 xl:px-24 py-12 bg-white overflow-y-auto">
            <div class="w-full max-w-md animate-slide-in">

                {{-- Mobile logo --}}
                <div class="lg:hidden flex items-center gap-2.5 mb-8">
                    <div class="w-9 h-9 rounded-xl bg-primary-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                        </svg>
                    </div>
                    <span class="font-extrabold text-neutral-900 text-base">SiARSIP</span>
                </div>

                {{-- Header --}}
                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-neutral-900 tracking-tight">Selamat Datang</h2>
                    <p class="text-neutral-500 text-sm mt-1.5">Masuk untuk mengakses sistem pengelolaan arsip.</p>
                </div>

                {{-- Flash alerts --}}
                @if(session('status'))
                    <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-success-50 border border-success-200 rounded-xl text-success-800 text-sm font-semibold">
                        <svg class="w-4 h-4 flex-shrink-0 text-success-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('status') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-danger-50 border border-danger-200 rounded-xl text-danger-800 text-sm font-semibold">
                        <svg class="w-4 h-4 flex-shrink-0 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- General error from validation --}}
                @if($errors->has('login') || $errors->has('email'))
                    <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-danger-50 border border-danger-200 rounded-xl text-danger-800 text-sm font-semibold">
                        <svg class="w-4 h-4 flex-shrink-0 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $errors->first('login') ?: $errors->first('email') }}
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Username / Email --}}
                    <div>
                        <label for="login" class="block text-xs font-bold text-neutral-600 uppercase tracking-wider mb-1.5">
                            Nama Pengguna atau Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="login"
                                name="login"
                                value="{{ old('login') }}"
                                required
                                autocomplete="username"
                                placeholder="Masukkan username atau email"
                                class="input-field w-full pl-10 pr-4 py-2.5 text-sm text-neutral-900 bg-neutral-50 border rounded-xl transition duration-150 focus:outline-none focus:bg-white placeholder:text-neutral-400 {{ $errors->has('login') ? 'border-danger-400 focus:border-danger-500' : 'border-neutral-200 focus:border-primary-500' }}"
                            />
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-xs font-bold text-neutral-600 uppercase tracking-wider mb-1.5">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi"
                                class="input-field w-full pl-10 pr-12 py-2.5 text-sm text-neutral-900 bg-neutral-50 border rounded-xl transition duration-150 focus:outline-none focus:bg-white placeholder:text-neutral-400 {{ $errors->has('password') ? 'border-danger-400 focus:border-danger-500' : 'border-neutral-200 focus:border-primary-500' }}"
                            />
                            <button
                                type="button"
                                data-password-toggle
                                data-target="password"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-neutral-400 hover:text-neutral-700 focus:outline-none transition"
                                aria-label="Tampilkan Kata Sandi"
                            >
                                <svg data-icon-show class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg data-icon-hide class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs font-semibold text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                name="remember"
                                id="remember"
                                class="w-4 h-4 text-primary-600 rounded border-neutral-300 focus:ring-primary-500 cursor-pointer"
                            />
                            <span class="text-sm text-neutral-600 font-medium">Ingat saya</span>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white font-bold rounded-xl text-sm shadow-sm transition duration-150 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Masuk ke Sistem
                    </button>
                </form>

                {{-- Footer --}}
                <p class="text-center text-xs text-neutral-400 mt-8">
                    &copy; {{ date('Y') }} Sistem Pengelolaan Arsip Surat Kedinasan.<br>Hak cipta dilindungi undang-undang.
                </p>
            </div>
        </div>

    </div>
</body>
</html>
