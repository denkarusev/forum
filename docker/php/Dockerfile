FROM php:7.3.13-fpm

ARG UID
ARG GID
RUN usermod -u ${UID} www-data
RUN groupmod -g ${GID} www-data

# Install dependencies
RUN apt-get -qq update && apt-get -qq install -y \
    autoconf \
    cmake \
    file \
    g++ \
    gcc \
    libc-dev \
    make \
    pkgconf \
    re2c \
    build-essential \
    libmcrypt-dev \
    libpq-dev \
    git \
    vim \
    zip \
    unzip \
    openssl \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    sqlite3 \
    jpegoptim optipng pngquant gifsicle \
    libsqlite3-dev  \
    curl \
    nano \
    libxml2-dev \
    imagemagick

RUN apt-get update -yqq && \
    apt-get install -y apt-utils

# Install extensions
RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    zip \
    mbstring \
    tokenizer \
    bcmath \
    xml

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
