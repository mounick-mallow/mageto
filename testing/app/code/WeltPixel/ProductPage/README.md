# m2-weltpixel-product-page

### Installation

Dependencies:

-   m2-weltpixel-backend
-   m2-weltpixel-frontend-options

With composer:

```sh
$ composer config repositories.welpixel-m2-weltpixel-product-page git git@github.com:rusdragos/m2-weltpixel-product-page.git
$ composer require weltpixel/m2-weltpixel-product-page:dev-master
```

Note: Composer installation only available for WeltPixel internal use for the moment as the repositos are not public. However, there is a work around that will allow you to install the product via composer, described in the article below: https://support.weltpixel.com/hc/en-us/articles/115000216654-How-to-use-composer-and-install-Pearl-Theme-or-other-WeltPixel-extensions

Manually:

Copy the zip into app/code/WeltPixel/ProductPage directory

#### After installation by either means, enable the extension by running following commands:

```sh
$ php bin/magento module:enable WeltPixel_ProductPage --clear-static-content
$ php bin/magento setup:upgrade
```
