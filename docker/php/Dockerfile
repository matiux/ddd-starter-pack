FROM matiux/php:fpm-8.2-bullseye-dev

USER root

ENV TZ=Europe/Rome

#RUN docker-php-ext-install sockets
RUN rm /usr/local/etc/php/conf.d/docker-php-memlimit.ini
ADD ./docker/php/conf/php.ini /usr/local/etc/php

###> Configurazione Xdebug
COPY ./docker/php/conf/xdebug-starter.sh /usr/local/bin/xdebug-starter
RUN chmod +x /usr/local/bin/xdebug-starter
RUN /usr/local/bin/xdebug-starter
###> Fine configurazione Xdebug

RUN composer self-update

USER utente

###> Bashrc con alias
COPY ./docker/php/conf/shell-custom.rc /tmp/shell-custom.rc
RUN cat /tmp/shell-custom.rc >> /home/utente/.zshrc \
    && cat /tmp/shell-custom.rc >> /home/utente/.bashrc
###> Fine bashrc con alias

USER www-data