/**
 * Banner Notifikasi (Flash Alert)
 * Menangani penutupan manual dan auto-dismiss santai (10 detik) agar tidak mengejutkan pengguna.
 */
export function initFlashAlert() {
  const alerts = document.querySelectorAll('[data-flash-alert]');

  alerts.forEach((alert) => {
    const closeBtn = alert.querySelector('[data-alert-close]');
    if (closeBtn) {
      closeBtn.addEventListener('click', () => {
        dismissAlert(alert);
      });
    }

    // Auto-dismiss jika diaktifkan (default tidak auto-dismiss cepat)
    if (alert.dataset.autoDismiss === 'true') {
      setTimeout(() => {
        dismissAlert(alert);
      }, 10000);
    }
  });

  function dismissAlert(el) {
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    el.style.opacity = '0';
    el.style.transform = 'translateY(-10px)';
    setTimeout(() => {
      el.remove();
    }, 500);
  }
}
