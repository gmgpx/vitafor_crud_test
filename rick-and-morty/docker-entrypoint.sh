#!/bin/bash

cd /var/www

# Copia .env se não existir
[ -f .env ] || cp .env.example .env

# Gera chave se não existir
php artisan key:generate --force

# Migra o banco
php artisan migrate --force

# Inicia o PHP-FPM
exec php-fpm
