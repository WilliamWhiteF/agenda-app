#!/bin/sh

echo "Waiting for mysql..."
while ! nc -z $DB_HOST 3306; do
    sleep 0.1
done

echo "mysql started"

# Apply database migrations
if [ $service == "app" ]; then
    echo "Apply database migrations"
    php artisan migrate
    php artisan db:seed
fi

exec "$@"
