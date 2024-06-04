# Magento 2.0 SmartWave Filterproducts extension

This extension filtering product collection for Best Seller List, Feature List, Latest List and Sale list.
This module must be connected to frontend templates.

## Installation with composer

-   Include the repository: `composer require smartwave/module-filterproducts`
-   Enable the extension: `php bin/magento --clear-static-content module:enable SmartWave_Filterproducts`
-   Upgrade db scheme: `php bin/magento setup:upgrade`
-   Clear cache

## Installation without composer

-   Download zip file of this extension
-   Place all the files of the extension in your Magento 2 installation in the folder `app/code/SmartWave/Filterproducts`
-   Enable the extension: `php bin/magento --clear-static-content module:enable SmartWave_Filterproducts`
-   Upgrade db scheme: `php bin/magento setup:upgrade`
-   Clear cache
