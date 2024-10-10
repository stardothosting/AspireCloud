# Dockerfile for the PHP container
FROM php:8.3-fpm

MAINTAINER Sarah Savage

# Update and install required dependencies and extensions
RUN apt-get update \
    && apt-get install -y \
        libpq-dev \
        git \
        zip \
        libzip-dev \
        libicu-dev \
        postgresql-client \
        mariadb-client \ 
        mariadb-dev

# Install PHP extensions
RUN docker-php-ext-install pdo \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-install intl \
    && pecl install xdebug-3.3.0 \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable pdo_pgsql \
    && docker-php-ext-enable pdo_mysql \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy Composer to the correct path (if needed)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www
