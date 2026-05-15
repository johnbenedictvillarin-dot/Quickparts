#!/bin/bash
PORT="${PORT:-8080}"

echo "[www]
listen = 127.0.0.1:9000
" > /usr/local/etc/php-fpm.d/zz-custom.conf

rm -f /etc/nginx/conf.d/default.conf

mkdir -p /etc/nginx/sites-enabled
rm -f /etc/nginx/sites-enabled/default

cat > /etc/nginx/sites-enabled/default <<EOF
server {
    listen $PORT;
    root /app/public;
    index index.php;
    location / { try_files \$uri \$uri/ /index.php?\$query_string; }
    location ~ \.php\$ { fastcgi_pass 127.0.0.1:9000; fastcgi_index index.php; fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name; include fastcgi_params; }
}
EOF

php-fpm -D 2>/dev/null || true
sleep 2
nginx -g "daemon off;"
