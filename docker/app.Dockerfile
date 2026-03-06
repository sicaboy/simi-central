FROM unit:php8.3
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