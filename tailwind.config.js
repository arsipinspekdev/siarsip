/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50:  '#eff6ff',
          100: '#dbeafe',
          500: '#3b82f6',
          600: '#2563eb',   // warna utama tombol & link aktif
          700: '#1d4ed8',   // hover state
        },
        success: {
          50:  '#f0fdf4',
          100: '#dcfce7',
          600: '#16a34a',
          700: '#15803d',
        },
        warning: {
          50:  '#fffbeb',
          100: '#fef3c7',
          500: '#f59e0b',
          600: '#d97706',
        },
        danger: {
          50:  '#fef2f2',
          100: '#fee2e2',
          600: '#dc2626',
          700: '#b91c1c',
        },
        neutral: {
          50:  '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
        },
      },
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', '"Inter"', 'sans-serif'],
      },
      fontSize: {
        base: ['18px', { lineHeight: '1.6' }],   // override default 16px
        sm:   ['16px', { lineHeight: '1.5' }],   // ukuran teks pendukung
        lg:   ['20px', { lineHeight: '1.6' }],
        xl:   ['24px', { lineHeight: '1.5' }],
        '2xl':['30px', { lineHeight: '1.4' }],   // judul halaman H1
        '3xl':['36px', { lineHeight: '1.3' }],   // judul besar statistik
      },
    },
  },
  plugins: [],
}
