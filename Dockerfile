FROM dunglas/frankenphp:1-php8.2-alpine AS base

RUN install-php-extensions pdo_mysql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

ENV APP_ENV=production

CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
