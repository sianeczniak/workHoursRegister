FROM php:8.2-fpm

# Ustawienie katalogu roboczego
WORKDIR /var/www/html

# Skopiowanie aplikacji do kontenera
COPY . /var/www/html/

# Skopiowanie konfiguracji php.ini
COPY ./php/php.ini /usr/local/etc/php/

# Instalacja wymaganych rozszerzeń
RUN docker-php-ext-install pdo pdo_mysql

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/tmp/.composer
# Instalacja Composer
RUN mkdir -p /tmp/.composer && \
    chown -R www-data:www-data /tmp/.composer && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/vendor && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Otwarte porty
EXPOSE 9000

# Uruchomienie PHP-FPM
CMD ["php-fpm"]