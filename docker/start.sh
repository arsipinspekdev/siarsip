#!/bin/bash

# 1. Konfigurasi Port Apache agar mendengarkan di port 80 DAN port $PORT dari Railway
PORT="${PORT:-80}"
echo "========================================="
echo "  SiARSIP Web Server"
echo "  Listening on Port: 80 and ${PORT}"
echo "========================================="

echo "Listen 80" > /etc/apache2/ports.conf
if [ "$PORT" != "80" ]; then
    echo "Listen ${PORT}" >> /etc/apache2/ports.conf
fi

# Buat VirtualHost menerima traffic dari port manapun (*:*)
sed -i 's/<VirtualHost [^>]*>/<VirtualHost *:*>/g' /etc/apache2/sites-available/*.conf

# 2. Symlink storage
php artisan storage:link --force 2>/dev/null || true

# 3. Clear cache lama agar konfigurasi .env Railway langsung terbaca
php artisan optimize:clear 2>/dev/null || true

# 4. Jalankan Migrasi & Seed Database di background agar Apache bisa langsung menyala seketika
(
    sleep 3
    echo "[DB] Memulai migrasi database..."
    php artisan migrate --force 2>&1 || true
    echo "[DB] Memasukkan data awal (admin & roles)..."
    php artisan db:seed --class=RoleSeeder --force 2>&1 || true
    php artisan db:seed --class=PermissionSeeder --force 2>&1 || true
    php artisan db:seed --class=UserSeeder --force 2>&1 || true
    echo "[DB] Database setup selesai!"
) &

# 5. Jalankan Apache di foreground segera (Health check Railway akan langsung PASS)
exec apache2-foreground
