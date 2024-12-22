FROM php:8.3-fpm
WORKDIR /var/www
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-install pdo_mysql gd
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache
EXPOSE 9000
CMD ["php-fpm"]
