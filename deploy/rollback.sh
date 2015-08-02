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

sudo disable-host -h "${PROJECT}" -y

    if [ -d "${PROJECT_DIR}_backup" ]; then
        if [ -d "${PROJECT_DIR}" ]; then
            rm -rf   "${PROJECT_DIR}"/*
        else
            mkdir -p "${PROJECT_DIR}"
        fi

        mv -fv   "${PROJECT_DIR}_backup"/* "${PROJECT_DIR}"
        rm -rf  "${PROJECT_DIR}_backup"
    fi

    cd "${PROJECT_DIR}"

    ./console migrations:rollback

    sed -i -e "s:<PROJECT_DIR>:${PROJECT_DIR}:g" ./crontab
    crontab ./crontab

sudo enable-host -h "${PROJECT}" -y

exit 0
