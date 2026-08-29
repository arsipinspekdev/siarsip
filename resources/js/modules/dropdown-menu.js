/**
 * Dropdown Menu — Vanilla JS
 * Handles generic toggle dropdowns via [data-dropdown-btn] + [data-dropdown-menu].
 * Closes on outside click and Escape key.
 */
export function initDropdownMenus() {
  document.querySelectorAll('[data-dropdown-btn]').forEach((btn) => {
    const menu = btn.nextElementSibling;
    if (!menu || !menu.hasAttribute('data-dropdown-menu')) return;

    // Toggle on button click
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = !menu.classList.contains('hidden');

      // Close all other dropdowns first
      document.querySelectorAll('[data-dropdown-menu]').forEach((m) => {
        m.classList.add('hidden');
        m.previousElementSibling?.setAttribute('aria-expanded', 'false');
      });

      if (!isOpen) {
        menu.classList.remove('hidden');
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  });

  // Close on outside click
  document.addEventListener('click', () => {
    document.querySelectorAll('[data-dropdown-menu]').forEach((menu) => {
      menu.classList.add('hidden');
      menu.previousElementSibling?.setAttribute('aria-expanded', 'false');
    });
  });

  // Close on Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.querySelectorAll('[data-dropdown-menu]').forEach((menu) => {
        menu.classList.add('hidden');
        menu.previousElementSibling?.setAttribute('aria-expanded', 'false');
      });
    }
  });
}
