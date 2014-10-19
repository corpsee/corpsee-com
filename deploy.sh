#!/bin/sh

# start by corpsee.com user

set -e

NORMAL='\033[0m'  #  ${NORMAL}   # default text decoration
CYAN='\033[0;36m' #  ${CYAN}     # blue color

PROJECT='corpsee.com'
BASE_DIR='/var/www'
VERSION='v16'
LAST_VERSION='v15'
MODE='production' # production|debug

PROJECT_DIR="$BASE_DIR/$PROJECT"
BACKUP_DIR="/var/backups/$PROJECT"

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

	#php -r "readfile('https://getcomposer.org/installer');" | php
	sudo composer self-update
	composer install

	rm -rf ./.git
	rm -rf ./.gitignore

	mkdir -p ./session
	mkdir -p ./temp

	mv -f ./Application/configs/configuration."$MODE".php ./Application/configs/configuration.php
		[ -f ./Application/configs/configuration.production.php ] && rm -f ./Application/configs/configuration.production.php
		[ -f ./Application/configs/configuration.debug.php ] && rm -f ./Application/configs/configuration.debug.php

	mv -f ./www/index."$MODE".php ./www/index.php
		[ -f ./www/index.production.php ] && rm -f ./www/index.production.php
		[ -f ./www/index.debug.php ] && rm -f ./www/index.debug.php

	cp -f "$PROJECT_DIR"/Application/corpsee.sqlite ./Application/corpsee.sqlite

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

		sed -e "s:\%PROJECT_DIR%:${PROJECT_DIR}:g" "$PROJECT_DIR"/crontab > "$PROJECT_DIR"/crontab.tmp
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