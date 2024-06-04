#!/bin/bash

set -e

cd /var/www/magento
rm -rf generated/ var/ vendor/ pub/static
sudo -u www-data /usr/bin/php /usr/bin/composer install
sudo -u www-data /usr/bin/php /var/www/magento/bin/magento setup:upgrade
sudo -u www-data /usr/bin/php /var/www/magento/bin/magento module:disable Magento_TwoFactorAuth
sudo -u www-data /usr/bin/php /var/www/magento/bin/magento setup:di:compile
sudo -u www-data /usr/bin/php /usr/bin/composer dump-autoload -o --apcu
sudo -u www-data /usr/bin/php /var/www/magento/bin/magento setup:static-content:deploy -f en_US --jobs=$(nproc)
