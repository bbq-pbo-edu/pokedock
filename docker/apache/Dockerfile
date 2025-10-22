FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql

COPY ./010-block-ip-access.conf /etc/apache2/sites-available/010-block-ip-access.conf
#COPY ./www.patrick.web.bbq.conf /etc/apache2/sites-available/www.patrick.web.bbq.conf
#COPY ./www.company.patrick.web.bbq.conf /etc/apache2/sites-available/www.company.patrick.web.bbq.conf
COPY ./www.pokemon.patrick.web.bbq.conf /etc/apache2/sites-available/www.pokemon.patrick.web.bbq.conf

RUN a2ensite 010-block-ip-access.conf
#RUN a2ensite www.patrick.web.bbq.conf
#RUN a2ensite www.company.patrick.web.bbq.conf
RUN a2ensite www.pokemon.patrick.web.bbq.conf