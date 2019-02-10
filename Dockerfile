#Download base image ubuntu 18.04
FROM ubuntu:18.04

# Define ENV Variables
ENV nginx_vhost /etc/nginx/sites-available/
ENV php_conf /etc/php/7.3/fpm/php.ini
ENV nginx_conf /etc/nginx/nginx.conf
ENV supervisor_conf /etc/supervisor/supervisord.conf
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
RUN curl -sL https://deb.nodesource.com/setup_11.x | bash -

# Install nginx, php-fpm and supervisord from ubuntu repository
RUN apt-get update && apt-get install -y nginx mysql-server supervisor git build-essential debconf-utils nodejs unzip xvfb autogen autoconf libtool pkg-config nasm \
    php7.2-common php7.2-cli php7.2-fpm php7.2-curl php7.2-json php7.2-mysql php7.2-readline php7.2-sqlite3 php7.2-tidy php7.2-xmlrpc php7.2-xsl php7.2-zip php7.2-mbstring php7.2-gd php7.2-soap php7.2-opcache php7.2-xml php7.2-bcmath php7.2 php7.2-bz2

# Install composer
RUN curl https://getcomposer.org/composer.phar -o /usr/local/bin/composer && chmod +x /usr/local/bin/composer && composer self-update

# Enable php-fpm on nginx virtualhost configuration
COPY docker/invoiceneko-nginx.conf ${nginx_vhost}/
RUN echo "\ndaemon off;" >> ${nginx_conf}
RUN ln -s /etc/nginx/sites-available/invoiceneko-nginx.conf /etc/nginx/sites-enabled/invoiceneko-nginx.conf

#Copy supervisor configuration
COPY docker/supervisord.conf ${supervisor_conf}

RUN mkdir -p /run/php && \
    chown -R www-data:www-data /var/www/html && \
    chown -R www-data:www-data /run/php

# Clone InvoiceNeko
RUN git clone https://github.com/poowf/invoiceneko.git /var/www/html/invoiceneko

# Environment Setup
COPY docker/.env.docker ${INVOICENEKO_DIRECTORY}/.env

# Install package dependencies
RUN cd $INVOICENEKO_DIRECTORY && composer install --no-dev --no-interaction --prefer-dist --no-suggest

## Generating build assets
#RUN cd $INVOICENEKO_DIRECTORY && npm install && npm run prod

# Volume configuration
VOLUME ["/etc/nginx/sites-enabled", "/etc/nginx/certs", "/etc/nginx/conf.d", "/var/log/nginx", "/var/lib/mysql", "/var/www/html"]

# Configure Services and Port
ARG CACHE_BUST=0
COPY docker/start.sh /start.sh
CMD ["./start.sh"]

RUN mysql -e "CREATE DATABASE invoiceneko;"

# Running migrations
RUN cd $INVOICENEKO_DIRECTORY && php artisan key:generate && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan storage:link

EXPOSE 80 443