FROM ubuntu:20.04 as base

EXPOSE 8080

WORKDIR /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    software-properties-common \
    tzdata \
    && LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
    nginx \
    supervisor \
    unzip \
    curl \
    php8.1-pgsql \
    php8.1-fpm \
    php8.1-cli \
    php8.1-xml \
    php8.1-gd \
    php8.1-mbstring \
    php8.1-curl \
    php8.1-soap \
    php8.1-zip \
    php8.1-redis \
    php8.1-intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY ../docker/app/entrypoint.sh /entrypoint.sh
COPY ../docker/app/nginx.conf docker/app/fastcgi_params docker/app/gzip_params /etc/nginx/
COPY ../docker/app/nginx-app.conf docker/app/nginx-http.conf /etc/nginx/conf.d/
COPY ../docker/app/php-cli.ini /etc/php/8.1/cli/
COPY ../docker/app/php.ini docker/app/php-fpm.conf /etc/php/8.1/fpm/
COPY ../docker/app/supervisord.conf /etc/supervisor/supervisord.conf

ENTRYPOINT ["/bin/bash", "/entrypoint.sh"]

CMD ["/usr/bin/supervisord"]

COPY ../docker/app/xdebug.ini /etc/php/8.1/fpm/conf.d/20-xdebug.ini
