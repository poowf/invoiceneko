#!/bin/sh

rm -rf /etc/nginx/sites-enabled/default
ln -s /etc/nginx/sites-available/invoiceneko-nginx.conf /etc/nginx/sites-enabled/invoiceneko-nginx.conf
/usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf