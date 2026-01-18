# syntax=docker/dockerfile

FROM php:7.4-fpm

# Без update не скачает
RUN     ["apt", "update"]
RUN     ["apt", "install", "-y", "git", "zip", "unzip"]
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

WORKDIR /var/www/
# Создание проекта необходим.
# Нужно создать отдельную дерикторию где будут заменятся необходимые файлы проекты
# Остальной проект будет в WCL
RUN     ["composer", "create-project", "--prefer-dist", "yiisoft/yii2-app-basic", "yii"]
