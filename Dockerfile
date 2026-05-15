FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev \
    && docker-php-ext-install pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN mkdir -p storage/framework/views bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Debug: Show PHP and artisan versions
RUN php --version
RUN php artisan --version

EXPOSE ${PORT:-8080}

CMD ["sh", "-c", "echo 'Starting server on port ${PORT:-8080}' && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]