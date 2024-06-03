FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \
  && docker-php-ext-install pdo_mysql zip

# imagemagickに関連するパッケージの追加
RUN apt-get update && \
  apt-get install -y --no-install-recommends \
  libpng-dev \
  libjpeg-dev \
  libfreetype6-dev \
  imagemagick \
  libmagickwand-dev \
  && rm -rf /var/lib/apt/lists/*

# Imagick拡張機能のインストール
RUN pecl install imagick && \
  docker-php-ext-enable imagick

# GD拡張機能のインストール
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
  docker-php-ext-install -j$(nproc) gd

# PHP設定の更新
RUN echo "extension=imagick.so" >> /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

WORKDIR /var/www