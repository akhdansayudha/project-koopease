FROM php:8.2-apache

# [cite_start]1. Install library (Tambahkan git & curl agar Composer lancar) [cite: 1]
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql

# [cite_start]2. Install Composer [cite: 1]
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# [cite_start]3. Set folder kerja [cite: 1]
WORKDIR /var/www/html

# [cite_start]4. Copy file project (Perhatikan titiknya ada dua: "COPY . .") [cite: 1, 2]
COPY . .

# [cite_start]5. Set permission (Penting untuk Laravel) [cite: 1]
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# [cite_start]6. Install dependency (Production mode) [cite: 1]
RUN composer install --no-dev --optimize-autoloader

# [cite_start]7. Atur Apache ke folder public [cite: 1]
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# 8. PERBAIKAN UTAMA: Mengatur PORT saat container berjalan (Runtime)
# Kita hapus perintah "RUN sed..." yang lama, dan ganti dengan script ini:
CMD sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && docker-php-entrypoint apache2-foreground