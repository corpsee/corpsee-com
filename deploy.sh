#!/bin/sh

set -e

PROJECT=$1
MODE=$2

BASE_DIR=$3

POSTGRESQL_USER=$4
POSTGRESQL_PASSWORD=$5
POSTGRESQL_DBNAME=$6

CURRENT_TIMESTAMP=$7
CURRENT_DATE=`date +%Y-%m-%d`

PROJECT_DIR="${BASE_DIR}/${PROJECT}"
BACKUP_DIR="/var/backups/${PROJECT}"

composer install --no-dev

rm -rf ./.git
rm -rf ./.gitignore

mkdir -p ./sessions
mkdir -p ./temp
ln -sv /var/log/"${PROJECT}" "${PROJECT_DIR}-${CURRENT_TIMESTAMP}"/logs

sed -e "s:\${POSTGRESQL_USER}:${POSTGRESQL_USER}:g;s:\${POSTGRESQL_PASSWORD}:${POSTGRESQL_PASSWORD}:g;s:\${POSTGRESQL_DBNAME}:${POSTGRESQL_DBNAME}:g" ./src/configs/base.php > ./src/configs/base.php

mv -f ./src/configs/config."${MODE}".php ./src/configs/config.php

[ -f ./src/configs/config.production.php ] && rm -f ./src/configs/config.production.php
[ -f ./src/configs/config.debug.php ]      && rm -f ./src/configs/config.debug.php

mv -f ./www/index."${MODE}".php ./www/index.php

[ -f ./www/index.production.php ] && rm -f ./www/index.production.php
[ -f ./www/index.debug.php ]      && rm -f ./www/index.debug.php

[ ! -d ./www/files/posts ]          && mkdir -p ./www/files/posts
[ ! -d ./www/files/pictures/x ]     && mkdir -p ./www/files/pictures/x
[ ! -d ./www/files/pictures/xgray ] && mkdir -p ./www/files/pictures/xgray
[ ! -d ./www/files/pictures/xmin ]  && mkdir -p ./www/files/pictures/xmin
[ ! -d ./www/files/projects ]       && mkdir -p ./www/files/projects
[ ! -d ./www/slides ]               && mkdir -p ./www/slides
[ ! -d ./www/yanka ]                && mkdir -p ./www/yanka

[ -d "${PROJECT_DIR}"/www/files/posts ]          && cp -fv "${PROJECT_DIR}"/www/files/posts/*          ./www/files/posts/
[ -d "${PROJECT_DIR}"/www/files/pictures/x ]     && cp -fv "${PROJECT_DIR}"/www/files/pictures/x/*     ./www/files/pictures/x/
[ -d "${PROJECT_DIR}"/www/files/pictures/xgray ] && cp -fv "${PROJECT_DIR}"/www/files/pictures/xgray/* ./www/files/pictures/xgray/
[ -d "${PROJECT_DIR}"/www/files/pictures/xmin ]  && cp -fv "${PROJECT_DIR}"/www/files/pictures/xmin/*  ./www/files/pictures/xmin/
[ -d "${PROJECT_DIR}"/www/files/projects ]       && cp -fv "${PROJECT_DIR}"/www/files/projects/*       ./www/files/projects/
[ -d "${PROJECT_DIR}"/www/slides ]               && cp -fv "${PROJECT_DIR}"/www/slides/*               ./www/slides/
[ -d "${PROJECT_DIR}"/www/yanka ]                && cp -fv "${PROJECT_DIR}"/www/yanka/*                ./www/yanka/

chmod 774 ./console

sudo disable-host -h "${PROJECT}"

    [ -d "${PROJECT_DIR}" ] && tar czf "${BACKUP_DIR}/${PROJECT}"."${CURRENT_DATE}"."${CURRENT_TIMESTAMP}".tar.gz "${PROJECT_DIR}"
    [ -d "${PROJECT_DIR}" ] && rm -rf  "${PROJECT_DIR}"

    mkdir -p "${PROJECT_DIR}"
    mv -f    "${PROJECT_DIR}-${CURRENT_TIMESTAMP}"/* "${PROJECT_DIR}"
    rm -rf   "${PROJECT_DIR}-${CURRENT_TIMESTAMP}"

    cd "${PROJECT_DIR}"

    ./console assets:compile --package frontend
    ./console migrations:migrate

    sed -e "s:\${PROJECT_DIR}:${PROJECT_DIR}:g" "${PROJECT_DIR}"/crontab > "${PROJECT_DIR}"/crontab.tmp
    echo "" >> "${PROJECT_DIR}"/crontab.tmp
    crontab "${PROJECT_DIR}"/crontab.tmp
    rm -f "${PROJECT_DIR}"/crontab.tmp

sudo enable-host -h "${PROJECT}"

exit 0
