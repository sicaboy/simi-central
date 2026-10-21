#!/bin/bash
set -e

# Bind mounts may have host ownership; PHP-FPM runs as www-data.
for directory in /var/www/html/storage /var/www/html/bootstrap/cache "${LARAVEL_STORAGE_PATH:-/var/www/html/storage}"; do
    if [ -d "$directory" ]; then
        chown -R www-data:www-data "$directory"
    fi
done

nginx -t
php-fpm -t

terminate() {
    trap - TERM INT
    kill -QUIT "${NGINX_PID:-}" "${FPM_PID:-}" 2>/dev/null || true
    wait "${NGINX_PID:-}" "${FPM_PID:-}" 2>/dev/null || true
}
trap 'terminate; exit 0' TERM INT

php-fpm -F &
FPM_PID=$!
nginx -g 'daemon off;' &
NGINX_PID=$!

# Stop the container if either web process exits, so restart policies apply.
set +e
wait -n "$FPM_PID" "$NGINX_PID"
status=$?
terminate
[ "$status" -ne 0 ] || status=1
exit "$status"
