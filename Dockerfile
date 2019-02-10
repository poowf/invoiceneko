# Download base image ubuntu 18.04
FROM ubuntu:18.04

# Define ENV Variables
ENV NGINX_VHOST /etc/nginx/sites-available/
ENV NGINX_CONF /etc/nginx/nginx.conf
ENV SUPERVISOR_CONF /etc/supervisor/supervisord.conf
ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC
ENV INVOICENEKO_DIRECTORY /var/www/html/invoiceneko

# Set Timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Update Software repository
RUN apt-get update && apt-get install -y software-properties-common curl apt-utils \
    && { \
            echo debconf debconf/frontend select Noninteractive; \
            echo mysql-community-server mysql-community-server/data-dir \
                select ''; \
            echo mysql-community-server mysql-community-server/root-pass \
                password '347FE11595E31F2854CDFF51F53C093231997710'; \
            echo mysql-community-server mysql-community-server/re-root-pass \
                password '347FE11595E31F2854CDFF51F53C093231997710'; \
            echo mysql-community-server mysql-community-server/remove-test-db \
                select true; \
        } | debconf-set-selections

# Add official nginx repository
RUN add-apt-repository ppa:nginx/stable

# Add NodeJS v11.x
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -

# Install nginx, php-fpm and supervisord from ubuntu repository
RUN apt-get update && apt-get install -y nginx mysql-server supervisor git build-essential debconf-utils nodejs unzip xvfb autogen autoconf libtool pkg-config nasm \
    php7.2-common php7.2-cli php7.2-fpm php7.2-curl php7.2-json php7.2-mysql php7.2-readline php7.2-sqlite3 php7.2-tidy php7.2-xmlrpc php7.2-xsl php7.2-zip php7.2-mbstring php7.2-gd php7.2-soap php7.2-opcache php7.2-xml php7.2-bcmath php7.2 php7.2-bz2

# Install composer
RUN curl https://getcomposer.org/composer.phar -o /usr/local/bin/composer && chmod +x /usr/local/bin/composer && composer self-update

# Create the run-time directories
RUN mkdir -p /run/php && \
    mkdir -p /var/run/mysqld && \
    chown -R mysql:mysql /var/run/mysqld && \
    chown -R www-data:www-data /var/www/html && \
    chown -R www-data:www-data /run/php

# Clone InvoiceNeko
RUN git clone https://github.com/poowf/invoiceneko.git /var/www/html/invoiceneko

# Environment Setup
COPY docker/.env.docker ${INVOICENEKO_DIRECTORY}/.env

# Install package dependencies
RUN cd $INVOICENEKO_DIRECTORY && composer install --no-dev --no-interaction --prefer-dist --no-suggest && php artisan key:generate && php artisan storage:link

## Generating build assets
RUN cd $INVOICENEKO_DIRECTORY && npm install && npm run prod

# Set application permissions
RUN chown -R www-data:www-data /var/www/html && \
    find $INVOICENEKO_DIRECTORY -type f -exec chmod 664 {} \; && \
    find $INVOICENEKO_DIRECTORY -type d -exec chmod 755 {} \; && \
    chgrp -R www-data $INVOICENEKO_DIRECTORY/storage $INVOICENEKO_DIRECTORY/bootstrap/cache && \
    chmod -R ug+rwx $INVOICENEKO_DIRECTORY/storage $INVOICENEKO_DIRECTORY/bootstrap/cache && \
    chmod -R g+s $INVOICENEKO_DIRECTORY/storage $INVOICENEKO_DIRECTORY/bootstrap/cache && \
    touch $INVOICENEKO_DIRECTORY/storage/logs/laravel.log && \
    chmod 775 $INVOICENEKO_DIRECTORY/storage/logs/laravel.log

# Volume configuration
VOLUME ["/etc/nginx/sites-enabled", "/etc/nginx/certs", "/etc/nginx/conf.d", "/var/log/nginx", "/var/lib/mysql", "/var/www/html"]

# Enable php-fpm on nginx virtualhost configuration
ARG CACHE_BUST=0
COPY docker/invoiceneko-nginx.conf ${NGINX_VHOST}/
RUN echo "\ndaemon off;" >> ${NGINX_CONF}

# Copy supervisor configuration
COPY docker/supervisord.conf ${SUPERVISOR_CONF}

# Copy bootstrapper script
COPY docker/bootstrapper.sh /usr/local/sbin/bootstrapper.sh

# Configure Services and Port
COPY docker/start.sh /start.sh
CMD ["./start.sh"]

EXPOSE 80 443