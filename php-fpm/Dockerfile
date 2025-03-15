FROM php:8.3-fpm-alpine

# Install dependencies
RUN apk update && apk add \
    libzip-dev \
    zip \
    unzip \
    git

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Install PHPMailer
RUN mkdir -p /var/www/vendor && \
    cd /var/www/vendor && \
    composer require phpmailer/phpmailer

# Copy composer.json if you have one
# COPY composer.json composer.lock ./
# RUN composer install --no-scripts --no-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/
