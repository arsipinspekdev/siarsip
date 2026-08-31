<x-layouts.app>
    <x-slot name="title">Ubah Surat Keluar</x-slot>
    <x-slot name="header">Ubah Data Surat Keluar</x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Surat Keluar', 'url' => route('surat-keluar.index')],
        ['label' => 'Ubah Agenda ' . $suratKeluar->no_agenda_formatted]
    ]" />

    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs max-w-4xl overflow-hidden">
        {{-- Form Header --}}
        <div class="px-6 sm:px-8 py-5 border-b border-neutral-200/80 bg-neutral-50/50 flex items-center justify-between">
            <div>
                <h2 class="text-base font-extrabold text-neutral-900 tracking-tight">Perbarui Data Surat Keluar</h2>
                <p class="text-xs text-neutral-500 mt-0.5">Periksa dan sesuaikan data yang ingin diperbarui untuk Agenda {{ $suratKeluar->no_agenda_formatted }}.</p>
            </div>
            <span class="px-3 py-1.5 bg-success-50 border border-success-200 rounded-lg text-xs font-extrabold text-success-700 flex-shrink-0">
                Agenda {{ $suratKeluar->no_agenda_formatted }}
            </span>
        </div>

        <form method="POST" action="{{ route('surat-keluar.update', $suratKeluar) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="px-6 sm:px-8 py-6 space-y-5">
                {{-- Nomor Surat & Tujuan Surat --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form-input
                        name="nomor_surat"
                        label="Nomor Surat Keluar"
                        :required="true"
                        :value="$suratKeluar->nomor_surat"
                        placeholder="Contoh: 005/310/ARSIP/2026"
                    />
                    <x-form-input
                        name="tujuan_surat"
                        label="Tujuan Surat (Instansi / Penerima)"
                        :required="true"
                        :value="$suratKeluar->tujuan_surat"
                        placeholder="Contoh: Kepala Dinas Komunikasi dan Informatika"
                    />
                </div>

                {{-- Tanggal Surat --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form-input
                        name="tanggal_surat"
                        label="Tanggal Surat Diterbitkan"
                        type="date"
                        :required="true"
                        :value="$suratKeluar->tanggal_surat?->format('Y-m-d')"
                    />
                </div>

                {{-- Sifat Surat & Pengirim --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form-select
                        name="sifat_surat"
                        label="Sifat Surat"
                        :required="false"
                        placeholder="-- Pilih Sifat Surat --"
                        :options="['Segera' => 'Segera', 'Biasa' => 'Biasa', 'Penting' => 'Penting', 'Rahasia' => 'Rahasia', 'Tertutup' => 'Tertutup']"
                        :selected="$suratKeluar->sifat_surat"
                        helper="Pilih klasifikasi sifat surat"
                    />
                    <x-form-input
                        name="pengirim"
                        label="Pengirim"
                        :required="false"
                        :value="$suratKeluar->pengirim"
                        placeholder="Contoh: Nama instansi atau individu pengirim"
                        helper="Nama pihak yang mengirimkan atau menandatangani surat ini"
                    />
                </div>

                {{-- Tanggal Penomoran & Disposisi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form-input
                        name="tanggal_penomoran"
                        label="Tanggal Penomoran"
                        type="date"
                        :required="false"
                        :value="$suratKeluar->tanggal_penomoran?->format('Y-m-d')"
                        helper="Tanggal surat mendapat nomor registrasi"
                    />
                    <x-form-input
                        name="disposisi"
                        label="Disposisi"
                        :required="false"
                        :value="$suratKeluar->disposisi"
                        placeholder="Contoh: Kepala Seksi Arsip"
                        helper="Pihak yang mendapat disposisi surat ini"
                    />
                </div>

                {{-- Pengelola & Jenis Surat --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form-input
                        name="pengelola"
                        label="Pengelola"
                        :required="false"
                        :value="$suratKeluar->pengelola"
                        placeholder="Contoh: Bagian Tata Usaha"
                        helper="Unit atau petugas yang mengelola surat ini"
                    />
                    <x-form-input
                        name="jenis_surat"
                        label="Jenis Surat"
                        :required="false"
                        :value="$suratKeluar->jenis_surat"
                        placeholder="Contoh: Surat Edaran / Nota Dinas"
                        helper="Jenis atau kategori surat"
                    />
                </div>

                {{-- Perihal --}}
                <x-form-textarea
                    name="perihal"
                    label="Perihal / Isi Ringkas Surat"
                    :required="true"
                    :rows="4"
                    :value="$suratKeluar->perihal"
                />

                {{-- Lampiran File --}}
                <x-form-file
                    name="file_surat"
                    label="Lampiran Berkas Surat (Opsional)"
                    :required="false"
                    :existing-file="$suratKeluar->file_surat"
                    helper="Kosongkan jika tidak ingin mengubah lampiran yang sudah ada"
                />
            </div>

            {{-- Footer Actions --}}
            <div class="px-6 sm:px-8 py-4 border-t border-neutral-200/80 bg-neutral-50/50 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('surat-keluar.index') }}" class="w-full sm:w-auto px-5 py-2.5 bg-white hover:bg-neutral-100 text-neutral-700 font-bold rounded-xl text-sm border border-neutral-300/80 transition text-center">
                    Batal
                </a>
                <x-button type="submit" variant="warning" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold">
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
