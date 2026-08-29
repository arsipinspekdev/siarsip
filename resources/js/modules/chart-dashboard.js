/**
 * Dashboard Chart Renderer (Chart.js via CDN/Native)
 * Merender grafik batang bulanan untuk Surat Masuk dan Surat Keluar jika elemen canvas ditemukan.
 */
export function initDashboardCharts() {
  const masukCanvas = document.getElementById('chartSuratMasuk');
  const keluarCanvas = document.getElementById('chartSuratKeluar');

  if (!window.Chart) return;

  if (masukCanvas && window.dashboardDataMasuk) {
    new window.Chart(masukCanvas, {
      type: 'bar',
      data: {
        labels: window.dashboardDataMasuk.labels,
        datasets: [{
          label: 'Jumlah Surat Masuk',
          data: window.dashboardDataMasuk.data,
          backgroundColor: '#2563eb', // primary-600
          borderRadius: 8,
          borderSkipped: false,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            titleFont: { size: 16, family: 'Plus Jakarta Sans' },
            bodyFont: { size: 16, family: 'Plus Jakarta Sans' },
            padding: 12,
            cornerRadius: 8,
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              font: { size: 14, family: 'Plus Jakarta Sans' }
            },
            grid: { color: '#e2e8f0' }
          },
          x: {
            ticks: { font: { size: 14, family: 'Plus Jakarta Sans' } },
            grid: { display: false }
          }
        }
      }
    });
  }

  if (keluarCanvas && window.dashboardDataKeluar) {
    new window.Chart(keluarCanvas, {
      type: 'bar',
      data: {
        labels: window.dashboardDataKeluar.labels,
        datasets: [{
          label: 'Jumlah Surat Keluar',
          data: window.dashboardDataKeluar.data,
          backgroundColor: '#16a34a', // success-600
          borderRadius: 8,
          borderSkipped: false,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            titleFont: { size: 16, family: 'Plus Jakarta Sans' },
            bodyFont: { size: 16, family: 'Plus Jakarta Sans' },
            padding: 12,
            cornerRadius: 8,
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              font: { size: 14, family: 'Plus Jakarta Sans' }
            },
            grid: { color: '#e2e8f0' }
          },
          x: {
            ticks: { font: { size: 14, family: 'Plus Jakarta Sans' } },
            grid: { display: false }
          }
        }
      }
    });
  }
}
