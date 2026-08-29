<x-layouts.app>
    <x-slot name="title">Detail Surat Keluar - {{ $suratKeluar->nomor_surat }}</x-slot>
    <x-slot name="header">Detail Surat Keluar</x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Surat Keluar', 'url' => route('surat-keluar.index')],
        ['label' => 'Detail Agenda ' . $suratKeluar->no_agenda_formatted]
    ]" />

    <div class="bg-white border border-neutral-200/90 rounded-2xl p-6 sm:p-8 shadow-xs max-w-5xl">
        {{-- Header Status --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-neutral-200/80">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 bg-success-50 text-success-700 rounded-md text-xs font-extrabold border border-success-200/70">
                        No. Agenda {{ $suratKeluar->no_agenda_formatted }}
                    </span>
                    <span class="text-xs text-neutral-400">&bull; Tanggal Surat: {{ $suratKeluar->tanggal_surat?->translatedFormat('d F Y') }}</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-neutral-900 mt-1.5 tracking-tight">{{ $suratKeluar->nomor_surat }}</h2>
            </div>

            <div class="flex items-center gap-2.5">
                @if(auth()->check() && auth()->user()->hasPermission('surat_keluar', 'update'))
                    <a href="{{ route('surat-keluar.edit', $suratKeluar) }}" class="px-4 py-2 bg-warning-500 hover:bg-warning-600 text-white font-bold rounded-xl text-xs sm:text-sm shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Ubah Data</span>
                    </a>
                @endif
                <a href="{{ route('surat-keluar.index') }}" class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-bold rounded-xl text-xs sm:text-sm border border-neutral-300/80 transition">
                    &larr; Kembali
                </a>
            </div>
        </div>

        {{-- Main Metadata Grid --}}
        <div class="py-6 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 bg-neutral-50/70 p-5 rounded-xl border border-neutral-200/80">
                <div>
                    <p class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">Tujuan Surat</p>
                    <p class="text-sm sm:text-base font-bold text-neutral-900 mt-0.5">{{ $suratKeluar->tujuan_surat }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">Dikirim Oleh</p>
                    <p class="text-sm sm:text-base font-bold text-neutral-900 mt-0.5">{{ $suratKeluar->dikirimOleh?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">Tanggal Surat</p>
                    <p class="text-sm sm:text-base font-bold text-neutral-900 mt-0.5">{{ $suratKeluar->tanggal_surat?->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">Nomor Agenda</p>
                    <p class="text-sm sm:text-base font-bold text-neutral-900 mt-0.5">{{ $suratKeluar->no_agenda_formatted }}</p>
                </div>
            </div>

            {{-- Perihal --}}
            <div class="p-5 rounded-xl border border-neutral-200/80 bg-white">
                <p class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider mb-1.5">Perihal / Isi Ringkasan Surat</p>
                <div class="text-sm sm:text-base text-neutral-800 leading-relaxed whitespace-pre-line">
                    {{ $suratKeluar->perihal }}
                </div>
            </div>

            {{-- Dokumen Lampiran --}}
            <div class="p-5 rounded-xl border border-neutral-200/80 bg-white">
                <div class="mb-3.5">
                    <p class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">Lampiran Berkas Digital</p>
                    <p class="text-xs text-neutral-500 mt-0.5">Pilih format dan preferensi nama file saat mengunduh.</p>
                </div>

                @if($suratKeluar->hasFile())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        {{-- Kolom Kiri: Format PDF --}}
                        <div class="p-4 bg-neutral-50/70 border border-neutral-200/80 rounded-xl hover:border-danger-300 transition">
                            <div class="flex items-center gap-2.5 mb-3">
                                <div class="w-9 h-9 rounded-lg bg-danger-50 text-danger-600 flex items-center justify-center flex-shrink-0 border border-danger-100">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-neutral-900">Format Dokumen PDF</h4>
                                    <p class="text-[11px] text-neutral-500">Versi standar / siap cetak</p>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <a href="{{ route('surat-keluar.download', [$suratKeluar, 'type' => 'pdf', 'name' => 'agenda']) }}"
                                   class="flex items-center justify-between px-3 py-2 bg-white hover:bg-danger-50 text-xs font-semibold text-neutral-700 hover:text-danger-700 rounded-lg border border-neutral-200 transition">
                                    <span>📄 Nama: Surat_Keluar_No_{{ $suratKeluar->no_agenda ?? $suratKeluar->id }}_PDF</span>
                                    <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                </a>
                                @if($suratKeluar->original_file_name)
                                <a href="{{ route('surat-keluar.download', [$suratKeluar, 'type' => 'pdf', 'name' => 'original']) }}"
                                   class="flex items-center justify-between px-3 py-2 bg-white hover:bg-danger-50 text-xs font-semibold text-neutral-700 hover:text-danger-700 rounded-lg border border-neutral-200 transition">
                                    <span>📄 Nama: {{ pathinfo($suratKeluar->original_file_name, PATHINFO_FILENAME) }}.pdf</span>
                                    <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                </a>
                                @endif
                            </div>
                        </div>

                        {{-- Kolom Kanan: File Asli --}}
                        <div class="p-4 bg-neutral-50/70 border border-neutral-200/80 rounded-xl hover:border-primary-300 transition">
                            <div class="flex items-center gap-2.5 mb-3">
                                <div class="w-9 h-9 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center flex-shrink-0 border border-primary-100">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-neutral-900">File Sumber Asli</h4>
                                    <p class="text-[11px] text-neutral-500">Format .{{ $suratKeluar->original_extension ?: 'file' }}</p>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <a href="{{ route('surat-keluar.download', [$suratKeluar, 'type' => 'original', 'name' => 'agenda']) }}"
                                   class="flex items-center justify-between px-3 py-2 bg-white hover:bg-primary-50 text-xs font-semibold text-neutral-700 hover:text-primary-700 rounded-lg border border-neutral-200 transition">
                                    <span>📎 Nama: Surat_Keluar_No_{{ $suratKeluar->no_agenda ?? $suratKeluar->id }}_Asli</span>
                                    <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                </a>
                                @if($suratKeluar->original_file_name)
                                <a href="{{ route('surat-keluar.download', [$suratKeluar, 'type' => 'original', 'name' => 'original']) }}"
                                   class="flex items-center justify-between px-3 py-2 bg-white hover:bg-primary-50 text-xs font-semibold text-neutral-700 hover:text-primary-700 rounded-lg border border-neutral-200 transition">
                                    <span>📎 Nama: {{ $suratKeluar->original_file_name }}</span>
                                    <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-3.5 bg-neutral-50 rounded-lg border border-neutral-200 text-neutral-500 text-xs font-medium">
                        Tidak ada file digital yang dilampirkan pada surat ini.
                    </div>
                @endif
            </div>

        </div>

        {{-- Footer Timestamps --}}
        <div class="pt-4 border-t border-neutral-100 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-neutral-400 gap-2">
            <span>Direkam pada: {{ $suratKeluar->created_at?->translatedFormat('d F Y, H:i') }}</span>
            <span>Terakhir diperbarui: {{ $suratKeluar->updated_at?->translatedFormat('d F Y, H:i') }}</span>
        </div>
    </div>
</x-layouts.app>
