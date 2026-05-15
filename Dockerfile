FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Apache listens on port 80, but Railway uses $PORT
# Create a start script that uses Railway's $PORT
RUN echo '#!/bin/bash\n\
sed -i "s/80/${PORT:-80}/g" /etc/apache2/ports.conf\n\
sed -i "s/80/${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf\n\
apache2-foreground' > /start.sh

RUN chmod +x /start.sh

EXPOSE ${PORT:-80}

ENTRYPOINT ["/start.sh"]