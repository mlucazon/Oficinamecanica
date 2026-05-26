#!/bin/sh
set -eu

PORT="${PORT:-8080}"

echo "Starting Nginx on port ${PORT}..."

sed -i "s/listen 80;/listen ${PORT};/" /etc/nginx/nginx.conf
nginx -t

exec nginx -g "daemon off;"
