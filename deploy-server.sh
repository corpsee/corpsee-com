#!/bin/bash

set -e

CURRENT_TIMESTAMP=`date +%s`

PROJECT='corpsee.com'
MODE='production' # production|debug

BASE_DIR='/var/www'

POSTGRESQL_USER="corpsee.com"
POSTGRESQL_PASSWORD="password"
POSTGRESQL_DBNAME="corpsee_com_db"

# see https://github.com/corpsee/phpell
source /usr/bin/functions

_help() {
    echo "How to use deploy-server.sh:"
    echo "Available params:"
    echo "-r|--release  - Release new version"
    echo "-b|--rollback - Rollback latest version"
    echo
    exit 0
}


_release() {
    cd "${BASE_DIR}"

    git clone git@github.com:corpsee/corpsee-com.git "${PROJECT}-${CURRENT_TIMESTAMP}"

    cd "${PROJECT}-${CURRENT_TIMESTAMP}"
    git checkout -f feature-deploy

    ./deploy.sh "${PROJECT}" "${MODE}" "${BASE_DIR}" "${POSTGRESQL_USER}" "${POSTGRESQL_PASSWORD}" "${POSTGRESQL_DBNAME}" "${CURRENT_TIMESTAMP}"
}

_rollback() {
    echo "Rollback"
}

processParamSimple() {
    if [ "$1" = "$2" ]; then
        return 0
    fi

    return 1
}

if ! [ $(id -u -n) = "${PROJECT}" ]; then
   echo "Please, run script from ${PROJECT}!"
   exit 1
fi

test $# -gt 0 || _help

while [ 1 ]; do
    if [ "$1" = "-y" ]; then
        pYes=1
    elif processParamSimple "-r" "$1"; then
        pRelease="pRelease"
    elif processParamSimple "--release" "$1"; then
        pRelease="pRelease"
    elif processParamSimple "-b" "$1"; then
        pRollback="pRollback"
    elif processParamSimple "--rollback" "$1"; then
        pRollback="pRollback"
    elif [ -z "$1" ]; then
        break
    else
        _help
    fi

    shift
done

if [[ "${pRelease}" && "${pRollback}" ]]; then
     _help
fi

if [ "${pYes}" != "1" ]; then
    if ! [ -z "${pRelease}" ]; then
        confirmation "Release new version?" || exit 1
    else
        confirmation "Rollback latest version?" || exit 1
    fi
fi

if ! [ -z "${pRelease}" ]; then
    _release
else
    _rollback
fi
