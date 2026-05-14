#!/bin/bash
set -e

PORT="${PORT:-8080}"
echo "Starting server on port $PORT"

cp /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default 2>/dev/null || true
mkdir -p /etc/nginx/sites-enabled
cat > /etc/nginx/sites-enabled/default <<EOF
server {
    listen $PORT default_server;
    root /app/public;
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \\.php\$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }
}
EOF

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
nginx -g "daemon off;"
