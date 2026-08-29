/**
 * Password Visibility Toggle
 * Mengizinkan pengguna menampilkan/menyembunyikan password agar tidak salah ketik (sangat ramah lansia/awam).
 */
export function initPasswordToggle() {
  document.querySelectorAll('[data-password-toggle]').forEach((btn) => {
    btn.addEventListener('click', function () {
      const targetId = this.dataset.target || this.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if (!input) return;

      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';

      const showText = this.querySelector('[data-text-show]');
      const hideText = this.querySelector('[data-text-hide]');
      const showIcon = this.querySelector('[data-icon-show]');
      const hideIcon = this.querySelector('[data-icon-hide]');

      if (showText && hideText) {
        showText.classList.toggle('hidden', isPassword);
        hideText.classList.toggle('hidden', !isPassword);
      }
      if (showIcon && hideIcon) {
        showIcon.classList.toggle('hidden', isPassword);
        hideIcon.classList.toggle('hidden', !isPassword);
      }
    });
  });
}
