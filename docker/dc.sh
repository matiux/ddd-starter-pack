#!/bin/sh

#WORKDIR=$(docker-compose --file docker/docker-compose.yml run --rm -u utente php pwd)
WORKDIR=/var/www/app
PROJECT_NAME=$(basename $(pwd) | tr  '[:upper:]' '[:lower:]')
COMPOSE_OVERRIDE=

if [ -f "./docker-compose.override.yml" ]; then
    COMPOSE_OVERRIDE="--file docker/docker-compose.override.yml"
fi

if [ "$1" = "composer" ]; then

    shift 1
    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        ${COMPOSE_OVERRIDE} \
        run \
        --rm \
        -u utente \
        -v ${PWD}:/var/www/app \
        -w ${WORKDIR} \
        php \
        composer $@

elif [ "$1" = "up" ]; then

    shift 1
#    if [ ! -d docker/php/ssh ]; then
#        mkdir -p docker/php/ssh
#        cp ~/.ssh/id_rsa docker/php/ssh/
#        cp ~/.ssh/id_rsa.pub docker/php/ssh/
#    fi

    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        ${COMPOSE_OVERRIDE} \
        up $@

elif [ "$1" = "enter-root" ]; then

    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        ${COMPOSE_OVERRIDE} \
        exec \
        php /bin/zsh

elif [ "$1" = "enter" ]; then

    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        ${COMPOSE_OVERRIDE} \
        exec \
        -u utente \
        -w ${WORKDIR} \
        php /bin/zsh

elif [ "$1" = "down" ]; then

    shift 1
    docker-compose \
	    --file docker/docker-compose.yml \
	    -p ${PROJECT_NAME} \
	    ${COMPOSE_OVERRIDE} \
		down $@

elif [ "$1" = "purge" ]; then

    docker-compose \
	    --file docker/docker-compose.yml \
	    -p ${PROJECT_NAME} \
	    ${COMPOSE_OVERRIDE} \
		down \
        --rmi=all \
        --volumes \
        --remove-orphans

elif [ "$1" = "log" ]; then

    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        ${COMPOSE_OVERRIDE} \
        logs -f

elif [ "$1" = "sf" ]; then
    shift 1

    docker-compose \
		--file docker/docker-compose.yml \
		-p ${PROJECT_NAME} \
		${COMPOSE_OVERRIDE} \
		exec \
		--rm \
		-u utente \
		#-v ${PWD}:/var/www/app \
		-w ${WORKDIR} \
		php \
		php ./bin/console $@

elif [ $# -gt 0 ]; then
    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        ${COMPOSE_OVERRIDE} \
        "$@"

else
    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        ${COMPOSE_OVERRIDE} \
        ps
fi
