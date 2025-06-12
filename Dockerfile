# Sử dụng image PHP 8.2 với Apache
FROM php:8.2-fpm

# Cài đặt các package cần thiết cho PHP và GD extension
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Sao chép mã nguồn vào thư mục web của Apache
COPY . /var/www/html/

# Cài đặt các dependencies PHP cho ứng dụng Laravel
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Mở port 9000
EXPOSE 9000

CMD ["php-fpm"]

