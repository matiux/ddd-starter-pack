FROM matiux/php:7.2.25-fpm-alpine3.10-dev

USER root

RUN rm /usr/local/etc/php/conf.d/docker-php-memlimit.ini
ADD conf/php.ini /usr/local/etc/php

COPY conf/xdebug-starter.sh /usr/local/bin/xdebug-starter
RUN chmod +x /usr/local/bin/xdebug-starter
RUN /usr/local/bin/xdebug-starter


USER utente

RUN echo 'alias test="vendor/bin/phpunit"' >> /home/utente/.bashrc \
    echo 'alias test="vendor/bin/phpunit"' >> /home/utente/.zshrc
