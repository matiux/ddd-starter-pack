FROM matiux/php:8.0-fpm-debian-buster-dev

USER root

ENV TZ=Europe/Rome

RUN docker-php-ext-install sockets
RUN rm /usr/local/etc/php/conf.d/docker-php-memlimit.ini
ADD conf/php.ini /usr/local/etc/php

###> Configurazione Xdebug
COPY conf/xdebug-starter.sh /usr/local/bin/xdebug-starter
RUN chmod +x /usr/local/bin/xdebug-starter
RUN /usr/local/bin/xdebug-starter
###> Fine configurazione Xdebug

RUN composer self-update

USER utente

###> Bashrc con alias
COPY conf/shell-alias.rc /tmp/shell-alias.rc
RUN cat /tmp/shell-alias.rc >> /home/utente/.zshrc \
    && cat /tmp/shell-alias.rc >> /home/utente/.bashrc
###> Fine bashrc con alias

USER www-data