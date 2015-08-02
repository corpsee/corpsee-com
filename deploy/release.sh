#!/bin/bash

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

sed -i -e "s:<POSTGRESQL_USER>:${POSTGRESQL_USER}:g;s:<POSTGRESQL_PASSWORD>:${POSTGRESQL_PASSWORD}:g;s:<POSTGRESQL_DBNAME>:${POSTGRESQL_DBNAME}:g" ./src/configs/base.php

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

if [[ -d "${PROJECT_DIR}/www/files/posts" && "$(ls -A ${PROJECT_DIR}/www/files/posts)" ]]; then
    cp -fv "${PROJECT_DIR}"/www/files/posts/* ./www/files/posts/
fi
if [[ -d "${PROJECT_DIR}/www/files/pictures/x" && "$(ls -A ${PROJECT_DIR}/www/files/pictures/x)" ]]; then
    cp -fv "${PROJECT_DIR}"/www/files/pictures/x/* ./www/files/pictures/x/
fi
if [[ -d "${PROJECT_DIR}/www/files/pictures/xgray" && "$(ls -A ${PROJECT_DIR}/www/files/pictures/xgray)" ]]; then
    cp -fv "${PROJECT_DIR}"/www/files/pictures/xgray/* ./www/files/pictures/xgray/
fi
if [[ -d "${PROJECT_DIR}/www/files/pictures/xmin" && "$(ls -A ${PROJECT_DIR}/www/files/pictures/xmin)" ]]; then
    cp -fv "${PROJECT_DIR}"/www/files/pictures/xmin/* ./www/files/pictures/xmin/
fi
if [[ -d "${PROJECT_DIR}/www/files/projects" && "$(ls -A ${PROJECT_DIR}/www/files/projects)" ]]; then
    cp -fv "${PROJECT_DIR}"/www/files/projects/* ./www/files/projects/
fi
if [[ -d "${PROJECT_DIR}/www/slides" && "$(ls -A ${PROJECT_DIR}/www/slides)" ]]; then
    cp -fv "${PROJECT_DIR}"/www/slides/* ./www/slides/
fi
if [[ -d "${PROJECT_DIR}/www/yanka" && "$(ls -A ${PROJECT_DIR}/www/yanka)" ]]; then
    cp -fv "${PROJECT_DIR}"/www/yanka/* ./www/yanka/
fi

chmod 774 ./console

sudo disable-host -h "${PROJECT}" -y

    if [ -d "${PROJECT_DIR}" ]; then
        if [ -d "${PROJECT_DIR}_backup" ]; then
            rm -rf   "${PROJECT_DIR}_backup"/*
        else
            mkdir -p "${PROJECT_DIR}_backup"
        fi

        cp -fvr "${PROJECT_DIR}"/* "${PROJECT_DIR}_backup"
        tar czf "${BACKUP_DIR}/${PROJECT}"."${CURRENT_DATE}"."${CURRENT_TIMESTAMP}".tar.gz "${PROJECT_DIR}"
        rm -rf  "${PROJECT_DIR}"/*
    else
        mkdir -p "${PROJECT_DIR}"
    fi

    mv -fv "${PROJECT_DIR}-${CURRENT_TIMESTAMP}"/* "${PROJECT_DIR}"
    rm -rf "${PROJECT_DIR}-${CURRENT_TIMESTAMP}"

    cd "${PROJECT_DIR}"

    ./console assets:compile --package frontend
    ./console migrations:migrate

    sed -i -e "s:<PROJECT_DIR>:${PROJECT_DIR}:g" ./crontab
    crontab ./crontab

sudo enable-host -h "${PROJECT}" -y

exit 0
