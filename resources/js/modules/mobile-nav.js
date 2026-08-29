/**
 * Mobile Navigation Drawer & User Dropdown Menu
 * Mengatur buka/tutup sidebar mobile dan dropdown profil pengguna secara native vanilla JS.
 */
export function initMobileNav() {
  const sidebar = document.getElementById('mobile-sidebar');
  const sidebarBackdrop = document.getElementById('mobile-sidebar-backdrop');
  const openSidebarBtn = document.getElementById('open-sidebar-btn');
  const closeSidebarBtn = document.getElementById('close-sidebar-btn');

  function openSidebar() {
    if (!sidebar || !sidebarBackdrop) return;
    sidebar.classList.remove('-translate-x-full');
    sidebarBackdrop.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  }

  function closeSidebar() {
    if (!sidebar || !sidebarBackdrop) return;
    sidebar.classList.add('-translate-x-full');
    sidebarBackdrop.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }

  if (openSidebarBtn) openSidebarBtn.addEventListener('click', openSidebar);
  if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
  if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeSidebar);

  // User dropdown menu
  const userMenuBtn = document.getElementById('user-menu-btn');
  const userMenuDropdown = document.getElementById('user-menu-dropdown');

  if (userMenuBtn && userMenuDropdown) {
    userMenuBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isExpanded = userMenuBtn.getAttribute('aria-expanded') === 'true';
      userMenuBtn.setAttribute('aria-expanded', !isExpanded);
      userMenuDropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
      if (!userMenuDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
        userMenuDropdown.classList.add('hidden');
        userMenuBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // Generic details/dropdown close on click outside
  document.addEventListener('click', (e) => {
    document.querySelectorAll('details.custom-dropdown[open]').forEach((dropdown) => {
      if (!dropdown.contains(e.target)) {
        dropdown.removeAttribute('open');
      }
    });
  });
}
