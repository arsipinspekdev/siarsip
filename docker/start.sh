#!/bin/bash
set -e

echo "========================================="
echo "  SiARSIP — Railway Production Boot"
echo "========================================="

# 1. Tentukan Port Tunggal (Railway menyuplai $PORT, default 8080)
PORT="${PORT:-8080}"
echo "[Config] Apache listening on Port: ${PORT}"

# 2. Source Apache envvars dan siapkan direktori runtime
. /etc/apache2/envvars 2>/dev/null || true
export PORT=${PORT}
echo "export PORT=${PORT}" >> /etc/apache2/envvars

mkdir -p ${APACHE_RUN_DIR:-/var/run/apache2} ${APACHE_LOCK_DIR:-/var/lock/apache2} ${APACHE_LOG_DIR:-/var/log/apache2}
rm -f ${APACHE_PID_FILE:-/var/run/apache2/apache2.pid} /var/run/apache2/apache2.pid 2>/dev/null || true

# 3. Tulis ports.conf dan ServerName global (menghilangkan notice AH00558)
echo "ServerName localhost" >> /etc/apache2/apache2.conf
echo "Listen ${PORT}" > /etc/apache2/ports.conf

# 4. Tulis VirtualHost 000-default.conf yang valid
cat <<EOF > /etc/apache2/sites-available/000-default.conf
<VirtualHost *:${PORT}>
    ServerName localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# 5. FIX MPM: Pastikan hanya single mpm_prefork yang aktif
rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf 2>/dev/null || true
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

a2enmod rewrite 2>/dev/null || true
a2ensite 000-default.conf 2>/dev/null || true

# Test syntax Apache
echo "[Apache] Menjalankan config test..."
apache2ctl -t

# 6. Buat symlink storage publik
echo "[1/4] Menghubungkan storage publik..."
php artisan storage:link --force 2>/dev/null || true

# 7. Jalankan Migrasi & Seed Database
echo "[2/4] Menjalankan migrasi database..."
php artisan migrate --force || { echo "MIGRASI GAGAL! Periksa koneksi database Anda."; exit 1; }

echo "[3/4] Mengisi data awal akun admin & roles..."
php artisan db:seed --class=RoleSeeder --force 2>/dev/null || true
php artisan db:seed --class=PermissionSeeder --force 2>/dev/null || true
php artisan db:seed --class=UserSeeder --force 2>/dev/null || true

# 8. Optimalkan cache Laravel
echo "[4/4] Mengoptimalkan cache sistem..."
php artisan optimize:clear 2>/dev/null || true
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

echo "========================================="
echo "  Server SiARSIP siap. Menjalankan Apache di port ${PORT}..."
echo "========================================="

# Jalankan Apache resmi dengan apache2-foreground
exec apache2-foreground
