FROM ubuntu:20.04


# Set debian frontend to noninteractive
ENV DEBIAN_FRONTEND=noninteractive

# Install apache, PHP 7.4 and supplimentary programs.
RUN apt update && \
    apt-get -y upgrade && \
    apt-get -y install \
    apache2 \
    php \
    php-cli \
    libapache2-mod-php \
    php-gd \
    php-curl \
    php-json \
    php-mbstring \
    php-mysql \
    php-xml \
    php-xsl \
    php-zip \
    curl

# Enable apache mods.
RUN a2enmod php7.4
RUN a2enmod rewrite

# Update the PHP.ini file, enable <? ?> tags and quieten logging.
RUN sed -i "s/short_open_tag = Off/short_open_tag = On/" /etc/php/7.4/apache2/php.ini
RUN sed -i "s/error_reporting = .*$/error_reporting = E_ERROR | E_WARNING | E_PARSE/" /etc/php/7.4/apache2/php.ini

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid


# Copy this repo into place.
COPY . /var/www/html

# Update the default apache site with the config we created.
ADD apache2.conf /etc/apache2/sites-enabled/000-default.conf

#Install composer to container.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set work directory.
WORKDIR /var/www/html

# composer install will run every time we start the container.
CMD ["sh", "-c", "composer install"] 

# Expose apache.
EXPOSE 80

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD /usr/sbin/apache2ctl -D FOREGROUND
