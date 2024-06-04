define([
    'jquery', 
    'productPage', 
    'mage/mage', 
    'mage/gallery/gallery'
], function ($, productPage) {
    'use strict';
    return function (config) {
        window.positionProductInfo = config.positionProductInfo;
        window.isMobile = config.isMobile;

        var positionProductInfo = config.positionProductInfo;
        var isMobileCheck = productPage.isMobileCheck();


        $(document).ready(function () {
            productPage.init();
        });

        $(window).load(function () {
            productPage.load();
            jQuery('html, body').animate({scrollTop: 0}, 'fast');
            if (positionProductInfo == 1 && !isMobileCheck) {
                productPage.bindStickyScroll();
            }
            $('.product-info-main').removeClass('pp-floating-v4');
            if(!isMobileCheck && $('.product-info-main').hasClass('product_v4')) {
                $('.product-info-main').addClass('pp-floating-v4');
            }

        });

        $(document).ajaxComplete(function () {
            productPage.ajaxComplete();
        });


        var reinitTimer;
        $(window).on('resize', function () {
            clearTimeout(reinitTimer);
            reinitTimer = setTimeout(productPage.resize(), 100);
            if (positionProductInfo == 1 && !isMobileCheck) {
                productPage.bindStickyScroll();
            }
        });
    }
});