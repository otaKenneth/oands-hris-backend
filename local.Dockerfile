FROM php:8.5-cli

RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    nodejs \
    npm \
    default-mysql-server \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app

WORKDIR /app

RUN rm -rf bootstrap/cache/*.php

RUN composer install --prefer-dist --no-interaction

CMD php artisan serve --env=local --port=8000 --host=0.0.0.0