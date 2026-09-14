FROM php:8.2-cli

# Install sistem dependency & ekstensi PHP yang dibutuhkan Laravel dan MySQL
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur direktori kerja
WORKDIR /app

# Salin semua file dari repository lokal ke dalam container
COPY . .

# Install dependency PHP & Node.js, lalu build aset frontend (Vite/Tailwind)
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Berikan hak akses untuk folder storage dan cache agar Laravel bisa menulis log/sesi
RUN chown -R www-data:www-data storage bootstrap/cache

# Buka port 8000
EXPOSE 8000

# Jalankan server bawaan Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]