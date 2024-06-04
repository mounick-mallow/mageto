#!/bin/bash

set -e

cd /var/www/magento/
# Need require remove this files due problems while installing
rm -fr app/etc/env.php var generated pub/static vendor
sudo -u www-data /usr/bin/php /usr/bin/composer install
sudo -u www-data /usr/bin/php /var/www/magento/bin/magento setup:install \
    --base-url=http://localhost \
    --db-host=db \
    --db-name=magento \
    --db-user=admin \
    --db-password=admin \
    --admin-firstname=admin \
    --admin-lastname=admin \
    --admin-email=admin@admin.com \
    --admin-user=admin \
    --admin-password=admin123 \
    --language=uk_UA \
    --currency=USD \
    --timezone=Europe/Kyiv \
    --use-rewrites=1 \
    --search-engine=elasticsearch7 \
    --elasticsearch-host=elastic \
    --elasticsearch-port=9200 \
    --elasticsearch-index-prefix=magento \
    --elasticsearch-timeout=15
