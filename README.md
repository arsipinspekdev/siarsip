# SiARSIP — Sistem Arsip Digital Surat

Aplikasi web berbasis Laravel untuk mengelola arsip **surat masuk** dan **surat keluar** pada sebuah instansi, lengkap dengan manajemen pengguna berbasis role dan matrix hak akses (permission) per modul.

## Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Fitur Utama](#fitur-utama)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Arsitektur Aplikasi](#arsitektur-aplikasi)
- [Struktur Direktori](#struktur-direktori)
- [Kebutuhan Sistem](#kebutuhan-sistem)
- [Instalasi & Menjalankan Secara Lokal](#instalasi--menjalankan-secara-lokal)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Database](#database)
- [Autentikasi & Otorisasi](#autentikasi--otorisasi)
- [Manajemen Dokumen (Upload File)](#manajemen-dokumen-upload-file)
- [Ekspor Laporan & Cetak](#ekspor-laporan--cetak)
- [Peta Rute Aplikasi](#peta-rute-aplikasi)
- [Testing](#testing)
- [Build Frontend](#build-frontend)
- [Deployment](#deployment)
- [Keamanan](#keamanan)
- [Troubleshooting](#troubleshooting)
- [Lisensi](#lisensi)

## Tentang Proyek

SiARSIP dibuat untuk menggantikan pencatatan buku agenda surat manual di instansi dengan sistem digital yang terpusat. Aplikasi ini menjawab masalah seperti sulitnya menelusuri riwayat surat, risiko dokumen fisik hilang atau rusak, serta tidak adanya kontrol siapa yang boleh menambah, mengubah, atau menghapus data surat.

Setiap surat masuk dan surat keluar dicatat dengan nomor agenda berurutan, disertai lampiran dokumen asli (PDF, gambar, atau dokumen kantor lain), dan dapat direkap dalam bentuk laporan cetak, PDF, Excel, maupun CSV. Akses terhadap setiap modul diatur melalui matrix **role x permission**, sehingga instansi dapat menentukan siapa yang hanya bisa melihat data dan siapa yang boleh mengelola atau mengekspornya.

## Fitur Utama

- **Manajemen Surat Masuk & Surat Keluar** — pencatatan CRUD lengkap dengan nomor agenda otomatis (`no_agenda`) yang selalu berurutan tanpa celah, meskipun ada data yang dihapus.
- **Upload & Konversi Dokumen** — lampiran surat dapat berupa PDF, DOC/DOCX, XLS/XLSX, atau gambar (JPG/PNG). Jika file yang diunggah berupa gambar, sistem otomatis membuat versi PDF-nya menggunakan DomPDF sehingga dokumen tetap bisa dicetak/diunduh dalam format seragam.
- **Pencarian, Sorting, dan Pagination** — daftar surat dapat dicari berdasarkan nomor surat, asal/tujuan surat, dan perihal, dengan kolom pengurutan yang di-*whitelist* untuk mencegah manipulasi parameter query.
- **Hapus Massal (Bulk Delete)** — penghapusan beberapa data surat sekaligus dari daftar.
- **Ekspor Laporan** — unduh rekap surat dalam format PDF (landscape), Excel (`.xlsx`), dan CSV, serta tampilan cetak (print view) yang bisa langsung dicetak dari browser.
- **Soft Delete** — data surat dan pengguna yang dihapus tidak langsung hilang permanen dari database (mendukung audit trail).
- **Manajemen Pengguna** — tambah, ubah, dan hapus akun pengguna beserta foto profil dan penetapan role.
- **Manajemen Role & Permission Matrix** — admin dapat membuat role baru dan mengatur hak akses (`view`, `create`, `update`, `delete`, `export`) untuk setiap modul (Surat Masuk, Surat Keluar, Pengguna, Role, Permission) melalui satu halaman matrix visual.
- **Dashboard Statistik** — ringkasan jumlah surat masuk, surat keluar, dan pengguna, daftar 5 surat terbaru dari masing-masing kategori, serta grafik tren 6 bulan terakhir (Chart.js).
- **Profil Pengguna** — setiap pengguna dapat mengubah data profil, foto, dan kata sandi sendiri.
- **Rate Limiting Login** — percobaan login yang gagal dibatasi 5 kali per kombinasi username/email dan IP, dengan cooldown 5 menit.

## Teknologi yang Digunakan

**Backend**
- [Laravel 10](https://laravel.com/) (PHP `^8.1`)
- Laravel Sanctum — infrastruktur token (tersedia untuk kebutuhan API/SPA)
- [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) — pembuatan dokumen PDF (laporan & konversi gambar ke PDF)
- [maatwebsite/excel](https://github.com/SpartnerNL/Laravel-Excel) — ekspor laporan ke Excel/CSV
- Ekstensi PHP yang dibutuhkan: `gd`, `zip`, `pdo_mysql`, `mbstring`, `intl`, `bcmath`, `xml`

**Frontend**
- Blade Templates (server-rendered, tanpa SPA framework)
- [Tailwind CSS v4](https://tailwindcss.com/) (via plugin PostCSS `@tailwindcss/postcss`)
- [Vite 5](https://vitejs.dev/) + `laravel-vite-plugin` untuk bundling asset
- JavaScript modular (vanilla JS, tanpa framework) — modul untuk modal konfirmasi, dropdown, bulk-select tabel, preview upload file, navigasi mobile, dan toggle password
- [Chart.js 4.4.1](https://www.chartjs.org/) (dimuat via CDN) — grafik statistik dashboard
- Google Fonts: Inter dan Plus Jakarta Sans

**Database**
- MySQL (koneksi default untuk produksi, membutuhkan ekstensi `pdo_mysql`)
- SQLite juga didukung dan digunakan pada environment development lokal contoh proyek ini

**Infrastruktur & Deployment**
- Docker (image `php:8.2-apache`) untuk containerization
- [Railway](https://railway.app/) sebagai target deployment (`railway.toml`, `docker/start.sh`)
- Apache dengan `mod_rewrite` dan MPM Prefork

**Testing**
- PHPUnit 10 (skeleton bawaan Laravel; lihat bagian [Testing](#testing))

## Arsitektur Aplikasi

SiARSIP mengikuti pola **MVC** standar Laravel dengan beberapa layer tambahan untuk memisahkan tanggung jawab:

- **Controller** (`app/Http/Controllers`) — menangani request HTTP untuk setiap modul (Surat Masuk, Surat Keluar, Dashboard, Pengguna, Role, Permission, Profil, Auth).
- **Form Request** (`app/Http/Requests`) — validasi input terpisah per aksi (`Store...Request`, `Update...Request`) untuk modul Surat Masuk dan Surat Keluar.
- **Model** (`app/Models`) — representasi tabel `users`, `roles`, `permissions`, `surat_masuk`, `surat_keluar`, dengan relasi Eloquent (`belongsTo`, `hasMany`, `belongsToMany`) dan accessor tambahan (mis. `no_agenda_formatted`, `avatar_url`).
- **Trait** (`app/Traits/ConvertsImageToPdf.php`) — logika reusable untuk menangani upload dokumen dan konversi gambar ke PDF, dipakai bersama oleh `SuratMasukController` dan `SuratKeluarController`.
- **Observer** (`app/Observers`) — `SuratMasukObserver` dan `SuratKeluarObserver` menjaga konsistensi data pada siklus hidup model (event Eloquent).
- **Export Class** (`app/Exports`) — kelas terpisah (`SuratMasukExport`, `SuratKeluarExport`) yang mengimplementasikan interface Laravel Excel untuk membangun sheet laporan.
- **Middleware** (`app/Http/Middleware/CheckPermission.php`) — middleware kustom dengan alias `permission:{module},{action}` yang memvalidasi hak akses pengguna terhadap modul dan aksi tertentu sebelum request diteruskan ke controller.
- **View** (`resources/views`) — Blade templates dipecah per modul, dengan komponen reusable (`resources/views/components`) seperti form input, tombol, modal konfirmasi, breadcrumb, dan pagination.

Alur permintaan untuk modul transaksional (Surat Masuk/Keluar) secara umum: `Route → Middleware auth + permission → Controller → Form Request (validasi) → Trait upload (jika ada file) → Model/Eloquent → View Blade`.

## Struktur Direktori

```
app/
├── Console/            Kernel Artisan (scheduler)
├── Exceptions/         Exception handler
├── Exports/            Kelas ekspor Excel/CSV (Surat Masuk & Surat Keluar)
├── Http/
│   ├── Controllers/    Controller per modul + subfolder Auth/
│   ├── Middleware/     Middleware bawaan Laravel + CheckPermission kustom
│   └── Requests/       Form Request validasi (SuratMasuk/, SuratKeluar/)
├── Models/              Eloquent model: User, Role, Permission, SuratMasuk, SuratKeluar
├── Observers/           Observer untuk SuratMasuk & SuratKeluar
├── Providers/           Service provider bawaan Laravel
└── Traits/              ConvertsImageToPdf (upload & konversi dokumen)

database/
├── factories/
├── migrations/          Skema tabel: roles, permissions, permission_role, users,
│                         surat_masuk, surat_keluar, dan migration tambahan
│                         (no_agenda, file_pdf)
└── seeders/              RoleSeeder, PermissionSeeder, UserSeeder,
                          SuratMasukSeeder, SuratKeluarSeeder

resources/
├── css/app.css          Entry point Tailwind CSS v4
├── js/
│   ├── app.js            Entry point JS, inisialisasi seluruh modul
│   └── modules/           Modul JS terpisah (modal, dropdown, chart, dst.)
└── views/
    ├── auth/              Halaman login
    ├── dashboard/          Halaman dashboard & statistik
    ├── surat-masuk/        index, create, edit, show, print, report-pdf
    ├── surat-keluar/       index, create, edit, show, print, report-pdf
    ├── users/ roles/ permissions/   Modul administrasi
    ├── profile/            Edit profil pengguna
    ├── components/         Komponen Blade reusable (form, tombol, modal, dll.)
    ├── layouts/            Layout utama (app, guest)
    └── errors/             Halaman error kustom (403, 404, 419, 500)

routes/
├── web.php               Seluruh rute web aplikasi
└── console.php

docker/start.sh           Skrip boot container (migrate, seed, cache, start Apache)
Dockerfile                 Image PHP 8.2 + Apache untuk deployment Railway
railway.toml                Konfigurasi deploy Railway
```

## Kebutuhan Sistem

- PHP `^8.1` beserta ekstensi: `gd`, `zip`, `pdo_mysql`, `mbstring`, `intl`, `bcmath`, `xml`
- [Composer](https://getcomposer.org/) 2.x
- Node.js beserta npm (untuk build asset Vite/Tailwind)
- Server database MySQL, **atau** SQLite untuk pengembangan lokal (driver `pdo_sqlite`)

## Instalasi & Menjalankan Secara Lokal

1. **Clone repository**

   ```bash
   git clone <url-repository-anda>
   cd SIARSIP_DMS
   ```

2. **Install dependency backend**

   ```bash
   composer install
   ```

3. **Install dependency frontend**

   ```bash
   npm install
   ```

4. **Salin file environment**

   ```bash
   cp .env.example .env
   ```

   Sesuaikan isi `.env` sesuai kebutuhan (lihat [Konfigurasi Environment](#konfigurasi-environment)).

5. **Generate application key**

   ```bash
   php artisan key:generate
   ```

6. **Siapkan database**

   - **Opsi MySQL**: buat database kosong terlebih dahulu, lalu isi `DB_CONNECTION=mysql` beserta kredensial pada `.env`.
   - **Opsi SQLite** (lebih cepat untuk pengembangan lokal): buat file database kosong, lalu set `DB_CONNECTION=sqlite` dan `DB_DATABASE` ke path absolut file tersebut.

     ```bash
     touch database/database.sqlite
     ```

7. **Jalankan migration & seeder**

   ```bash
   php artisan migrate --seed
   ```

   Perintah ini akan membuat seluruh tabel serta mengisi data awal role, permission, dan tiga akun demo (lihat [Database](#database)).

8. **Buat symbolic link storage**

   ```bash
   php artisan storage:link
   ```

   Diperlukan agar file yang diunggah pada `storage/app/public` dapat diakses lewat URL publik `public/storage`.

9. **Build asset frontend**

   ```bash
   npm run dev
   ```

   Untuk build produksi (asset ter-compile & ter-minifikasi), gunakan:

   ```bash
   npm run build
   ```

10. **Jalankan development server Laravel**

    ```bash
    php artisan serve
    ```

    Aplikasi dapat diakses melalui `http://127.0.0.1:8000` (jalankan `npm run dev` di terminal terpisah agar Vite HMR aktif selama development).

## Konfigurasi Environment

Variabel environment yang relevan dengan aplikasi ini (selain variabel bawaan Laravel):

| Variabel | Keterangan |
|---|---|
| `APP_NAME` | Nama aplikasi yang tampil pada judul halaman & email |
| `APP_ENV` | `local`, `staging`, atau `production` |
| `APP_KEY` | Kunci enkripsi Laravel, di-generate melalui `php artisan key:generate` |
| `APP_DEBUG` | Set `false` pada production untuk mencegah kebocoran informasi debug |
| `APP_URL` | Base URL aplikasi |
| `DB_CONNECTION` | `mysql` (produksi) atau `sqlite` (pengembangan lokal) |
| `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Kredensial koneksi MySQL (tidak dipakai jika `DB_CONNECTION=sqlite`) |
| `FILESYSTEM_DISK` | Disk penyimpanan file default; gunakan `public` agar lampiran surat dapat diakses lewat `storage:link` |
| `SESSION_DRIVER`, `SESSION_LIFETIME` | Konfigurasi session autentikasi |
| `MAIL_*` | Konfigurasi pengiriman email (opsional, tidak wajib untuk fitur inti aplikasi) |

**Penting:** jangan pernah melakukan commit file `.env` yang berisi kredensial asli ke repository. File `.env` sudah tercantum pada `.gitignore`; gunakan `.env.example` sebagai acuan untuk membuat `.env` lokal masing-masing developer.

## Database

Skema utama terdiri dari:

- **`roles`** — daftar role (`name`, `slug`).
- **`permissions`** — daftar hak akses granular per `module` dan `action` (kombinasi unik), misalnya `surat_masuk.view` atau `roles.delete`.
- **`permission_role`** — tabel pivot many-to-many antara role dan permission.
- **`users`** — akun pengguna, terhubung ke `roles` melalui `role_id`, mendukung soft delete dan foto profil.
- **`surat_masuk`** — data surat masuk: nomor surat, tanggal surat, tanggal terima, asal surat, perihal, lampiran (`file_surat`/`file_pdf`), nomor agenda (`no_agenda`), dan penerima (`diterima_oleh_id`).
- **`surat_keluar`** — data surat keluar: nomor surat, tanggal surat, tujuan surat, perihal, lampiran, nomor agenda, dan pembuat (`dibuat_oleh_id`).

Kedua tabel surat menggunakan soft delete dan kolom `no_agenda` yang di-*resequence* secara berurutan (tanpa celah) melalui migration khusus, terpisah dari `id` primary key auto-increment.

### Menjalankan migration

```bash
php artisan migrate
```

Untuk reset total database (drop seluruh tabel lalu migrate ulang):

```bash
php artisan migrate:fresh --seed
```

### Data awal (seeder)

`DatabaseSeeder` menjalankan seeder berikut secara berurutan: `RoleSeeder`, `PermissionSeeder`, `UserSeeder`, `SuratMasukSeeder`, `SuratKeluarSeeder`.

- **`RoleSeeder`** membuat dua role: `Administrator` (`administrator`) dan `Petugas Tata Usaha` (`user`).
- **`PermissionSeeder`** membuat seluruh permission per modul (`surat_masuk`, `surat_keluar`, `users`, `roles`, `permissions`) dengan aksi `view`, `create`, `update`, `delete`, `export`, lalu memberikan seluruh permission ke role Administrator dan sebagian (tanpa `delete`) ke role Petugas.
- **`UserSeeder`** membuat tiga akun demo untuk keperluan pengembangan/pengujian lokal:

  | Username | Role | Password |
  |---|---|---|
  | `admin` | Administrator | `admin123` |
  | `petugas` | Petugas Tata Usaha | `petugas123` |
  | `siti` | Petugas Tata Usaha | `siti12345` |

  > Akun dan kata sandi di atas berasal dari data seeder (`database/seeders/UserSeeder.php`) dan hanya dimaksudkan untuk environment development/demo. **Ganti atau hapus akun ini sebelum aplikasi digunakan pada environment production.**

## Autentikasi & Otorisasi

- Login mendukung **username atau email** (dideteksi otomatis dari format input) beserta kata sandi, dengan rate limiting 5 percobaan gagal per kombinasi login+IP (cooldown 5 menit) melalui `RateLimiter` Laravel.
- Otorisasi menggunakan model **role-based access control (RBAC) granular**: setiap role memiliki sekumpulan `permission` yang merupakan kombinasi `module` (misalnya `surat_masuk`) dan `action` (`view`, `create`, `update`, `delete`, `export`).
- Middleware kustom `permission:{module},{action}` (`App\Http\Middleware\CheckPermission`) diterapkan pada setiap rute yang membutuhkan otorisasi. Jika pengguna belum login, request dialihkan ke halaman login; jika tidak memiliki permission yang sesuai, request dihentikan dengan HTTP 403.
- Admin dapat mengatur permission untuk setiap role secara visual melalui halaman **Matrix Hak Akses** (`/hak-akses`), yang menyimpan kombinasi role-permission ke tabel pivot `permission_role`.

## Manajemen Dokumen (Upload File)

Lampiran surat (`file_surat`) menerima format **PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG** dengan batas ukuran **100 MB** (divalidasi pada `StoreSuratMasukRequest`/`StoreSuratKeluarRequest`).

Logika penyimpanan file ditangani oleh trait `ConvertsImageToPdf`:

- Jika file yang diunggah adalah **PDF**, file disimpan apa adanya dan digunakan langsung sebagai `file_surat` maupun `file_pdf`.
- Jika file berupa **gambar** (JPG/JPEG/PNG), file asli disimpan pada `storage/app/public/dokumen_surat`, kemudian sistem membuat **versi PDF** dari gambar tersebut menggunakan DomPDF agar dokumen tetap dapat diunduh/dicetak dalam format seragam.
- Untuk format dokumen kantor lain (DOC/DOCX/XLS/XLSX), file disimpan apa adanya tanpa versi PDF turunan.

Saat data diperbarui dengan lampiran baru, file lama dihapus otomatis dari storage untuk mencegah penumpukan file yatim (`deleteOldDocuments`). File dapat diunduh dengan nama deskriptif berbasis nomor agenda atau nama file asli, sesuai parameter query `name` pada rute unduh.

## Ekspor Laporan & Cetak

Setiap modul surat menyediakan:

- **Cetak** (`/surat-masuk/cetak`, `/surat-keluar/cetak`) — tampilan tabel bersih untuk dicetak langsung dari browser.
- **Ekspor PDF** — laporan PDF orientasi landscape yang dirender lewat DomPDF (`report-pdf.blade.php`).
- **Ekspor Excel (`.xlsx`)** dan **CSV** — dihasilkan lewat kelas `SuratMasukExport`/`SuratKeluarExport` menggunakan `maatwebsite/excel`, dengan header kolom dan styling baris judul.

Seluruh ekspor menghormati filter pencarian (`q`) yang sedang aktif pada daftar surat, dan diurutkan berdasarkan nomor agenda.

## Peta Rute Aplikasi

Seluruh rute didefinisikan pada `routes/web.php` sebagai rute web (server-rendered, bukan REST API JSON). Ringkasan rute utama beserta permission yang dibutuhkan:

**Autentikasi**

| Method | URI | Middleware |
|---|---|---|
| GET/POST | `/login` | `guest` |
| POST | `/logout` | `auth` |

**Surat Masuk** (prefix `/surat-masuk`, permission modul `surat_masuk`)

| Method | URI | Permission |
|---|---|---|
| GET | `/` | `view` |
| GET | `/tambah` | `create` |
| POST | `/` | `create` |
| GET | `/{suratMasuk}` | `view` |
| GET | `/{suratMasuk}/ubah` | `update` |
| PUT | `/{suratMasuk}` | `update` |
| DELETE | `/{suratMasuk}` | `delete` |
| DELETE | `/bulk-destroy` | `delete` |
| GET | `/{suratMasuk}/unduh` | `view` |
| GET | `/cetak`, `/ekspor/pdf`, `/ekspor/excel`, `/ekspor/csv` | `export` |

**Surat Keluar** (prefix `/surat-keluar`, permission modul `surat_keluar`) mengikuti pola rute yang sama seperti Surat Masuk.

**Administrasi**

| Method | URI | Permission |
|---|---|---|
| GET/POST/PUT/DELETE | `/pengguna...` | modul `users` |
| GET/POST/PUT/DELETE | `/wewenang...` | modul `roles` |
| GET, PUT | `/hak-akses` | modul `permissions` |

**Profil** (prefix `/profil`, memerlukan login)

| Method | URI | Fungsi |
|---|---|---|
| GET | `/profil` | Edit profil |
| PUT | `/profil` | Update data profil & foto |
| PUT | `/profil/ganti-sandi` | Ganti kata sandi |

## Testing

Proyek menggunakan **PHPUnit 10**. Saat ini repository hanya berisi test skeleton bawaan Laravel (`tests/Unit/ExampleTest.php` dan `tests/Feature/ExampleTest.php`) — belum ada test khusus untuk modul Surat Masuk, Surat Keluar, autentikasi, maupun permission.

Menjalankan seluruh test:

```bash
php artisan test
```

atau langsung melalui PHPUnit:

```bash
./vendor/bin/phpunit
```

## Build Frontend

- `npm run dev` — menjalankan Vite dalam mode development dengan hot module replacement.
- `npm run build` — build asset produksi (CSS Tailwind v4 + JS) ke folder `public/build`.

Styling menggunakan Tailwind CSS v4 yang dikonfigurasi lewat `@theme` pada `resources/css/app.css` dan plugin `@tailwindcss/postcss`. Palet warna kustom (primary, success, dsb.) didefinisikan pada `tailwind.config.js`. Interaksi UI (modal konfirmasi, dropdown, preview upload, grafik dashboard, dst.) ditulis sebagai modul JavaScript vanilla terpisah pada `resources/js/modules`.

## Deployment

Aplikasi dikemas menggunakan **Docker** dan dikonfigurasi untuk deploy ke **Railway** (`railway.toml` mengarah ke `Dockerfile`).

Ringkasan proses build image (`Dockerfile`):

1. Base image `php:8.2-apache` dengan ekstensi PHP yang dibutuhkan (`gd`, `zip`, `pdo`, `pdo_mysql`, `mbstring`, `exif`, `intl`, `bcmath`, `xml`, `opcache`).
2. Konfigurasi PHP produksi (opcache aktif, `upload_max_filesize=20M`, `post_max_size=25M`, `memory_limit=256M`).
3. Apache diarahkan ke `public/` sebagai document root dengan `mod_rewrite` aktif dan MPM Prefork.
4. `composer install --no-dev --optimize-autoloader` untuk dependency PHP produksi.
5. `npm ci && npm run build` untuk mengompilasi asset frontend, lalu `node_modules` dihapus dari image final.
6. Permission `storage/` dan `bootstrap/cache/` diatur agar dapat ditulis oleh `www-data`.

Saat container berjalan, `docker/start.sh` menjalankan urutan berikut secara otomatis:

1. Menyiapkan konfigurasi Apache (port dinamis dari variabel `PORT`, virtual host, MPM Prefork).
2. `php artisan storage:link` untuk menghubungkan storage publik.
3. `php artisan migrate --force` — proses deploy akan berhenti (`exit 1`) jika migrasi gagal.
4. Menjalankan `RoleSeeder`, `PermissionSeeder`, dan `UserSeeder` (idempotent melalui `firstOrCreate`, aman dijalankan berulang tiap deploy).
5. `php artisan optimize:clear`, `config:cache`, `route:cache`, `view:cache` untuk optimasi produksi.
6. Menjalankan Apache melalui `apache2-foreground`.

**Variabel environment yang wajib diset pada Railway (atau platform serupa):** `APP_KEY`, `APP_URL`, `APP_ENV=production`, `APP_DEBUG=false`, serta kredensial database MySQL (`DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). Platform seperti Railway umumnya menyuplai variabel `PORT` secara otomatis, yang sudah ditangani oleh `docker/start.sh`.

## Keamanan

Praktik keamanan yang sudah diterapkan pada source code:

- **Hashing password** menggunakan cast `hashed` bawaan Laravel (bcrypt) pada model `User`.
- **CSRF protection** aktif melalui middleware bawaan Laravel (`VerifyCsrfToken`) pada seluruh form.
- **Rate limiting login** untuk mencegah brute force (5 percobaan/5 menit per kombinasi login+IP).
- **Validasi request** terpusat melalui Form Request class dengan pesan error berbahasa Indonesia, termasuk validasi tipe dan ukuran file upload.
- **Otorisasi granular** berbasis permission per modul dan aksi, bukan sekadar pengecekan role tunggal.
- **Whitelist kolom sorting** pada query listing untuk mencegah manipulasi parameter query terhadap struktur SQL.
- **Soft delete** pada data sensitif (surat dan pengguna) sehingga penghapusan tidak langsung bersifat destruktif.
- **Session regeneration** saat login dan logout untuk mencegah session fixation.

Beberapa hal yang perlu diperhatikan sebelum penggunaan production:

- Ganti seluruh kata sandi akun demo dari `UserSeeder` (lihat [Database](#database)).
- Pastikan `APP_DEBUG=false` pada environment production agar detail error/stack trace tidak terekspos ke pengguna akhir.
- Batas upload file sebesar 100 MB pada validasi aplikasi lebih besar dari batas `upload_max_filesize`/`post_max_size` pada konfigurasi PHP produksi (20 MB/25 MB) pada `Dockerfile` — sesuaikan salah satu nilai agar konsisten sebelum digunakan pada skenario upload file besar.

## Troubleshooting

- **`SQLSTATE[HY000] [2002] Connection refused` saat migrasi** — periksa kembali `DB_HOST`, `DB_PORT`, dan pastikan service MySQL sudah berjalan, atau gunakan `DB_CONNECTION=sqlite` untuk pengembangan lokal.
- **File lampiran surat tidak dapat diakses / 404 pada `/storage/...`** — jalankan `php artisan storage:link` untuk membuat symbolic link dari `public/storage` ke `storage/app/public`.
- **Perubahan Blade/CSS/JS tidak muncul** — pastikan proses `npm run dev` berjalan di terminal terpisah selama development, atau jalankan `npm run build` setelah selesai mengubah asset.
- **`419 Page Expired` saat submit form** — biasanya disebabkan token CSRF kedaluwarsa akibat session timeout atau cache halaman lama; refresh halaman sebelum submit ulang.
- **`403 Forbidden` setelah login** — akun yang digunakan tidak memiliki permission untuk modul/aksi tersebut; periksa penetapan role pengguna dan matrix hak akses pada halaman `/hak-akses`.
- **Migrasi gagal pada deployment Railway** — perhatikan log `docker/start.sh`; skrip akan menghentikan proses boot jika `php artisan migrate --force` gagal, biasanya karena variabel koneksi database belum diset dengan benar pada environment production.

## Lisensi
Proyek ini dilisensikan di bawah MIT License — lihat file LICENSE untuk teks lengkapnya. Lisensi ini memberikan izin bebas untuk menggunakan, menyalin, memodifikasi, mengintegrasikan, mendistribusikan ulang, maupun mengembangkan lebih lanjut source code ini, termasuk untuk kebutuhan internal Inspektorat Daerah Samarinda maupun pihak lain, selama notice copyright dan izin (permission notice) pada file LICENSE tetap disertakan pada setiap salinan atau bagian substansial dari source code.

Perlu dicatat, metadata pada composer.json (nama paket laravel/laravel dan deskripsi skeleton) masih merupakan bawaan template Laravel dan belum disesuaikan dengan identitas proyek SiARSIP; hal ini tidak memengaruhi keberlakuan LICENSE, namun disarankan untuk diperbarui agar konsisten.
