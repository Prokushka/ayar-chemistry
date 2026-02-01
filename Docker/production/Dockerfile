FROM php:8.4-fpm AS composer

RUN docker-php-ext-install pcntl

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    unzip \
    libpq-dev \
    libonig-dev \
    libssl-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libpng16-16 \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    opcache \
    intl \
    zip \
    gd \
    bcmath \
    soap \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www/laravel

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist

FROM debian AS frontend

# Install Node.js and build tools
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    nodejs \
    npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set working directory
WORKDIR /var/www/laravel

# Copy Laravel application code
COPY . /var/www/laravel

# Install Node.js dependencies and build assets
RUN npm install
RUN npm run build:csr
RUN npm run build:ssr

RUN ls -la public/build
RUN find /var/www/laravel -name "manifest.json"
# Stage 3: Production image
FROM php:8.4-fpm AS php

RUN docker-php-ext-install pcntl

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    supervisor \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libpng16-16 \
    libfcgi-bin \
    procps \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && docker-php-ext-install gd

# Download and install php-fpm health check script
RUN curl -o /usr/local/bin/php-fpm-healthcheck \
    https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/master/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck

# Copy the initialization script
COPY ./Docker/production/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./Docker/production/supervisor/horizon.conf /etc/supervisor/conf.d/horizon.conf
COPY ./Docker/production/supervisor/php-fpm.conf /etc/supervisor/conf.d/php-fpm.conf

COPY ./Docker/production/php-fpm/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy the initial storage structure
COPY ./storage /var/www/laravel/storage-init

COPY --from=composer /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=composer /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=composer /usr/local/bin/docker-php-ext-* /usr/local/bin/
COPY --from=composer /usr/local/bin/composer /usr/local/bin/composer

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Enable PHP-FPM status page by modifying zz-docker.conf with sed
RUN sed -i '/\[www\]/a pm.status_path = /status' /usr/local/etc/php-fpm.d/zz-docker.conf
# Update the variables_order to include E (for ENV)
#RUN sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/' "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/laravel

# Копируем зависимости и исходники из composer-стадии
COPY --from=composer /var/www/laravel /var/www/laravel
COPY --from=frontend /var/www/laravel/public /var/www/laravel/public
# Копируем собранные ассеты из frontend-стадии

RUN chown -R www-data:www-data /var/www/laravel

USER www-data

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]


# Stage 4: nginx

FROM nginx:alpine AS nginx

COPY ./Docker/production/nginx/nginx.conf /etc/nginx/nginx.conf

COPY --from=frontend /var/www/laravel/public /var/www/laravel/public

WORKDIR /var/www/laravel/public

CMD ["nginx", "-g", "daemon off;"]

