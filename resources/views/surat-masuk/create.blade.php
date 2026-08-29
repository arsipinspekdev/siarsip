<x-layouts.app>
    <x-slot name="title">Tambah Surat Masuk</x-slot>
    <x-slot name="header">Input Surat Masuk Baru</x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Surat Masuk', 'url' => route('surat-masuk.index')],
        ['label' => 'Tambah Surat Baru']
    ]" />

    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs max-w-4xl overflow-hidden">
        {{-- Form Header --}}
        <div class="px-6 sm:px-8 py-5 border-b border-neutral-200/80 bg-neutral-50/50 flex items-center justify-between">
            <div>
                <h2 class="text-base font-extrabold text-neutral-900 tracking-tight">Formulir Perekaman Surat Masuk</h2>
                <p class="text-xs text-neutral-500 mt-0.5">Isi seluruh data sesuai fisik surat yang diterima instansi.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('surat-masuk.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="px-6 sm:px-8 py-6 space-y-5">
                {{-- Nomor Surat & Asal Surat --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form-input
                        name="nomor_surat"
                        label="Nomor Surat"
                        :required="true"
                        placeholder="Contoh: 005/120/DISDIK/2026"
                        helper="Nomor resmi yang tertera pada kepala surat fisik"
                    />
                    <x-form-input
                        name="asal_surat"
                        label="Asal Surat (Instansi / Pengirim)"
                        :required="true"
                        placeholder="Contoh: Dinas Pendidikan dan Kebudayaan"
                        helper="Nama instansi, organisasi, atau individu pengirim"
                    />
                </div>

                {{-- Tanggal Surat & Tanggal Terima --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form-input
                        name="tanggal_surat"
                        label="Tanggal Surat Dibuat"
                        type="date"
                        :required="true"
                        :value="date('Y-m-d')"
                        helper="Tanggal yang tertulis pada lembar surat"
                    />
                    <x-form-input
                        name="tanggal_terima"
                        label="Tanggal Diterima di Kantor"
                        type="date"
                        :required="true"
                        :value="date('Y-m-d')"
                        helper="Tanggal surat fisik sampai dan diterima"
                    />
                </div>

                {{-- Perihal --}}
                <x-form-textarea
                    name="perihal"
                    label="Perihal / Isi Ringkas Surat"
                    :required="true"
                    :rows="4"
                    placeholder="Contoh: Undangan Rapat Koordinasi Program Peningkatan Mutu Layanan..."
                    helper="Tuliskan pokok bahasan atau intisari dari surat ini"
                />

                {{-- Lampiran File --}}
                <x-form-file
                    name="file_surat"
                    label="Lampiran Berkas Digital (Scan / Dokumen)"
                    :required="false"
                    helper="Format: PDF, Word (DOC/DOCX), Excel (XLS/XLSX), Foto (JPG/PNG) — Maks. 3MB. Foto akan otomatis dikonversi ke PDF."
                />
            </div>

            {{-- Footer Actions --}}
            <div class="px-6 sm:px-8 py-4 border-t border-neutral-200/80 bg-neutral-50/50 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('surat-masuk.index') }}" class="w-full sm:w-auto px-5 py-2.5 bg-white hover:bg-neutral-100 text-neutral-700 font-bold rounded-xl text-sm border border-neutral-300/80 transition text-center">
                    Batal
                </a>
                <x-button type="submit" variant="success" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold">
                    Simpan Data Surat Masuk
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
