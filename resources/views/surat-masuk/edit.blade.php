<x-layouts.app>
    <x-slot name="title">Ubah Surat Masuk</x-slot>
    <x-slot name="header">Ubah Data Surat Masuk</x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Surat Masuk', 'url' => route('surat-masuk.index')],
        ['label' => 'Ubah Agenda ' . $suratMasuk->no_agenda_formatted]
    ]" />

    <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-xs max-w-4xl overflow-hidden">
        {{-- Form Header --}}
        <div class="px-6 sm:px-8 py-5 border-b border-neutral-200/80 bg-neutral-50/50 flex items-center justify-between">
            <div>
                <h2 class="text-base font-extrabold text-neutral-900 tracking-tight">Perbarui Data Surat Masuk</h2>
                <p class="text-xs text-neutral-500 mt-0.5">Periksa dan ubah data yang perlu diperbarui untuk Agenda {{ $suratMasuk->no_agenda_formatted }}.</p>
            </div>
            <span class="px-3 py-1.5 bg-primary-50 border border-primary-200 rounded-lg text-xs font-extrabold text-primary-700 flex-shrink-0">
                Agenda {{ $suratMasuk->no_agenda_formatted }}
            </span>
        </div>

        <form method="POST" action="{{ route('surat-masuk.update', $suratMasuk) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="px-6 sm:px-8 py-6 space-y-5">
                {{-- Nomor Surat & Asal Surat --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form-input
                        name="nomor_surat"
                        label="Nomor Surat"
                        :required="true"
                        :value="$suratMasuk->nomor_surat"
                        placeholder="Contoh: 005/120/DISDIK/2026"
                    />
                    <x-form-input
                        name="asal_surat"
                        label="Asal Surat (Instansi / Pengirim)"
                        :required="true"
                        :value="$suratMasuk->asal_surat"
                        placeholder="Contoh: Dinas Pendidikan dan Kebudayaan"
                    />
                </div>

                {{-- Tanggal Surat & Tanggal Terima --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form-input
                        name="tanggal_surat"
                        label="Tanggal Surat Dibuat"
                        type="date"
                        :required="true"
                        :value="$suratMasuk->tanggal_surat?->format('Y-m-d')"
                    />
                    <x-form-input
                        name="tanggal_terima"
                        label="Tanggal Diterima di Kantor"
                        type="date"
                        :required="true"
                        :value="$suratMasuk->tanggal_terima?->format('Y-m-d')"
                    />
                </div>

                {{-- Perihal --}}
                <x-form-textarea
                    name="perihal"
                    label="Perihal / Isi Ringkas Surat"
                    :required="true"
                    :rows="4"
                    :value="$suratMasuk->perihal"
                />

                {{-- Lampiran File --}}
                <x-form-file
                    name="file_surat"
                    label="Lampiran Berkas Dokumen (Opsional)"
                    :required="false"
                    :existing-file="$suratMasuk->file_surat"
                    helper="Kosongkan jika tidak ingin mengubah lampiran yang sudah ada"
                />
            </div>

            {{-- Footer Actions --}}
            <div class="px-6 sm:px-8 py-4 border-t border-neutral-200/80 bg-neutral-50/50 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('surat-masuk.index') }}" class="w-full sm:w-auto px-5 py-2.5 bg-white hover:bg-neutral-100 text-neutral-700 font-bold rounded-xl text-sm border border-neutral-300/80 transition text-center">
                    Batal
                </a>
                <x-button type="submit" variant="warning" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold">
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
