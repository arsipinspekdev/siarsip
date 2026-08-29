{{-- Modal Konfirmasi Global Kustom (Pengganti confirm() bawaan browser yang kecil) --}}
<div
    id="confirm-modal"
    role="dialog"
    aria-modal="true"
    aria-hidden="true"
    class="hidden fixed inset-0 z-50 overflow-y-auto bg-neutral-900/60 backdrop-blur-xs flex items-center justify-center p-4"
>
    <div class="bg-white rounded-2xl shadow-2xl border-2 border-neutral-200 max-w-lg w-full p-6 sm:p-8 transform transition-all">
        <div class="flex items-start gap-4 mb-6">
            <div class="w-14 h-14 rounded-2xl bg-danger-100 border-2 border-danger-200 flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-neutral-900">Konfirmasi Hapus</h3>
                    <button id="confirm-modal-close" type="button" aria-label="Tutup" class="text-neutral-400 hover:text-neutral-700 p-1 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p id="confirm-modal-message" class="text-base text-neutral-700 mt-2 leading-relaxed font-normal">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t-2 border-neutral-100">
            <button
                id="confirm-modal-no"
                type="button"
                class="w-full sm:w-auto px-6 py-3 rounded-lg border-2 border-neutral-300 text-neutral-800 text-base font-semibold hover:bg-neutral-100 active:bg-neutral-200 transition focus:outline-none focus:ring-4 focus:ring-neutral-200"
            >
                Batal
            </button>
            <button
                id="confirm-modal-yes"
                type="button"
                class="w-full sm:w-auto px-6 py-3 rounded-lg bg-danger-600 hover:bg-danger-700 active:bg-danger-800 text-white text-base font-semibold shadow-sm transition focus:outline-none focus:ring-4 focus:ring-danger-200"
            >
                Ya, Hapus Data
            </button>
        </div>
    </div>
</div>
