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

# 3. Tulis VirtualHost 000-default.conf yang bersih
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

# 4. FIX MUTLAK ERROR AH00534 (Multiple MPM):
# Hapus semua MPM yang aktif di mods-enabled, lalu link HANYA mpm_prefork
rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf 2>/dev/null || true
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

# Pastikan rewrite aktif
a2enmod rewrite 2>/dev/null || true
a2ensite 000-default.conf 2>/dev/null || true

# Test syntax Apache
echo "[Apache] Menjalankan config test..."
apache2ctl -t

# 5. Buat symlink storage publik
echo "[1/4] Menghubungkan storage publik..."
php artisan storage:link --force 2>/dev/null || true

# 6. Jalankan Migrasi & Seed Database
echo "[2/4] Menjalankan migrasi database..."
php artisan migrate --force || true

echo "[3/4] Mengisi data awal akun admin & roles..."
php artisan db:seed --class=RoleSeeder --force 2>/dev/null || true
php artisan db:seed --class=PermissionSeeder --force 2>/dev/null || true
php artisan db:seed --class=UserSeeder --force 2>/dev/null || true

# 7. Optimalkan cache Laravel
echo "[4/4] Mengoptimalkan cache sistem..."
php artisan optimize:clear 2>/dev/null || true
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

echo "========================================="
echo "  Server SiARSIP siap. Menjalankan Apache..."
echo "========================================="

# Jalankan Apache di foreground
exec apache2 -DFOREGROUND
