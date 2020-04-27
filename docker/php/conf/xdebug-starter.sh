#!/usr/bin/env bash

set -e

# if `HOST_IP` is manually configured as env
HOST="$HOST_IP"

# try to get the ip of the host from ns host.docker.internal
if [[ -z "$HOST" ]]; then
  HOST=$(getent hosts host.docker.internal | awk '{ print $1 }')
fi

# try to get the linux host ip
if [[ -z "$HOST" ]]; then
  HOST=$(ip route | awk 'NR==1 {print $3}')
fi

# use the ip alias loopback
# if [ -z "$HOST" ]; then
#     HOST=`10.254.254.254`
# fi

if [[ -f /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ]]; then
  sed -i "s/xdebug\.remote_host=.*/xdebug\.remote_host=${HOST}/" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
fi
