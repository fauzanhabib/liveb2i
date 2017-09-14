FROM php:7-apache

# Used for debugging, remove when done using.
#RUN apt-get install vim -y

#RUN apt-get update -y && apt-get install -y libxml2-dev && docker-php-ext-install soap mysqli
RUN apt-get update -y && apt-get install libmcrypt-dev -y
RUN docker-php-ext-install mysqli
RUN docker-php-ext-configure mcrypt && docker-php-ext-install mcrypt
#RUN apt-get install php7-mcrypt

RUN a2enmod headers && a2enmod expires && a2enmod rewrite

ADD ./config/php.ini /usr/local/etc/php/

ADD ./live/* /var/www/html/
