FROM php:7.4-fpm-alpine

RUN apk update; \
    apk upgrade;
RUN apk add jpeg-dev libpng-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install mysqli