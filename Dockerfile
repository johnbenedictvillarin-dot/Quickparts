FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN cp .env.example .env && composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

EXPOSE ${PORT:-8080}

CMD ["sh", "-c", "php artisan migrate --force 2>/dev/null || true && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
