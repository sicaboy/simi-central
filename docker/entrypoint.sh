#!/usr/bin/env bash

if [ ! -d "/var/www/html/vendor" ]
then
    /usr/local/bin/composer install --prefer-dist --no-interaction
    /usr/local/bin/php /var/www/html/artisan storage:link
fi

/usr/local/bin/php /var/www/html/artisan optimize:clear
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap
chmod -R 777 /var/www/html/bootstrap/cache

/usr/local/bin/php /var/www/html/artisan queue:work --tries=3 --timeout=90

# Execute Docker CMD
exec "$@"
