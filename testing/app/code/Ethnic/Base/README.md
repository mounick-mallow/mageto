# Mage2 Module Ethnic Base

    ``ethnic/base``

-   [Main Functionalities](#Main Functionalities)
-   [Installation](#Installation)

## Main Functionalities

Module extend functionality of native ForgotPasswordPost controller.
In overridden controller added redirect to Success page in the case of success.
After installing the module starts working automatically.

## Installation

-   Enable the module by running `php bin/magento module:enable Ethnic_Base`
-   Apply database updates by running `php bin/magento setup:upgrade`
-   Flush the cache by running `php bin/magento cache:flush`
