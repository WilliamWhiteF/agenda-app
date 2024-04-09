FROM php:8.3.4-cli-alpine3.19 as base

WORKDIR /app

RUN apk add --no-cache linux-headers
RUN docker-php-ext-install sockets mysqli pdo pdo_mysql

COPY --from=composer:2.6.6 /usr/bin/composer /usr/local/bin/composer

COPY . .
RUN composer install

ENTRYPOINT ["./docker-entrypoint.sh"]

EXPOSE 8080
EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
