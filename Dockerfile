# Dockerfile untuk Web server apache + PHP

# Image diturunkan dari Ubuntu LTS
FROM ubuntu:20.04

# php7 node_module apache

# U pdate database paket dan instal semua yang diperlukan
# curl dan lynx-cur digunakan untuk debugging container
# Enable apache mods.
# Update file PHP.ini , enable-kan tag dan quieten logging.
RUN apt-get update

RUN apt-get install php7.3

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    nano \
    libzip-dev \
    unzip \
    git \
    libonig-dev \
    curl

#RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Port 80 dipublish ke Host
EXPOSE 80

# Tambahkan halaman web (aplikasi) default ke /var/www/
COPY . /var/www/html

# Update situs apache default dengan konfigurasi yang kita buat.
# COPY ./config.conf /etc/apache2/sites-enabled/000-default.conf

# Secara default, jalankan apache.
# CMD /usr/sbin/apache2ctl -D FOREGROUND

