#!/bin/bash

echo "========================================="
echo "  SiARSIP — Railway Production Boot"
echo "========================================="

# 1. Tentukan Port
PORT="${PORT:-8080}"
echo "[Config] Apache listening on Port: ${PORT} and Port: 80"
echo "export PORT=${PORT}" >> /etc/apache2/envvars

# 2. Tulis file ports.conf yang bersih
cat <<EOF > /etc/apache2/ports.conf
Listen ${PORT}
Listen 80
EOF

# 3. Tulis VirtualHost 000-default.conf yang bersih dan valid
cat <<EOF > /etc/apache2/sites-available/000-default.conf
<VirtualHost *:${PORT} *:80>
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

# Pastikan konfigurasi aktif
a2ensite 000-default.conf 2>/dev/null || true
a2enmod mpm_prefork rewrite 2>/dev/null || true

# 4. Buat symlink storage publik
echo "[1/4] Menghubungkan storage publik..."
php artisan storage:link --force 2>/dev/null || true

# 5. Jalankan Migrasi & Seed Database
echo "[2/4] Menjalankan migrasi database..."
php artisan migrate --force || true

echo "[3/4] Mengisi data awal akun admin & roles..."
php artisan db:seed --class=RoleSeeder --force 2>/dev/null || true
php artisan db:seed --class=PermissionSeeder --force 2>/dev/null || true
php artisan db:seed --class=UserSeeder --force 2>/dev/null || true

# 6. Optimalkan cache Laravel
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
