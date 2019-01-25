#!/bin/bash
git pull
composer install --no-dev
npm install
npm run prod

php artisan view:clear
php artisan view:cache
php artisan clear-compiled
php artisan optimize

sudo supervisorctl restart invoiceneko-horizon:*
