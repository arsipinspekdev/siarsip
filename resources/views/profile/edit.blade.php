<x-layouts.app>
    <x-slot name="title">Profil Akun Saya</x-slot>
    <x-slot name="header">Profil &amp; Pengaturan Akun</x-slot>

    <x-breadcrumb :items="[['label' => 'Profil Akun Saya']]" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== PANEL KIRI: Kartu Profil ===== --}}
        <div class="lg:col-span-1">
            <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs overflow-hidden">
                {{-- Cover gradient --}}
                <div class="h-20 bg-gradient-to-br from-primary-600 to-primary-700 relative">
                    <div class="absolute inset-0 opacity-10"
                         style="background-image: repeating-linear-gradient(45deg, white 0, white 1px, transparent 0, transparent 50%); background-size: 12px 12px;"></div>
                </div>

                <div class="px-5 pb-5 -mt-10">
                    <div class="relative inline-block mb-3">
                        <img
                            src="{{ $user->avatar_url }}"
                            alt="{{ $user->name }}"
                            class="w-20 h-20 rounded-xl object-cover border-4 border-white shadow-sm"
                            id="avatar-preview"
                        />
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-primary-600 rounded-lg flex items-center justify-center shadow-sm border-2 border-white">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-sm font-extrabold text-neutral-900">{{ $user->name }}</h3>
                    <p class="text-xs text-neutral-500 mt-0.5">@{{ $user->username }}</p>
                    <span class="mt-2 inline-block px-2.5 py-0.5 bg-primary-50 text-primary-700 font-bold text-xs rounded-lg border border-primary-100">
                        {{ $user->role?->name ?? 'Pengguna' }}
                    </span>

                    <div class="mt-4 pt-4 border-t border-neutral-100 space-y-2.5">
                        <div class="flex items-center gap-2 text-xs text-neutral-600">
                            <svg class="w-4 h-4 text-neutral-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="font-semibold truncate">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-neutral-600">
                            <svg class="w-4 h-4 text-neutral-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-semibold">Bergabung: {{ $user->created_at?->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== PANEL KANAN: Form Edit ===== --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Edit Profil --}}
            <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs p-5 sm:p-6">
                <div class="mb-5 pb-4 border-b border-neutral-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center border border-primary-100">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-neutral-900">Perbarui Informasi Profil</h3>
                        <p class="text-xs text-neutral-500">Ubah nama, email, atau foto profil.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-form-input name="name" label="Nama Lengkap" :required="true" :value="$user->name" placeholder="Nama lengkap Anda" />
                        <x-form-input name="email" label="Alamat Email" type="email" :required="true" :value="$user->email" placeholder="email@instansi.go.id" />
                    </div>

                    {{-- Foto Profil --}}
                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1.5 uppercase tracking-wider">
                            Foto Profil <span class="text-neutral-400 font-normal normal-case tracking-normal">(opsional, maks. 2MB)</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <img
                                src="{{ $user->avatar_url }}"
                                alt="Pratinjau"
                                class="w-12 h-12 rounded-xl object-cover border border-neutral-200 flex-shrink-0"
                                id="profile-photo-preview"
                            />
                            <input
                                type="file"
                                name="photo"
                                id="photo"
                                accept="image/jpg,image/jpeg,image/png"
                                class="flex-1 px-3 py-2 text-xs border border-neutral-200/90 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none file:mr-2 file:py-1 file:px-2.5 file:border-0 file:bg-neutral-100 file:text-neutral-700 file:font-bold file:text-xs file:rounded-lg file:cursor-pointer cursor-pointer transition bg-neutral-50/80"
                                onchange="document.getElementById('profile-photo-preview').src = URL.createObjectURL(this.files[0])"
                            />
                        </div>
                        @error('photo')
                            <p class="mt-1.5 text-xs font-bold text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-3 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-xs shadow-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Ganti Kata Sandi --}}
            <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs p-5 sm:p-6">
                <div class="mb-5 pb-4 border-b border-neutral-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center border border-warning-100">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-neutral-900">Ganti Kata Sandi</h3>
                        <p class="text-xs text-neutral-500">Anda akan diminta masuk kembali setelah mengganti sandi.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Sandi Saat Ini --}}
                    <div>
                        <label for="current_password" class="block text-xs font-bold text-neutral-700 mb-1.5 uppercase tracking-wider">
                            Kata Sandi Saat Ini <span class="text-danger-600">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="current_password"
                                name="current_password"
                                required
                                placeholder="Kata sandi yang sedang digunakan"
                                class="w-full px-3.5 py-2.5 pr-10 text-sm text-neutral-900 bg-neutral-50/80 border rounded-xl transition duration-150 focus:outline-none focus:bg-white placeholder:text-neutral-400 {{ $errors->has('current_password') ? 'border-danger-500 focus:border-danger-500 focus:ring-2 focus:ring-danger-100' : 'border-neutral-300/80 focus:border-primary-500 focus:ring-2 focus:ring-primary-100' }}"
                            />
                            <button type="button" data-password-toggle data-target="current_password" class="absolute inset-y-0 right-0 pr-3 flex items-center text-neutral-400 hover:text-neutral-700 focus:outline-none transition">
                                <svg data-icon-show class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg data-icon-hide class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-1.5 text-xs font-bold text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-form-input name="password" label="Kata Sandi Baru" type="password" :required="true" placeholder="Minimal 6 karakter" />
                        <x-form-input name="password_confirmation" label="Konfirmasi Kata Sandi" type="password" :required="true" placeholder="Ulangi kata sandi baru" />
                    </div>

                    <div class="pt-3 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-warning-600 hover:bg-warning-700 text-white font-bold rounded-xl text-xs shadow-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Ganti Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-layouts.app>
