FROM php:8.4-fpm
ENV LANG C.UTF-8
EXPOSE 80

RUN apt update
RUN apt install -y \
        g++ \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        libxml2-dev \
        zip \
        zlib1g-dev \
        libhiredis-dev

RUN docker-php-ext-install \
        intl \
        opcache \
        pdo \
        pcntl \
        pdo_mysql \
        zip \
        bcmath \
        soap

RUN pecl install redis \
    && docker-php-ext-enable redis

WORKDIR /var/www/html

# Serve HTTP through Nginx and execute PHP through PHP-FPM.
RUN apt-get update && apt-get install -y --no-install-recommends nginx \
    && rm -rf /var/lib/apt/lists/* \
    && rm -f /etc/nginx/sites-enabled/default
COPY docker/http-nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/start-web.sh /usr/local/bin/start-web
COPY docker/php.ini /usr/local/etc/php/conf.d/extra-php-config.ini
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-web.conf
RUN chmod 755 /usr/local/bin/start-web
STOPSIGNAL SIGTERM
CMD ["/usr/local/bin/start-web"]
