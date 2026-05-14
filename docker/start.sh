#!/bin/bash
set -e

PORT=${PORT:-8080}

sed -i "s/__PORT__/$PORT/g" /etc/nginx/http.d/default.conf

php-fpm -D

nginx -g "daemon off;"
