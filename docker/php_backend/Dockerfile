# syntax=docker/dockerfile

FROM php:7.4-fpm

# Без update не скачает
RUN     apt update && \ 
        apt install -y git zip unzip;
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
RUN     composer create-project --prefer-dist yiisoft/yii2-app-basic 