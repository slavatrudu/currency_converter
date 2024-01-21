#!/bin/bash
echo 'Initializing...'
cd /srv/app
cp -f .env.docker .env
# Waiting for database initialization
for i in {30..0}; do
    if echo 'SELECT 1' | mysql -u$MYSQL_USER -p$MYSQL_PASSWORD -hdb &> /dev/null; then
        break
    fi
    echo 'MySQL init process in progress...'
    sleep 1
done

php artisan key:generate
php artisan migrate:fresh --seed
php artisan get-rates
php artisan serve --host=0.0.0.0 --port=8081
