FROM php:8.2-apache

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Configure Apache to use Railway's PORT
ENV PORT=8080
EXPOSE ${PORT}

# Custom entrypoint to use Railway's port
RUN echo '#!/bin/bash\n\
sed -i "s/80/${PORT:-80}/g" /etc/apache2/ports.conf\n\
sed -i "s/80/${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf\n\
apache2-foreground' > /start.sh

RUN chmod +x /start.sh

ENTRYPOINT ["/start.sh"]