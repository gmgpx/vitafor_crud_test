FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip sqlite3 libsqlite3-dev libzip-dev nodejs npm \
    && docker-php-ext-install pdo pdo_sqlite zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install && npm run build && ls -la /var/www/public/build/assets/

RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["php-fpm"]
