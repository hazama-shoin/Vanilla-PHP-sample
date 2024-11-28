#!/bin/sh
set -e

if [ ! -d "/tmp/check" ]; then
    cd /var/www/html
    composer install
    chmod -R 777 storage/*

    mkdir /tmp/check
fi

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- apache2-foreground "$@"
fi

exec "$@"
