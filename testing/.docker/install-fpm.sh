#!/usr/bin/env bash

set -e

PHP_V=8.1.18
SRC_DIR=/usr/local/src/php8.1-build
PREFIX=/usr
SYSCONFDIR=/etc/php/8.1
export EXTENSION_DIR=$PREFIX/lib/php/8.1/modules
export PEAR_INSTALLDIR=$PREFIX/share/php/8.1/pear
export CFLAGS="-s -march=native -O2"
export CPPFLAGS="-s -march=native -O2"

apt update
apt full-upgrade -y
apt install -y --no-install-recommends --no-install-suggests \
    autoconf dpkg-dev g++ gcc libc6-dev make pkg-config  \
    libcurl4-openssl-dev libsqlite3-dev libssl-dev libonig-dev libsodium-dev \
    libxml2-dev zlib1g-dev libzip-dev libjpeg-dev libpng-dev \
    libfreetype6-dev libxslt1-dev libxpm-dev libjpeg62-turbo-dev \
    wget \
    \
    dpkg libc6 ca-certificates curl xz-utils libcurl4 libsqlite3-0 libssl1.1 \
    libxml2 zlib1g libzip4 libpng16-16 libonig5 libsodium23 \
    libfreetype6 libxslt1.1 libxpm4 libx11-6 libjpeg62-turbo unzip

wget -O /usr/bin/composer "https://getcomposer.org/download/2.5.5/composer.phar"
chmod +x /usr/bin/composer

MAIN_CONF="--config-cache \
--prefix=$PREFIX \
--sbindir=$PREFIX/bin \
--sysconfdir=$SYSCONFDIR \
--localstatedir=/var \
--with-layout=GNU \
--enable-option-checking=fatal \
--with-config-file-path=$SYSCONFDIR \
--with-config-file-scan-dir=$SYSCONFDIR/conf.d \
--disable-rpath \
--mandir=$PREFIX/share/man \
--with-pear \
--disable-cgi \
--disable-debug \
--disable-phpdbg \
--enable-pcntl \
--enable-fpm --with-fpm-user=www-data --with-fpm-group=www-data \
--with-libdir=/usr/lib/x86_64-linux-gnu"

ESSENTIAL_EXT="--enable-opcache \
--enable-mbstring \
--enable-phar \
--enable-intl \
--enable-soap \
--enable-bcmath \
--enable-gd \
--enable-sockets \
--with-zip \
--with-openssl \
--with-curl \
--with-mysqli=mysqlnd \
--with-pdo-mysql=mysqlnd \
--with-jpeg \
--with-xpm \
--with-freetype \
--with-sodium \
--with-xsl=/usr \
--with-zlib"

mkdir -p $SRC_DIR $SYSCONFDIR $SYSCONFDIR/conf.d $EXTENSION_DIR $PEAR_INSTALLDIR /var/www

chown www-data:www-data /var/www
chmod 755 /var/www

cd $SRC_DIR
wget https://www.php.net/distributions/php-$PHP_V.tar.xz -O - | tar xJ
cd $SRC_DIR/php-$PHP_V
./configure ${MAIN_CONF} ${ESSENTIAL_EXT}
make -j$(nproc)
make install

mv -v $SYSCONFDIR/php-fpm.d/www.conf.default $SYSCONFDIR/php-fpm.d/www.conf
mv -v $SYSCONFDIR/php-fpm.conf.default $SYSCONFDIR/php-fpm.conf
cp -v $SRC_DIR/php-$PHP_V/php.ini-production $SYSCONFDIR/php.ini
cp -v $SRC_DIR/php-$PHP_V/sapi/fpm/php-fpm.service /etc/systemd/system/php-fpm.service

echo 'memory_limit = 1024M' > $SYSCONFDIR/conf.d/memory.ini
echo 'fastcgi.logging=0' > $SYSCONFDIR/conf.d/logger.ini
echo '[www]
catch_workers_output = 1' > $SYSCONFDIR/php-fpm.d/zz-logger.conf

mkdir /var/run/php/
chown www-data:www-data /var/run/php/
chmod 755 /var/run/php/

echo '[www]
listen = /var/run/php/php-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0666' > $SYSCONFDIR/php-fpm.d/zz-fpm-socket.conf

echo "zend_extension=opcache.so
opcache.enable_cli=1
opcache.enable=1" > $SYSCONFDIR/conf.d/opcache.ini

printf "\n" | pecl install redis apcu xdebug
echo "extension=redis.so" > $SYSCONFDIR/conf.d/redis.ini
echo "extension=apcu.so
apc.enable_cli=1
apc.enabled=1" > $SYSCONFDIR/conf.d/apcu.ini
echo "zend_extension=xdebug.so
xdebug.client_host=172.17.0.1
xdebug.client_port=9003
xdebug.remote_handler=dbgp
xdebug.remote_mode=req
xdebug.remote_connect_back=0
xdebug.mode = debug
xdebug.idekey=PHPSTORM
xdebug.start_with_request = 1" > $SYSCONFDIR/conf.d/xdebug.ini

apt autoremove --purge -y autoconf dpkg-dev g++ gcc libc6-dev make pkg-config  \
    libcurl4-openssl-dev libsqlite3-dev libssl-dev libonig-dev \
    libxml2-dev zlib1g-dev libzip-dev libjpeg-dev libpng-dev \
    libfreetype6-dev libxslt1-dev libxpm-dev libjpeg62-turbo-dev libsodium-dev
apt clean
pecl clear-cache
rm -rf $SRC_DIR /var/lib/apt/lists/* /tmp/pear/

ln -sf /dev/stdout /var/log/php-fpm.log
ln -sf /dev/stderr /var/log/php-fpm.log
