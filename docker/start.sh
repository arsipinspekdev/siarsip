#!/bin/bash

echo "========================================="
echo "  SiARSIP — Railway Production Boot"
echo "========================================="

# 1. Konfigurasi Port Apache ke PORT dari Railway
PORT="${PORT:-8080}"
echo "[Config] Apache listening on port: ${PORT}"
echo "Listen ${PORT}" > /etc/apache2/ports.conf
sed -ri -e "s!<VirtualHost .*>!<VirtualHost \*:${PORT}>!g" /etc/apache2/sites-available/*.conf

# 2. Pastikan hanya single MPM yang aktif (Mencegah error AH00534)
rm -f /etc/apache2/mods-enabled/mpm_event.load /etc/apache2/mods-enabled/mpm_event.conf 2>/dev/null || true
rm -f /etc/apache2/mods-enabled/mpm_worker.load /etc/apache2/mods-enabled/mpm_worker.conf 2>/dev/null || true
a2enmod mpm_prefork rewrite 2>/dev/null || true

# 3. Buat symlink storage publik
echo "[1/4] Menghubungkan storage publik..."
php artisan storage:link --force 2>/dev/null || true

# 4. Jalankan Migrasi & Seed Database
echo "[2/4] Menjalankan migrasi database..."
php artisan migrate --force || true

echo "[3/4] Mengisi data awal akun admin & roles..."
php artisan db:seed --class=RoleSeeder --force 2>/dev/null || true
php artisan db:seed --class=PermissionSeeder --force 2>/dev/null || true
php artisan db:seed --class=UserSeeder --force 2>/dev/null || true

# 5. Optimalkan cache Laravel
echo "[4/4] Mengoptimalkan cache sistem..."
php artisan optimize:clear 2>/dev/null || true
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

echo "========================================="
echo "  Server SiARSIP siap. Menjalankan Apache..."
echo "========================================="

# Jalankan Apache di foreground
exec apache2-foreground
