# ============================================================
# Dockerfile untuk Laravel (Railway Deployment)
# PHP 8.2 + Apache + Node.js (untuk Vite build)
# ============================================================

FROM php:8.2-apache

# -------------------------------------------
# 1. Install system dependencies
# -------------------------------------------
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# -------------------------------------------
# 2. Install & configure PHP extensions
# -------------------------------------------
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        zip \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        intl \
        bcmath \
        xml \
        opcache

# -------------------------------------------
# 3. PHP production config
# -------------------------------------------
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "upload_max_filesize=20M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size=25M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/custom.ini

# -------------------------------------------
# 4. Install Composer
# -------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -------------------------------------------
# 5. Configure Apache
# -------------------------------------------
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && printf "<Directory /var/www/html/public>\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>\n" >> /etc/apache2/apache2.conf

# Enable mod_rewrite (required for Laravel)
RUN a2enmod rewrite

# -------------------------------------------
# 6. Set working directory & copy project
# -------------------------------------------
WORKDIR /var/www/html

COPY . .

# -------------------------------------------
# 7. Install PHP dependencies (no dev)
# -------------------------------------------
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress

# -------------------------------------------
# 8. Install Node deps & build frontend assets
# -------------------------------------------
RUN npm ci && npm run build && rm -rf node_modules

# -------------------------------------------
# 9. Fix permissions
# -------------------------------------------
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# -------------------------------------------
# 10. Copy and set startup script
# -------------------------------------------
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
