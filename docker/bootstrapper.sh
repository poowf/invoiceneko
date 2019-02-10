#!/bin/bash
/usr/bin/supervisorctl restart mysql:
/usr/bin/supervisorctl restart php-fpm7.2:
/usr/bin/supervisorctl restart nginx:

if [ ! -f /var/www/html/invoiceneko/.migrated ]; then
    echo ".migrated not found, running setup"
	mysql -u root -p347FE11595E31F2854CDFF51F53C093231997710 -e "CREATE DATABASE IF NOT EXISTS invoiceneko; CREATE USER 'invoiceneko'@'localhost' IDENTIFIED BY '347FE11595E31F2854CDFF51F53C093231997710'; GRANT ALL PRIVILEGES ON invoiceneko . * TO 'invoiceneko'@'localhost'; FLUSH PRIVILEGES;"
	cd /var/www/html/invoiceneko
	php artisan migrate --force
	php artisan db:seed --force
	touch .migrated
fi