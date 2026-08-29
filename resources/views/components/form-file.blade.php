@props([
    'name',
    'label' => 'Lampiran File Dokumen',
    'required' => false,
    'helper' => 'Format: PDF, Word (DOC/DOCX), Excel (XLS/XLSX), Foto (JPG/PNG) — Ukuran Maksimal: 100MB',
    'existingFile' => null,
    'id' => null,
])

@php
$inputId = $id ?? $name;
$hasError = $errors->has($name);
@endphp

<div class="mb-6" data-file-upload>
    <label class="block text-xs font-bold text-neutral-700 mb-1.5 uppercase tracking-wider">
        {{ $label }}
        @if($required)
            <span class="text-danger-600 font-bold" title="Wajib diisi">*</span>
            <span class="text-sm font-normal text-danger-600 ml-1">(wajib diisi)</span>
        @endif
    </label>

    @if($existingFile)
        <div class="mb-3 p-4 bg-primary-50 border-2 border-primary-200 rounded-lg flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-primary-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div>
                    <p class="text-base font-semibold text-neutral-900">File Tersimpan Saat Ini:</p>
                    <p class="text-sm text-neutral-600 break-all">{{ basename($existingFile) }}</p>
                </div>
            </div>
            <a href="{{ asset('storage/' . $existingFile) }}" target="_blank" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700">
                Lihat File
            </a>
        </div>
        <p class="text-sm text-neutral-600 mb-2">Unggah file baru di bawah ini hanya jika Anda ingin mengganti file yang sudah ada:</p>
    @endif

    <div
        data-drop-zone
        class="border-2 border-dashed rounded-lg p-6 text-center transition-colors duration-150 cursor-pointer {{ $hasError ? 'border-danger-600 bg-danger-50/30' : 'border-neutral-300 hover:border-primary-500 bg-white hover:bg-neutral-50' }}"
        onclick="document.getElementById('{{ $inputId }}').click()"
    >
        <svg class="w-12 h-12 mx-auto text-neutral-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>

        <p class="text-base font-semibold text-neutral-800">
            Klik di sini untuk memilih file, atau seret & lepas file ke area ini
        </p>
        <p class="text-sm text-neutral-500 mt-1">
            {{ $helper }}
        </p>

        <input
            type="file"
            id="{{ $inputId }}"
            name="{{ $name }}"
            class="hidden"
            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
            {{ $required && !$existingFile ? 'required' : '' }}
        />

        <div class="mt-4">
            <button type="button" class="px-5 py-2.5 bg-neutral-100 hover:bg-neutral-200 border-2 border-neutral-300 text-neutral-800 rounded-lg text-base font-semibold transition" onclick="event.stopPropagation(); document.getElementById('{{ $inputId }}').click()">
                Pilih Dokumen dari Komputer
            </button>
        </div>
    </div>

    <!-- Preview Container -->
    <div data-file-preview class="hidden mt-3 p-4 bg-success-50 border-2 border-success-200 rounded-lg flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-success-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <div>
                <p class="text-base font-semibold text-success-900">File Siap Diunggah:</p>
                <p class="text-base text-neutral-800 font-medium break-all">
                    <span data-file-name></span> (<span data-file-size class="text-sm text-neutral-600"></span>)
                </p>
            </div>
        </div>
        <button type="button" data-file-remove class="px-4 py-2 bg-white hover:bg-danger-50 text-danger-700 border border-danger-300 rounded-lg text-sm font-semibold transition">
            Batalkan Pilihan
        </button>
    </div>

    <p data-file-error class="mt-2 text-base font-semibold text-danger-600"></p>

    @error($name)
        <p class="mt-2 text-base font-semibold text-danger-600 flex items-center gap-1.5" role="alert">
            <svg class="w-5 h-5 flex-shrink-0 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
