#!/bin/sh

# start by corpsee.com user

set -e

NORMAL='\033[0m'  #  ${NORMAL}   # default text decoration
CYAN='\033[0;36m' #  ${CYAN}     # blue color

PROJECT='corpsee.com'
BASE_DIR='/var/www'
VERSION='v21'
LAST_VERSION='v20'
MODE='production' # production|debug

PROJECT_DIR="$BASE_DIR/$PROJECT"
BACKUP_DIR="/var/backups/$PROJECT"

POSTGRESQL_USER="corpsee_com"
POSTGRESQL_PASSWORD="password"
POSTGRESQL_DBNAME="corpsee_com_db"

help ()
{
    echo "$CYANHow use:"
    echo "Параметры:"
    echo "-r  Release new version of site."
    echo "-h  Help.$NORMAL"
    echo ""
}

release ()
{
    cd "$BASE_DIR"

    [ -d "$PROJECT"-"$VERSION" ] && rm -rf "$PROJECT"-"$VERSION"
    git clone git@github.com:corpsee/corpsee-com.git "$PROJECT"-"$VERSION"

    cd "$BASE_DIR"/"$PROJECT"-"$VERSION"
    git checkout -f "$VERSION"

    sudo composer selfupdate
    composer install

    rm -rf ./.git
    rm -rf ./.gitignore

    mkdir -p ./session
    mkdir -p ./temp

    sed -e "s:${POSTGRESQL_USER}:${POSTGRESQL_USER}:g;s:${POSTGRESQL_PASSWORD}:${POSTGRESQL_PASSWORD}:g;s:${POSTGRESQL_DBNAME}:${POSTGRESQL_DBNAME}:g" ./Application/configs/config."$MODE".php > ./Application/configs/config.php

    [ -f ./Application/configs/config.production.php ] && rm -f ./Application/configs/config.production.php
    [ -f ./Application/configs/config.debug.php ]      && rm -f ./Application/configs/config.debug.php

    mv -f ./www/index."$MODE".php ./www/index.php

    [ -f ./www/index.production.php ] && rm -f ./www/index.production.php
    [ -f ./www/index.debug.php ]      && rm -f ./www/index.debug.php

    [ ! -d ./www/files/posts ]          && mkdir -p ./www/files/posts
    [ ! -d ./www/files/pictures/x ]     && mkdir -p ./www/files/pictures/x
    [ ! -d ./www/files/pictures/xgray ] && mkdir -p ./www/files/pictures/xgray
    [ ! -d ./www/files/pictures/xmin ]  && mkdir -p ./www/files/pictures/xmin
    [ ! -d ./www/slides ]               && mkdir -p ./www/slides
    [ ! -d ./www/yanka ]                && mkdir -p ./www/yanka

    cp -fv "$PROJECT_DIR"/www/files/posts/*          ./www/files/posts/
    cp -fv "$PROJECT_DIR"/www/files/pictures/x/*     ./www/files/pictures/x/
    cp -fv "$PROJECT_DIR"/www/files/pictures/xgray/* ./www/files/pictures/xgray/
    cp -fv "$PROJECT_DIR"/www/files/pictures/xmin/*  ./www/files/pictures/xmin/
    cp -fv "$PROJECT_DIR"/www/slides/*               ./www/slides/
    cp -fv "$PROJECT_DIR"/www/yanka/*                ./www/yanka/

    chmod 774 ./console

    sudo disable-host "$PROJECT"

    [ ! -d "$BACKUP_DIR" ] && sudo mkdir -p "$BACKUP_DIR"

    [ -d "$BASE_DIR"/"$PROJECT" ] && tar czf "$BACKUP_DIR"/"$PROJECT"."$LAST_VERSION".tar.gz "$BASE_DIR"/"$PROJECT"
    [ -d "$BASE_DIR"/"$PROJECT" ] && rm -rf  "$BASE_DIR"/"$PROJECT"

    mkdir -p "$BASE_DIR"/"$PROJECT"
    mv -f    "$BASE_DIR"/"$PROJECT"-"$VERSION"/* "$BASE_DIR"/"$PROJECT"
    rm -rf   "$BASE_DIR"/"$PROJECT"-"$VERSION"

    cd "$BASE_DIR"/"$PROJECT"

    ./console assets:compile
    ./console migrations:migrate

    sed -e "s:${PROJECT_DIR}:${PROJECT_DIR}:g" "$PROJECT_DIR"/crontab > "$PROJECT_DIR"/crontab.tmp
    echo "" >> "$PROJECT_DIR"/crontab.tmp
    crontab "$PROJECT_DIR"/crontab.tmp
    rm -f "$PROJECT_DIR"/crontab.tmp

    sudo enable-host "$PROJECT"
}

if [ $# = 0 ]; then
    help
fi

while getopts ":r" opt; do
    case $opt in
        r)
            release
            ;;
        *)
            help
            ;;
    esac
done

exit 0