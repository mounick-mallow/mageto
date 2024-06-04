FROM debian:bullseye-slim

USER root

COPY ./.docker/install-fpm.sh /usr/bin/install-fpm
COPY ./.docker/build.sh /usr/bin/build
COPY ./.docker/install.sh /usr/bin/install
RUN apt update -y && apt install npm -y
RUN npm install -g magepack
RUN set -ex \
    && apt update \
    && apt -y --no-install-recommends --no-install-suggests install nano sudo git \
    && /usr/bin/install-fpm \
    && usermod -u 1000 www-data && groupmod -g 1000 www-data \
    && mkdir -p /var/www/.composer \
    && mkdir -p /var/www/.ssh \
    && echo -e "Host github.com\n\tStrictHostKeyChecking no\n" >> /var/www/.ssh/config \
    && chown -R www-data:www-data /var/www

# Comment this string to enable Xdebug and run docker compose up --build
RUN rm -rf /etc/php/8.1/conf.d/xdebug.ini

CMD /usr/bin/php-fpm --nodaemonize --fpm-config /etc/php/8.1/php-fpm.conf
