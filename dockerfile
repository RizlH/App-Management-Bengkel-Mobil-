# 1. Pilih Base Image (PHP dengan Apache)
# Menggunakan versi PHP yang sesuai dengan kebutuhan CI4 (>= 7.2) dan Apache
FROM php:8.1-apache

# 2. Set Work Directory
# Direktori kerja di dalam container
WORKDIR /var/www/html

# 3. Install Ekstensi PHP yang Dibutuhkan (Contoh: mysql, gd)
# Sesuaikan dengan kebutuhan aplikasi CI4 Anda
RUN docker-php-ext-install pdo pdo_mysql mysqli gd

# 4. Copy File Aplikasi
# Salin seluruh file proyek CI4 ke dalam direktori kerja container
COPY . .

# 5. Atur Permissions (Opsional tapi penting)
# Berikan izin tulis ke direktori 'writable' agar CI4 bisa menyimpan cache, logs, dll.
RUN chown -R www-data:www-data /var/www/html/writable /var/www/html/public/assets
RUN chmod -R 775 /var/www/html/writable /var/www/html/public/assets

# 6. Install Composer
# Unduh dan install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 7. Install Dependensi Proyek (Composer)
# Jalankan 'composer install' untuk mengunduh dependensi CI4
RUN composer install --no-dev --optimize-autoloader

# 8. Expose Port Apache
# Beri tahu Docker bahwa container akan mendengarkan di port 80
EXPOSE 80

# 9. Jalankan Server Web (Apache)
# Perintah default untuk memulai Apache saat container dijalankan
CMD ["apache2-foreground"]
