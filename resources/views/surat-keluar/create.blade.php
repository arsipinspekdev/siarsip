<x-layouts.app>
    <x-slot name="title">Tambah Surat Keluar</x-slot>
    <x-slot name="header">Input Surat Keluar Baru</x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Surat Keluar', 'url' => route('surat-keluar.index')],
        ['label' => 'Tambah Surat Keluar Baru']
    ]" />

    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs max-w-4xl overflow-hidden">
        {{-- Form Header --}}
        <div class="px-6 sm:px-8 py-5 border-b border-neutral-200/80 bg-neutral-50/50">
            <h2 class="text-base font-extrabold text-neutral-900 tracking-tight">Formulir Perekaman Surat Keluar</h2>
            <p class="text-xs text-neutral-500 mt-0.5">Lengkapi data surat yang akan diterbitkan atau dikirimkan ke pihak luar.</p>
        </div>

        <form method="POST" action="{{ route('surat-keluar.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="px-6 sm:px-8 py-6 space-y-5">
                {{-- Nomor Surat & Tujuan Surat --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form-input
                        name="nomor_surat"
                        label="Nomor Surat Keluar"
                        :required="true"
                        placeholder="Contoh: 005/310/ARSIP/2026"
                        helper="Nomor registrasi resmi yang diterbitkan untuk surat ini"
                    />
                    <x-form-input
                        name="tujuan_surat"
                        label="Tujuan Surat (Instansi / Penerima)"
                        :required="true"
                        placeholder="Contoh: Kepala Dinas Komunikasi dan Informatika"
                        helper="Nama instansi, dinas, atau pihak tujuan pengiriman"
                    />
                </div>

                {{-- Tanggal Surat --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form-input
                        name="tanggal_surat"
                        label="Tanggal Surat Diterbitkan"
                        type="date"
                        :required="true"
                        :value="date('Y-m-d')"
                        helper="Tanggal yang tercantum pada surat keluar"
                    />
                </div>

                {{-- Perihal --}}
                <x-form-textarea
                    name="perihal"
                    label="Perihal / Isi Ringkas Surat"
                    :required="true"
                    :rows="4"
                    placeholder="Contoh: Permohonan Integrasi Backup Server Arsip ke Data Center..."
                    helper="Tuliskan intisari atau tujuan pokok dari surat keluar ini"
                />

                {{-- Lampiran File --}}
                <x-form-file
                    name="file_surat"
                    label="Lampiran Berkas Surat (Salinan Final / Tembusan)"
                    :required="false"
                    helper="Format: PDF, Word (DOC/DOCX), Excel (XLS/XLSX), Foto (JPG/PNG) — Maks. 3MB. Foto akan otomatis dikonversi ke PDF."
                />
            </div>

            {{-- Footer Actions --}}
            <div class="px-6 sm:px-8 py-4 border-t border-neutral-200/80 bg-neutral-50/50 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('surat-keluar.index') }}" class="w-full sm:w-auto px-5 py-2.5 bg-white hover:bg-neutral-100 text-neutral-700 font-bold rounded-xl text-sm border border-neutral-300/80 transition text-center">
                    Batal
                </a>
                <x-button type="submit" variant="success" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold">
                    Simpan Data Surat Keluar
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
