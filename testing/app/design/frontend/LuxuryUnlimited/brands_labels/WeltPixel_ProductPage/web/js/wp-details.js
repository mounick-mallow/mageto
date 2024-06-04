define([
    'jquery', 
    'productPage'
], function ($, productPage) {
    'use strict';
    return function (config) {
    
        $(document).ready(function() {
            /** pre-load product reviews */
            window.reviewUrl = config.reviewUrl;
            var tabsLayout = config.tabsLayout;
            
            if (tabsLayout === 'list') {
                productPage.preLoadProductReviews(function() {});
            }

            /** have to wait until all the images are loaded */
            $('[data-gallery-role=gallery-placeholder]').on('gallery:loaded', function () {
                $(this).on('fotorama:ready', function(){
                    productPage.scrollToUrlHash(window.location.href);
                });
            });

            $('.reviews-actions a.action').on('click', function() {
                productPage.scrollToUrlHash($(this).attr('href'));
            });
        });

        $(".cls_heading").click(function(){
            jQuery( ".cls_holecontent" ).slideToggle('slow');
            jQuery(".cls_heading").toggleClass("active");
        });
    }
});