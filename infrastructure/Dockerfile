FROM php:7.2.2-fpm-alpine3.7

LABEL maintainer="atanasmk16@gmail.com"

COPY ./travel-be /app
EXPOSE 8080
WORKDIR /app

RUN composer install
