FROM php:8.2-apache

# 1. Install library
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql

# 2. Aktifkan Mod Rewrite (WAJIB untuk Routing Laravel)
# --- BARIS BARU DI SINI ---
RUN a2enmod rewrite
# --------------------------

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set folder kerja
WORKDIR /var/www/html

# 5. Copy semua file
COPY . .

# 6. Set permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Install dependency
RUN composer install --no-dev --optimize-autoloader

# 8. Atur Apache ke folder public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# 9. PENTING: Izinkan .htaccess (Override All)
# Kita update config apache agar membaca file .htaccess Laravel
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# 10. CMD untuk start server
CMD sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && docker-php-entrypoint apache2-foreground