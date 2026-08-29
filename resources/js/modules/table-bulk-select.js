/**
 * Table Bulk Select & Batch Actions
 * Menangani centang "Pilih Semua", penghitungan jumlah terpilih, dan pengaktifan tombol "Hapus Terpilih".
 *
 * Mendukung dua ID untuk select-all: 'select-all' atau 'select-all-checkbox'.
 */
export function initTableBulkSelect() {
  // Coba keduanya agar kompatibel dengan template lama dan baru
  const selectAllCheckbox =
    document.getElementById('select-all') ||
    document.getElementById('select-all-checkbox');

  const rowCheckboxes     = document.querySelectorAll('.row-checkbox');
  const bulkActionContainer = document.getElementById('bulk-action-container');
  const selectedCountEl   = document.getElementById('selected-count');
  const bulkForm          = document.getElementById('bulk-delete-form');
  const bulkHiddenInputs  = document.getElementById('bulk-hidden-inputs');

  if (!selectAllCheckbox && rowCheckboxes.length === 0) return;

  function updateBulkState() {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    const count = checked.length;

    if (selectedCountEl) selectedCountEl.textContent = count;

    if (bulkActionContainer) {
      if (count > 0) {
        bulkActionContainer.classList.remove('hidden');
        bulkActionContainer.classList.add('flex');
      } else {
        bulkActionContainer.classList.add('hidden');
        bulkActionContainer.classList.remove('flex');
      }
    }

    if (selectAllCheckbox) {
      selectAllCheckbox.checked      = count === rowCheckboxes.length && rowCheckboxes.length > 0;
      selectAllCheckbox.indeterminate = count > 0 && count < rowCheckboxes.length;
    }
  }

  if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function () {
      rowCheckboxes.forEach((cb) => {
        cb.checked = this.checked;
      });
      updateBulkState();
    });
  }

  rowCheckboxes.forEach((cb) => {
    cb.addEventListener('change', updateBulkState);
  });

  // Saat form bulk submit, isi hidden inputs dengan ID yang tercentang
  if (bulkForm) {
    bulkForm.addEventListener('submit', function (e) {
      const checked = document.querySelectorAll('.row-checkbox:checked');

      if (checked.length === 0) {
        e.preventDefault();
        alert('Pilih setidaknya satu surat terlebih dahulu.');
        return;
      }

      // Gunakan #bulk-hidden-inputs jika ada, atau append ke form
      const container = bulkHiddenInputs || bulkForm;

      // Bersihkan input lama
      container.querySelectorAll('input[name="selected_ids[]"]').forEach((el) => el.remove());
      // Juga bersihkan format lama 'ids[]'
      container.querySelectorAll('input[name="ids[]"]').forEach((el) => el.remove());

      // Tambahkan input hidden per ID terpilih
      checked.forEach((cb) => {
        const hidden = document.createElement('input');
        hidden.type  = 'hidden';
        hidden.name  = 'selected_ids[]';
        hidden.value = cb.value;
        container.appendChild(hidden);
      });
    });
  }
}
