FROM php:8.3-apache

RUN docker-php-ext-install pdo_mysql
RUN a2enmod headers \
    && sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!<Directory /var/www/>!<Directory /var/www/html/public>!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY docker/apache-security.conf /etc/apache2/conf-available/zz-app-security.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
RUN a2enconf zz-app-security

COPY --chown=www-data:www-data src ./src
COPY --chown=www-data:www-data views ./views
COPY --chown=www-data:www-data public ./public

EXPOSE 80
