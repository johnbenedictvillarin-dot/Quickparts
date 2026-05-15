FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Create start script that uses Railway's PORT
RUN echo '#!/bin/bash\n\
PORT=${PORT:-8080}\n\
sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf\n\
sed -i "s/80/${PORT}/g" /etc/apache2/sites-available/000-default.conf\n\
apache2-foreground' > /start.sh

RUN chmod +x /start.sh

EXPOSE ${PORT:-8080}

ENTRYPOINT ["/start.sh"]