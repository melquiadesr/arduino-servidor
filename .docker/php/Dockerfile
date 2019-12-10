FROM php:7.3-apache

RUN apt-get update -y
RUN apt-get upgrade -y

RUN apt-get install -y \
        adduser \
        ca-certificates \
        unzip \
        curl \
        git \
        wget \
        openssl \
        nano

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >>  /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_connect_back=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=docker" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.default_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/html

COPY config/apache/apache2.conf /etc/apache2/apache2.conf
COPY ./config/apache/site-default.conf /etc/apache2/sites-available/000-default.conf
#COPY config/php.ini /usr/local/etc/php/

RUN a2enmod rewrite
RUN a2enmod headers

RUN echo "172.15.0.2    arduino.server" >> /etc/hosts
RUN echo "172.15.0.3    arduino.mysql" >> /etc/hosts
RUN echo "172.15.0.4    arduino.front" >> /etc/hosts
