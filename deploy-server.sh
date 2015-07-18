#!/bin/sh

# start by corpsee.com user

set -e

NORMAL='\033[0m'  #  ${NORMAL} # default text decoration
CYAN='\033[0;36m' #  ${CYAN}   # blue color

CURRENT_TIMESTAMP=`date +%s`

PROJECT='corpsee.com'
MODE='production' # production|debug

BASE_DIR='/var/www'

POSTGRESQL_USER="corpsee.com"
POSTGRESQL_PASSWORD="password"
POSTGRESQL_DBNAME="corpsee_com_db"

help ()
{
    echo "${CYANH}ow use:"
    echo "Параметры:"
    echo "-r  Release new version of site"
    echo "-h  Help"
    echo "${NORMAL}"
}

release ()
{
    cd "${BASE_DIR}"

    git clone git@github.com:corpsee/corpsee-com.git "${PROJECT}-${CURRENT_TIMESTAMP}"

    cd "${PROJECT}-${CURRENT_TIMESTAMP}"
    git checkout -f feature-deploy

    ./deploy.sh "${PROJECT}" "${MODE}" "${BASE_DIR}" "${POSTGRESQL_USER}" "${POSTGRESQL_PASSWORD}" "${POSTGRESQL_DBNAME}" "${CURRENT_TIMESTAMP}"
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
