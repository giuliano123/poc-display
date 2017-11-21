FROM debian:jessie

ARG PROJECT_ENV=prod

ENV TERM="xterm" \
    DEBIAN_FRONTEND="noninteractive" \
    COMPOSER_ALLOW_SUPERUSER=1

EXPOSE 80
WORKDIR /var/www/poc-display
STOPSIGNAL SIGQUIT

RUN apt-get update -q \
    && export LC_ALL=C \
    && apt-get install -f locales \
    && apt-get install -qy apt-transport-https lsb-release ca-certificates wget software-properties-common \
    && wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
    && echo "deb https://packages.sury.org/php/ jessie main" > /etc/apt/sources.list.d/php.list \
    && apt-get update -q \
    && apt-get install -qy --no-install-recommends \
        nginx \
        supervisor \
        libjpeg-dev \
        libpng12-dev \
        curl \

        php7.1 \
        php7.1-common \
        php7.1-fpm \
        php7.1-mysql \
        php7.1-phar \
        php7.1-curl \
        php7.1-json \
        php7.1-gd \
        php7.1-iconv \
        php7.1-mcrypt \
        php7.1-intl \
        php7.1-mbstring \
        php7.1-redis \
        php7.1-xml \
        php7.1-zip \
        php7.1-opcache \
        php7.1-soap \
        php-imagick \

    # Install uglify js and css
    && curl -sL https://deb.nodesource.com/setup_6.x | bash - \
    && apt-get -y install nodejs --no-install-recommends \
    && npm install -g uglify-es \
    && npm install -g uglifycss \

    # less for font-awesome
    && npm install -g less \

    # composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \

    # locales and timezone
    && cp /usr/share/zoneinfo/Europe/Paris /etc/localtime \
    && echo "Europe/Paris" > /etc/timezone \

    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log \

    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY ./docker/${PROJECT_ENV}/php/php.ini /etc/php/7.1/fpm/php.ini
COPY ./docker/${PROJECT_ENV}/php/www.conf /etc/php/7.1/fpm/pool.d/www.conf
COPY ./docker/${PROJECT_ENV}/php/opcache-recommended.ini /usr/local/etc/php/conf.d/opcache-recommended.ini
COPY ./docker/${PROJECT_ENV}/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/${PROJECT_ENV}/nginx/conf/* /etc/nginx/

RUN sed -i -e 's/# fr_FR.UTF-8 UTF-8/fr_FR.UTF-8 UTF-8/' /etc/locale.gen && \
    locale-gen

# CODE
RUN mkdir -p /var/www/poc-display \
    && mkdir -p /var/www/poc-display/web/css \
    && mkdir -p /var/www/poc-display/web/js \
    && mkdir -p /var/run/php

COPY . /var/www/poc-display

RUN echo "parameters:" > /var/www/poc-display/app/config/assets_version.yml \
    && echo "    assets_version: "$(date +%s) >> /var/www/poc-display/app/config/assets_version.yml

RUN chown -R www-data:www-data /var/www/poc-display/var/cache/ /var/www/poc-display/var/logs/ /var/www/poc-display/var/sessions/ /var/run/php

CMD ["/var/www/poc-display/docker/script/env.sh"]
