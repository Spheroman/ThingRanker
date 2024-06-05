
FROM composer:lts as deps

WORKDIR /app
RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=bind,source=composer.lock,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction

FROM php:8.3.7-apache as final

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --from=deps app/vendor/ /var/www/html/vendor
COPY . /var/www/html

RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql
RUN --host=0.0.0.0

USER www-data
