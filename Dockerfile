FROM php:8.2-apache

# Install library yang dibutuhkan (termasuk driver untuk Supabase/Postgres)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set folder kerja
WORKDIR /var/www/html

# Copy semua file project
COPY . .

# Set permission folder storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install dependency Laravel
RUN composer install --no-dev --optimize-autoloader

# Atur Apache agar membaca folder public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Ubah port agar sesuai environment Render
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80