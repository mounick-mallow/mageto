#!/bin/bash

php -dmemory_limit=-1 bin/magento setup:upgrade 

exec "$@"