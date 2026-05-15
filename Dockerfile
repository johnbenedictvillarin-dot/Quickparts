FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    libpng-dev \
    libxml2-dev \
    libonig-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN echo '[www]' > /usr/local/etc/php-fpm.d/zz-docker.conf && echo 'listen = 127.0.0.1:9000' >> /usr/local/etc/php-fpm.d/zz-docker.conf

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN chmod -R 777 storage bootstrap/cache

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

ENTRYPOINT ["/start.sh"]
