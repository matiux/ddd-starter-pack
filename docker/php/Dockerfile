FROM matiux/php:7.3.6-fpm-alpine3.10-dev

USER root

RUN rm /usr/local/etc/php/conf.d/docker-php-memlimit.ini
ADD conf/php.ini /usr/local/etc/php

COPY conf/xdebug-starter.sh /usr/local/bin/xdebug-starter
RUN chmod +x /usr/local/bin/xdebug-starter
RUN /usr/local/bin/xdebug-starter

RUN composer self-update

USER utente

###> Bashrc con alias
COPY conf/shell-alias.rc /tmp/shell-alias.rc
RUN cat /tmp/shell-alias.rc >> /home/utente/.zshrc \
    && cat /tmp/shell-alias.rc >> /home/utente/.bashrc
###> Fine bashrc con alias
