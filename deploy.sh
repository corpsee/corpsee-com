#!/bin/sh

set -e

NORMAL='\033[0m'  #  ${NORMAL}   # все атрибуты по умолчанию
CYAN='\033[0;36m' #  ${CYAN}     # цвет морской волны знаков

#config
BASE_DIR="/var/www/"
PROJECT="zoner2-primary"
TAG="v11"
USER="deployer"
GROUP="deployer"

help()
{
	echo "$CYANИспользование: $SCRIPT options..."
	echo "Параметры:"
	echo "-r  Релиз приложения на боевом сервере для тестирования (поддомен dev)."
	echo "-f  Окончательный релиз приложения на боевом сервере (боевой домен)."
	echo "-h  Справка.$NORMAL"
	echo ""
}

release()
{
	cd /var/www
	git clone git@github.com:corpsee/corpsee-com.git dev.corpsee.com

	cd ./dev.corpsee.com
	git checkout -f "$TAG"

	cd ../
	git clone git@bitbucket.org:corpsee/corpsee.com-private.git private.corpsee.com

	cd ./private.corpsee.com
	git checkout -f "$TAG"

	#dd if=/dev/zero of=/swapfile bs=1M count=1024
	#mkswap -f /swapfile
	#swapon /swapfile

	#cd ./dev.corpsee.com
	#curl -sS https://getcomposer.org/installer | php
	#php composer.phar self-update
	#php -d memory_limit=-1 composer.phar install

	#swapoff /swapfile

	cd ../

	rm -rf ./dev.corpsee.com/.git
	rm -rf ./dev.corpsee.com/.gitignore
	rm -rf ./private.corpsee.com/.git
	rm -rf ./private.corpsee.com/.gitignore

	mkdir ./dev.corpsee.com/session
	mkdir ./dev.corpsee.com/temp

	cp -fr ./private.corpsee.com/* ./dev.corpsee.com
	rm -fr ./private.corpsee.com

	cd ./dev.corpsee.com/Application/configs
	mv -f  ./configuration.production.php ./configuration.php

	cd ../../www
	rm ./index.debug.php
	mv -f  ./index.production.php ./index.php

	cd ../../
	chown -R web .
	chgrp -R www-data .
	find . -type d -exec chmod 775 {} \;
	find . -type f -exec chmod 664 {} \;

	#a2ensite dev.corpsee.com
	#ln -s /etc/nginx/sites-available/dev.corpsee.com /etc/nginx/sites-enabled/dev.corpsee.com

	/root/ensite dev.corpsee.com
}

finish()
{
	[ ! -d "/var/www/backups" ] && mkdir -p /var/www/backups
	chmod 755 /var/www/backups
	chgrp www-data /var/www/backups

	service apache2 stop
	service nginx   stop

	#tar czf /var/www/backups/corpsee.com."$TAG".tar.gz /var/www/corpsee.com
	#rm -rf /var/www/corpsee.com/*

	mv -rf /var/www/dev.corpsee.com/* /var/www/corpsee.com

	/root/dissite dev.corpsee.com

	service apache2 start
	service nginx   start
}

if [ $# = 0 ]; then
	help
fi

while getopts ":rfh" opt; do
	case $opt in
		r)
			release
			;;
		f)
			finish
			;;
		h)
			help
			;;
		*)
			echo "$CYANНеправильный параметр. Для вызова справки запустите скрипт с ключом -h$NORMAL";
			exit 1
			;;
	esac
done

exit 0