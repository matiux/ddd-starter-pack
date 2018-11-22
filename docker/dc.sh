#!/bin/sh

#WORKDIR=$(docker-compose --file docker/docker-compose.yml run --rm -u utente php pwd)
WORKDIR=/var/www/app
PROJECT_NAME=$(basename $(pwd) | tr  '[:upper:]' '[:lower:]')

if [ "$1" = "composer" ]; then

    shift 1
    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        run \
        --rm \
        -u utente \
        -v ${PWD}:/var/www/app \
        -w ${WORKDIR} \
        php \
        composer $@

elif [ "$1" = "up" ]; then

    shift 1
    if [ ! -d docker/php/ssh ]; then
        mkdir -p docker/php/ssh
        cp ~/.ssh/id_rsa docker/php/ssh/
        cp ~/.ssh/id_rsa.pub docker/php/ssh/
    fi

    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        up $@

elif [ "$1" = "enter-root" ]; then

    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        exec \
        php /bin/zsh

elif [ "$1" = "enter" ]; then

    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        exec \
        -u utente \
        -w ${WORKDIR} \
        php /bin/zsh

elif [ "$1" = "down" ]; then

    shift 1
    docker-compose \
	    --file docker/docker-compose.yml \
	    -p ${PROJECT_NAME} \
		down $@

elif [ "$1" = "prune" ]; then

    docker-compose \
	    --file docker/docker-compose.yml \
	    -p ${PROJECT_NAME} \
		down \
        --rmi=all \
        --volumes \
        --remove-orphans

elif [ "$1" = "log" ]; then

    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        logs -f

elif [ "$1" = "sf" ]; then
    shift 1

    docker-compose \
		--file docker/docker-compose.yml \
		-p ${PROJECT_NAME} \
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
        "$@"

else
    docker-compose \
        --file docker/docker-compose.yml \
        -p ${PROJECT_NAME} \
        ps
fi
