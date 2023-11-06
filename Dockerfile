FROM php:8.2-apache

RUN apt -y update \
    && apt -y install git unzip \
    && apt -y clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm -rf /var/cache/apk/*

COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

COPY . /opt/app
WORKDIR /opt/app

RUN composer install --no-interaction --optimize-autoloader


RUN rm -drf /var/www/html \
    && ln -s /opt/app/public /var/www/html \
    && chown -R www-data:www-data /opt/app/var \
    && a2enmod rewrite

RUN bin/console doctrine:migrations:migrate --no-interaction
