FROM php:8.4-fpm

# Устанавливаем зависимости и расширения
RUN apt-get -o Acquire::Check-Valid-Until=false update && apt-get install -y \
    libzip-dev zip unzip libonig-dev libpng-dev libxml2-dev libcurl4-openssl-dev tzdata \
    && ln -snf /usr/share/zoneinfo/Europe/Moscow /etc/localtime && echo "Europe/Moscow" > /etc/timezone \
    && docker-php-ext-install pdo pdo_mysql zip mbstring bcmath exif pcntl gd xml curl \
    && pecl install redis \
    && docker-php-ext-enable redis

# Настраиваем рабочую директорию
WORKDIR /var/www/laravel

# Назначаем права на нужные директории
RUN chown -R www-data:www-data /var/www/laravel/storage /var/www/laravel/bootstrap/cache \
    && chmod -R 775 /var/www/laravel/storage /var/www/laravel/bootstrap/cache

# По умолчанию запускаем PHP-FPM
CMD ["php-fpm"]
