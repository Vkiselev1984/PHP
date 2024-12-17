FROM php:8.2-fpm

COPY ./php.ini /usr/local/etc/php/conf.d/php-custom.ini

# Установка необходимых пакетов
RUN apt-get update && \
    apt-get install -y --no-install-recommends zip unzip libpq-dev libmemcached-dev zlib1g-dev && \
    apt-get install -y --no-install-recommends netcat-traditional iputils-ping && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка расширения Memcached
RUN apt-get update && \
    apt-get install -y --no-install-recommends libmemcached-dev && \
    pecl install memcached && \
    docker-php-ext-enable memcached

# Установка расширений PDO
RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /data/mysite.local

VOLUME /data/mysite.local

CMD ["php-fpm"]