FROM php:7.3-cli
MAINTAINER Bruno Perel

RUN apt-get update && apt-get install -y \
      libpng-dev libfreetype6-dev libmcrypt-dev libjpeg-dev libpng-dev ffmpeg bash

RUN docker-php-ext-configure gd \
  --enable-gd-native-ttf \
  --with-freetype-dir=/usr/include/freetype2 \
  --with-png-dir=/usr/include \
  --with-jpeg-dir=/usr/include

RUN docker-php-ext-install gd

COPY wheel.php /home/wheel.php
COPY entrypoint.sh /home/entrypoint.sh

WORKDIR /home

RUN mkdir /tmp/original

ENTRYPOINT ["/bin/bash", "/home/entrypoint.sh"]
