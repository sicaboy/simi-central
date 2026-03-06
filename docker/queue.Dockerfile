FROM php:8.3-cli
ENV LANG C.UTF-8

RUN apt update
RUN apt install -y \
        g++ \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        libxml2-dev \
        zip \
        unzip \
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
COPY ./docker/composer/* /root/.composer/
COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod 777 /entrypoint.sh

ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_ALLOW_SUPERUSER=1
# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENTRYPOINT ["/entrypoint.sh"]
