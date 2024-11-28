FROM php:8.2-apache

RUN a2enmod rewrite && \
    apt-get update && \
    apt-get install zip unzip && \
    docker-php-ext-install pdo_mysql mysqli
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./entrypoint/docker-php-entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod 755 /usr/local/bin/docker-php-entrypoint
