FROM surnet/alpine-wkhtmltopdf:3.13.5-0.12.6-full as wkhtmltopdf
FROM node:14.17.6-alpine3.14 as base
WORKDIR /srv/http/www/invoiceneko/

# Essentials
RUN echo "UTC" > /etc/timezone
RUN apk add --no-cache \
    zip \
    unzip \
    curl \
    sqlite \
    nginx \
    supervisor \
    git \
    g++ \
    make \
    bash \
    zlib-dev \
    libpng-dev \
    autoconf \
    automake \
    libtool \
    tiff \
    jpeg \
    zlib \
    pkgconf \
    nasm \
    file \
    gcc \
    musl-dev \
    libltdl \
    python2 \
    xvfb \
    xvfb-run \
    libstdc++ \
    libx11 \
    libxrender \
    libxext \
    libssl1.1 \
    ca-certificates \
    fontconfig \
    freetype \
    ttf-dejavu \
    ttf-droid \
    ttf-freefont \
    ttf-liberation \
    dbus \
    chromium \
    chromium-chromedriver

RUN apk add --no-cache --virtual .build-deps \
    msttcorefonts-installer \
    \
    # Install microsoft fonts
    && update-ms-fonts \
    && fc-cache -f \
    \
    # Clean up when done
    && rm -rf /tmp/* \
    && apk del .build-deps

COPY --from=wkhtmltopdf /bin/wkhtmltopdf /usr/bin/wkhtmltopdf
COPY --from=wkhtmltopdf /bin/wkhtmltoimage /usr/bin/wkhtmltoimage
COPY --from=wkhtmltopdf /bin/libwkhtmltox* /usr/bin/

# Installing PHP
RUN apk add --no-cache php8 \
    php8-common \
    php8-fpm \
    php8-pdo \
    php8-opcache \
    php8-zip \
    php8-phar \
    php8-iconv \
    php8-cli \
    php8-curl \
    php8-openssl \
    php8-mbstring \
    php8-tokenizer \
    php8-fileinfo \
    php8-json \
    php8-xml \
    php8-xmlwriter \
    php8-simplexml \
    php8-dom \
    php8-pdo_mysql \
    php8-pdo_sqlite \
    php8-tokenizer \
    php8-pecl-redis \
    php8-bcmath \
    php8-ctype \
    php8-gd \
    php8-pcntl \
    php8-posix \
    php8-xmlreader \
    php8-intl

# Installing composer
RUN ln -s /usr/bin/php8 /usr/bin/php
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php

# Configure supervisor
RUN mkdir -p /etc/supervisor.d/

# Configure php-fpm
RUN mkdir -p /run/php/
RUN touch /run/php/php8.0-fpm.pid
RUN touch /run/php/php8.0-fpm.sock
RUN sed -i 's~listen = 127.0.0.1:9000~listen = /run/php/php8.0-fpm.sock~g' /etc/php8/php-fpm.d/www.conf
RUN sed -i 's~;listen.owner = nobody~listen.owner = nginx~g' /etc/php8/php-fpm.d/www.conf
RUN sed -i 's~;listen.group = nobody~listen.group = nginx~g' /etc/php8/php-fpm.d/www.conf
RUN sed -i 's~;listen.mode = 0660~listen.mode = 0660~g' /etc/php8/php-fpm.d/www.conf
RUN sed -i 's~user = nobody~user = nginx~g' /etc/php8/php-fpm.d/www.conf
RUN sed -i 's~group = nobody~group = nginx~g' /etc/php8/php-fpm.d/www.conf

# Configure nginx
RUN echo "daemon off;" >> /etc/nginx/nginx.conf

RUN mkdir -p /run/nginx/
RUN touch /run/nginx/nginx.pid

RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log

# Building process
COPY --chown=nginx:nginx . .
RUN composer install

# Configure Laravel logs
RUN ln -sf /dev/stdout /srv/http/www/invoiceneko/storage/laravel.log

EXPOSE 80
CMD ["supervisord", "-c", "/etc/supervisor.d/supervisord.conf"]
