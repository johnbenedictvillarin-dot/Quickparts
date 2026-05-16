FROM dunglas/frankenphp:1-php8.2-alpine

RUN install-php-extensions pdo_mysql

COPY . /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN cd /app && composer install --no-dev --optimize-autoloader --ignore-platform-reqs 2>&1 || true

ENV SERVER_NAME=:8080
ENV APP_ENV=production

WORKDIR /app

CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
