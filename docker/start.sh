#!/bin/bash

echo "========================================="
echo "  SiARSIP — Railway Startup Script"
echo "========================================="

# 1. Konfigurasi Apache agar bind ke dynamic $PORT dari Railway (atau default 80)
PORT="${PORT:-80}"
echo "[Config] Mengarahkan Apache ke Port: ${PORT}"
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/*.conf

# 2. Buat symlink storage jika belum ada
echo "[1/5] Menghubungkan storage publik..."
php artisan storage:link --force 2>/dev/null || true

# 3. Jalankan migrasi database
echo "[2/5] Menjalankan migrasi database..."
php artisan migrate --force || true

# 4. Seed database (roles, permissions, default admin users)
echo "[3/5] Mengisi data awal (roles, permissions, admin)..."
php artisan db:seed --class=RoleSeeder --force 2>/dev/null || true
php artisan db:seed --class=PermissionSeeder --force 2>/dev/null || true
php artisan db:seed --class=UserSeeder --force 2>/dev/null || true

# 5. Refresh cache Laravel
echo "[4/5] Mengoptimalkan cache sistem..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "[5/5] Selesai."
echo "========================================="
echo "  Server SiARSIP siap. Menjalankan Apache di port ${PORT}..."
echo "========================================="

# Jalankan Apache di foreground
exec apache2-foreground
