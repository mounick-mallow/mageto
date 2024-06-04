# Mage2 Module Ascure Helperblock

    ``ascure/helperblock``

-   [Main Functionalities](#Main Functionalities)
-   [Installation](#Installation)
-   [Composer](#Composer)

## Main Functionalities

Module adds link "Size Chart" to Product Details Page

## Installation

-   The module will be in vendor/ludxb/magento-meta-package
-   Enable the module by running `php bin/magento module:enable Ascure_Helperblock`
-   Apply database updates by running `php bin/magento setup:upgrade --keep-generated`
-   Flush the cache by running `php bin/magento cache:flush`

## Composer

-   The module available in a composer repository: https://github.com/ludxb/magento-meta-package.git
-   Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
-   Install the module composer by running `composer require custom/module-navchange`
-   enable the module by running `php bin/magento module:enable Custom_Navchange`
-   apply database updates by running `php bin/magento setup:upgrade --keep-generated`
-   Flush the cache by running `php bin/magento cache:flush`
