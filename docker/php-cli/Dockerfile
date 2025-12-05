FROM php:8.3-cli-alpine3.22

ARG ENV

RUN set -ex \
    && apk update \
    && apk add --no-cache --virtual .software \
        bash \
        libzip-dev \
        curl-dev \
        libxml2-dev \
    && apk add --no-cache --virtual .deps-build-common \
        $PHPIZE_DEPS \
        $BUILD_DEPENDENSIES \
        tzdata \
        linux-headers \
    && apk add --no-cache --virtual .deps-build-intl \
        icu-dev \
    && apk add --no-cache --virtual .deps-build-gd \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libwebp-dev \
    && apk add --no-cache --virtual .deps-build-zip \
        libzip-dev \
    && apk add --no-cache --virtual .deps-build-mcrypt \
        libmcrypt-dev \
    && apk add --no-cache --virtual .deps-intl \
        icu-libs \
    && apk add --no-cache --virtual .deps-gd \
        freetype \
        libjpeg-turbo \
        libpng \
        libwebp \
    && apk add --no-cache --virtual .deps-zip \
        libzip \
    && apk add --no-cache --virtual .deps-mcrypt \
        libmcrypt \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install bcmath pdo_mysql intl opcache pcntl gd exif curl zip \
    && pecl config-set php_ini /usr/local/etc/php/php.ini \
    && pecl install apcu \
    && pecl install xdebug \
    && pecl install redis \
    && docker-php-ext-enable apcu \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable redis \
    && apk del \
        .deps-build-common \
        .deps-build-intl \
        .deps-build-gd \
        .deps-build-zip \
        .deps-build-mcrypt \
    && rm -rf /var/cache/apk/* /var/tmp/* /tmp/*


COPY config/php.ini /usr/local/etc/php/conf.d/php.ini
COPY bash/.bashrc /.bashrc

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer\
    && mkdir -m 777 /.composer

WORKDIR /var/www
