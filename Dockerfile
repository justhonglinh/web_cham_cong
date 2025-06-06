# Sử dụng image PHP 8.0 với Apache
FROM php:8.0-apache

# Cài đặt các package cần thiết cho PHP và GD extension
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Sao chép mã nguồn vào thư mục web của Apache
COPY . /var/www/html/

# Cài đặt các dependencies PHP cho ứng dụng Laravel
WORKDIR /var/www/html
RUN composer install --no-interaction

# Mở port 80
EXPOSE 80

# Chạy Apache trong background
CMD ["apache2-foreground"]

