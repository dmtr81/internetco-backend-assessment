FROM php:8.0-fpm

ARG uid="1000"
ARG login="app"

RUN apt-get update \
&& apt-get upgrade -y \
&& apt-get install -y \
   wget \
   zip \
   git \
&& wget -O "/usr/local/bin/composer" "https://getcomposer.org/composer-stable.phar" \
&& chmod -R 755 "/usr/local/bin" \
&& useradd --create-home --user-group --uid=$uid $login \
&& docker-php-ext-install pdo pdo_mysql
