FROM php:8.1-fpm

RUN apt-get update && \
    apt-get install -y \
        apt-utils \
        libpq-dev \
        libpng-dev \
        libzip-dev \
        zip \
        unzip \
        git && \
    docker-php-ext-install bcmath gd zip && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin --no-dev

RUN composer clear-cache

WORKDIR /var/www


