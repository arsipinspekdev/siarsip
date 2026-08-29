/**
 * File Upload Preview & Validation
 * Menangani drag-and-drop file, menampilkan nama & ukuran file yang dipilih,
 * serta memberikan validasi format/ukuran client-side ramah pengguna.
 */
export function initFileUploadPreview() {
  const uploadContainers = document.querySelectorAll('[data-file-upload]');

  uploadContainers.forEach((container) => {
    const input = container.querySelector('input[type="file"]');
    const dropZone = container.querySelector('[data-drop-zone]');
    const previewBox = container.querySelector('[data-file-preview]');
    const fileNameEl = container.querySelector('[data-file-name]');
    const fileSizeEl = container.querySelector('[data-file-size]');
    const removeBtn = container.querySelector('[data-file-remove]');
    const clientErrorEl = container.querySelector('[data-file-error]');

    if (!input) return;

    function formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function handleFile(file) {
      if (clientErrorEl) clientErrorEl.textContent = '';

      if (!file) {
        if (previewBox) previewBox.classList.add('hidden');
        return;
      }

      // Validasi ukuran maks (100MB = 100 * 1024 * 1024)
      const maxSize = 100 * 1024 * 1024;
      if (file.size > maxSize) {
        if (clientErrorEl) {
          clientErrorEl.textContent = 'Ukuran file melebihi batas maksimum 100MB (File Anda: ' + formatFileSize(file.size) + '). Silakan pilih file yang lebih kecil.';
        }
        input.value = '';
        if (previewBox) previewBox.classList.add('hidden');
        return;
      }

      if (fileNameEl) fileNameEl.textContent = file.name;
      if (fileSizeEl) fileSizeEl.textContent = formatFileSize(file.size);
      if (previewBox) previewBox.classList.remove('hidden');
    }

    input.addEventListener('change', function () {
      if (this.files && this.files[0]) {
        handleFile(this.files[0]);
      }
    });

    if (removeBtn) {
      removeBtn.addEventListener('click', function () {
        input.value = '';
        if (previewBox) previewBox.classList.add('hidden');
        if (clientErrorEl) clientErrorEl.textContent = '';
      });
    }

    if (dropZone) {
      ['dragenter', 'dragover'].forEach((eventName) => {
        dropZone.addEventListener(eventName, (e) => {
          e.preventDefault();
          e.stopPropagation();
          dropZone.classList.add('border-primary-500', 'bg-primary-50');
        });
      });

      ['dragleave', 'drop'].forEach((eventName) => {
        dropZone.addEventListener(eventName, (e) => {
          e.preventDefault();
          e.stopPropagation();
          dropZone.classList.remove('border-primary-500', 'bg-primary-50');
        });
      });

      dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        if (dt && dt.files && dt.files[0]) {
          input.files = dt.files;
          handleFile(dt.files[0]);
        }
      });
    }
  });
}
