FROM php:7.3.13-fpm

# Set working directory
ARG WORKING_DIR
WORKDIR ${WORKING_DIR}

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

# Clear cache
RUN rm -rf /var/lib/apt/lists/*

RUN apt-get update -yqq && \
    apt-get install -y apt-utils

# Install extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql zip mbstring tokenizer bcmath
RUN docker-php-ext-install -j$(nproc) iconv
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

# Clean
RUN apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/*

# Add user for laravel application
ARG CURRENT_USER
ARG CURRENT_GROUP

RUN groupadd -g ${CURRENT_GROUP} www
RUN useradd -u ${CURRENT_USER} -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . ${WORKING_DIR}

# Copy existing application directory permissions
COPY --chown=www:www . ${WORKING_DIR}
