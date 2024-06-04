define([
    'jquery',
    'mage/mage',
    'Magento_Catalog/product/view/validation',
    'Magento_Catalog/js/catalog-add-to-cart'
], function ($) {
    'use strict';
    return function (config) {

        $('#product_addtocart_form').mage('validation', {
            radioCheckboxClosest: '.nested',
            submitHandler: function (form) {
                var widget = $(form).catalogAddToCart({
                    bindSubmit: false
                });

                widget.catalogAddToCart('submitForm', $(form));
                return false;
            }
        });
    }
});
