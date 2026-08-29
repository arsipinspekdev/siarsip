import './bootstrap';
import { initConfirmModal } from './modules/confirm-modal';
import { initFlashAlert } from './modules/flash-alert';
import { initFileUploadPreview } from './modules/file-upload-preview';
import { initMobileNav } from './modules/mobile-nav';
import { initPasswordToggle } from './modules/password-toggle';
import { initTableBulkSelect } from './modules/table-bulk-select';
import { initDashboardCharts } from './modules/chart-dashboard';
import { initDropdownMenus } from './modules/dropdown-menu';

document.addEventListener('DOMContentLoaded', () => {
  initConfirmModal();
  initFlashAlert();
  initFileUploadPreview();
  initMobileNav();
  initPasswordToggle();
  initTableBulkSelect();
  initDashboardCharts();
  initDropdownMenus();
});
