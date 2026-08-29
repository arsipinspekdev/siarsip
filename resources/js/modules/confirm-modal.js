/**
 * Modal Konfirmasi Kustom (Pengganti confirm() browser yang kecil)
 * Membuka modal konfirmasi besar dan ramah lansia saat form dengan atribut [data-confirm-delete] di-submit.
 */
export function initConfirmModal() {
  const modal = document.getElementById('confirm-modal');
  const messageEl = document.getElementById('confirm-modal-message');
  const yesBtn = document.getElementById('confirm-modal-yes');
  const noBtn = document.getElementById('confirm-modal-no');
  const closeBtn = document.getElementById('confirm-modal-close');

  if (!modal || !yesBtn || !noBtn) return;

  let activeForm = null;

  function closeModal() {
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    activeForm = null;
  }

  function openModal(form, message) {
    activeForm = form;
    if (messageEl) {
      messageEl.textContent = message || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
    }
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    yesBtn.focus();
  }

  // Delegasi event submit untuk semua form dengan atribut data-confirm-delete
  document.addEventListener('submit', function (e) {
    const form = e.target.closest('form[data-confirm-delete]');
    if (form) {
      if (form.dataset.confirmed === 'true') {
        return; // Loloskan submit jika sudah dikonfirmasi
      }
      e.preventDefault();
      const message = form.getAttribute('data-confirm-message') || form.dataset.confirmMessage;
      openModal(form, message);
    }
  });

  yesBtn.addEventListener('click', function () {
    if (activeForm) {
      activeForm.dataset.confirmed = 'true';
      activeForm.submit();
      closeModal();
    }
  });

  noBtn.addEventListener('click', closeModal);
  if (closeBtn) closeBtn.addEventListener('click', closeModal);

  // Tutup dengan tombol Escape atau klik area luar modal
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
      closeModal();
    }
  });

  modal.addEventListener('click', function (e) {
    if (e.target === modal) {
      closeModal();
    }
  });
}
