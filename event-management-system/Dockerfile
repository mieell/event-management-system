FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y git unzip libicu-dev libzip-dev \
    && docker-php-ext-install intl mysqli pdo_mysql zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . /var/www/html

WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data writable \
    && chmod -R 775 writable \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80
