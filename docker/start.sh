#!/bin/bash
set -e

echo "========================================="
echo "  SiARSIP — Railway Startup Script"
echo "========================================="

# Buat symlink storage jika belum ada
echo "[1/6] Menghubungkan storage publik..."
php artisan storage:link --force 2>/dev/null || true

# Jalankan migrasi database
echo "[2/6] Menjalankan migrasi database..."
php artisan migrate --force

# Seed database (hanya jika tabel users masih kosong)
echo "[3/6] Mengisi data awal (roles, permissions, admin)..."
php artisan db:seed --class=RoleSeeder --force 2>/dev/null || true
php artisan db:seed --class=PermissionSeeder --force 2>/dev/null || true
php artisan db:seed --class=UserSeeder --force 2>/dev/null || true

# Cache config, routes, views
echo "[4/6] Meng-cache konfigurasi..."
php artisan config:cache

echo "[5/6] Meng-cache routes..."
php artisan route:cache

echo "[6/6] Meng-cache views..."
php artisan view:cache

echo "========================================="
echo "  Server siap. Menjalankan Apache..."
echo "========================================="

# Jalankan Apache di foreground
exec apache2-foreground
