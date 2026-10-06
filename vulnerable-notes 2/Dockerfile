FROM php:8.3-apache

RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite

COPY docker/apache.conf /etc/apache2/conf-available/vulnerable-notes.conf
RUN a2enconf vulnerable-notes
COPY --chown=www-data:www-data . /var/www/html

WORKDIR /var/www/html
