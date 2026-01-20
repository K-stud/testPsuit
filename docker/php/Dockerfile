# syntax=docker/dockerfile

FROM php:7.4-fpm

RUN             ["apt", "update"]
RUN             ["apt", "install", "-y", "git", "zip", "unzip"]
RUN             ["docker-php-ext-install", "mysqli", "pdo", "pdo_mysql"]
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

WORKDIR /var/www/yii
ENTRYPOINT ["sh", "-c"]
CMD ["composer install && php-fpm"]
