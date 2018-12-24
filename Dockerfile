FROM php:5.6-apache
USER root
ADD ./ /var/www/html
RUN apt-get update && apt-get install -y libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql bcmath mbstring
RUN chmod 777 -R /var/www/html
