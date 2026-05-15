#!/bin/bash
set -e

PORT="${PORT:-8080}"
echo "Configuring Apache to listen on port $PORT..."

echo "Listen $PORT" > /etc/apache2/ports.conf

cat > /etc/apache2/sites-available/000-default.conf <<EOF
<VirtualHost *:$PORT>
    DocumentRoot /app/public

    <Directory /app/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

a2ensite 000-default > /dev/null 2>&1 || true

echo "Apache configured for port $PORT with DocumentRoot /app/public"
exec "$@"
