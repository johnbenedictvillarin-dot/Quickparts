FROM php:8.2-fpm

RUN apt-get update && apt-get install -y nginx libpng-dev libzip-dev libonig-dev libxml2-dev && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql mbstring gd zip xml

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN cp .env.example .env && composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN chmod -R 777 storage bootstrap/cache

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
