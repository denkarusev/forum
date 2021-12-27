FROM php:7.4-fpm

ARG UID
ARG GID
RUN usermod -u ${UID} www-data
RUN groupmod -g ${GID} www-data

# Install dependencies
RUN apt-get update && apt-get install -y \
        autoconf \
        make \
        git \
        vim \
        zip \
        libzip-dev \
        unzip \
        openssl \
        curl \
        imagemagick \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

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
    ctype \
    xml

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

# install and enable xdebug
RUN pecl install xdebug-2.9.7 \
	&& docker-php-ext-enable xdebug
