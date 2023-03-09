FROM alpine:3.8

# Repository/Image Maintainer
MAINTAINER Jose Luiz <josejlpp@hotmail.com>

# Variables for enabling NewRelic
ENV FRAMEWORK=laravel \
    XDEBUG_ENABLED=true \
    NR_ENABLED=false \
    NR_APP_NAME="" \
    NR_LICENSE_KEY="" \
    TERM=xterm-256color \
    COLORTERM=truecolor \
    COMPOSER_PROCESS_TIMEOUT=1200

# Reset user to root to allow software install
USER root

# Add the ENTRYPOINT script
ADD php/start.sh /scripts/start.sh
ADD php/bashrc /home/ambientum/.bashrc

# Copy nginx and entry script
COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/ssl.conf /etc/nginx/ssl.conf
COPY nginx/sites /etc/nginx/sites
COPY nginx/start.sh  /home/ambientum/start.sh
COPY --from=madnight/alpine-wkhtmltopdf-builder:0.12.5-alpine3.10-606718795 \
    /bin/wkhtmltopdf /bin/wkhtmltopdf

# trust this project public key to trust the packages.
#ADD https://dl.bintray.com/php-alpine/key/php-alpine.rsa.pub /etc/apk/keys/php-alpine.rsa.pub

# make sure you can use HTTPS
RUN apk --update add ca-certificates

# add the repository, make sure you replace the correct versions if you want.
RUN echo "https://packages.whatwedo.ch/php-alpine/v3.8/php-7.2" >> /etc/apk/repositories


# Install PHP From DotDeb, Common Extensions, Composer and then cleanup
RUN echo "---> Enabling PHP-Alpine" && \
    apk --update --no-cache add \
    wget \
    curl \
    bash \
    fontconfig \
    imagemagick \
    libxrender \
    libxext \
    nano \
    vim \
    git \
    unzip \
    wget \
    make \
    libx11 \
    ttf-dejavu \
    ttf-droid \
    ttf-freefont \
    ttf-liberation \
    ttf-ubuntu-font-family \
    libintl \
    gcc \
    musl-dev \
    make \
#    wkhtmltopdf \
    sudo

RUN echo "---> Preparing and Installing PHP" && \
    apk add --update \
    php \
    php7-pear \
    php7-dev \
    php-bcmath \
    php-bz2 \
    php-calendar \
    php-curl \
    php-ctype \
    php-exif \
    php-fpm \
    php-gd \
    php-fileinfo \
    php-gmp \
    php-iconv \
    php-imap \
    php-intl \
    php-json \
    php-mbstring \
    php7-mcrypt \
    php-mysqlnd \
    php-pdo_mysql \
    php-opcache \
    php-pdo_pgsql \
    php-pgsql \
    php-posix \
    php-soap \
    php-sqlite3 \
    php-pdo_sqlite \
    php-tokenizer \
    php-xml \
    php-xmlreader \
    php-xmlwriter \
    php-openssl \
    php-phar \
    php-zip \
    php-zlib \
    php-pcntl \
    php-phpdbg

#RUN sudo ln -s /usr/bin/php7 /usr/bin/php

RUN echo "---> Installing Composer" && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer self-update --1 && \
    echo "---> Cleaning up" && \
    rm -rf /tmp/* && \
    echo "---> Adding the ambientum user" && \
    adduser -D -u 1000 ambientum && \
    mkdir -p /var/www/app && \
    ls -lahrt /etc/php7/conf.d/ && \
    chown -R ambientum:ambientum /var/www && \
    wget -O /tini https://github.com/krallin/tini/releases/download/v0.14.0/tini-static && \
    chmod +x /tini

RUN echo "---> Configuring PHP" && \
    ls -lahrt /etc/php7/conf.d && \
    echo "ambientum  ALL = ( ALL ) NOPASSWD: ALL" >> /etc/sudoers && \
    sed -i "/user = .*/c\user = ambientum" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/^group = .*/c\group = ambientum" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/listen.owner = .*/c\listen.owner = ambientum" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/listen.group = .*/c\listen.group = ambientum" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/listen = .*/c\listen = [::]:9000" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/;access.log = .*/c\access.log = /proc/self/fd/2" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/;clear_env = .*/c\clear_env = no" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/;catch_workers_output = .*/c\catch_workers_output = yes" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/pid = .*/c\;pid = /run/php/php7.1-fpm.pid" /etc/php7/php-fpm.conf && \
    sed -i "/;daemonize = .*/c\daemonize = yes" /etc/php7/php-fpm.conf && \
    sed -i "/error_log = .*/c\error_log = /proc/self/fd/2" /etc/php7/php-fpm.conf && \
    sed -i "/post_max_size = .*/c\post_max_size = 1000M" /etc/php7/php.ini && \
    sed -i "/upload_max_filesize = .*/c\upload_max_filesize = 1000M" /etc/php7/php.ini

# Install nginx from dotdeb (already enabled on base image)
RUN echo "--> Installing Nginx" && \
    apk add --update nginx openssl && \
    rm -rf /tmp/* /var/tmp/* /usr/share/doc/* && \
    echo "--> Fixing permissions" && \
    mkdir /var/tmp/nginx && \
    mkdir /var/run/nginx && \
    mkdir /home/ambientum/ssl && \
    chown -R ambientum:ambientum /var/tmp/nginx && \
    chown -R ambientum:ambientum /var/run/nginx && \
    chown -R ambientum:ambientum /var/log/nginx && \
    chown -R ambientum:ambientum /var/lib/nginx && \
    chmod +x /home/ambientum/start.sh && \
    chown -R ambientum:ambientum /home/ambientum

RUN echo "--> Installing Xdebug" && \
    pecl install xdebug-3.1.5

# Define the running user
USER ambientum

# Pre generate some SSL
# YOU SHOULD REPLACE WITH YOUR OWN CERT.
#RUN openssl req -x509 -nodes -days 3650 \
#   -newkey rsa:2048 -keyout /home/ambientum/ssl/nginx.key \
#   -out /home/ambientum/ssl/nginx.crt -subj "/C=AM/ST=Ambientum/L=Ambientum/O=Ambientum/CN=*.dev" && \
#   openssl dhparam -out /home/ambientum/ssl/dhparam.pem 2048

# Application directory
WORKDIR "/var/www/app"

# Environment variables
ENV PATH=/home/ambientum/.composer/vendor/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

# As non daemon and single base image, it may be used as cli container
CMD ["/bin/bash"]

# Expose webserver port
EXPOSE 8080

# Starts a single shell script that puts php-fpm as a daemon and nginx on foreground
CMD ["/home/ambientum/start.sh"]
