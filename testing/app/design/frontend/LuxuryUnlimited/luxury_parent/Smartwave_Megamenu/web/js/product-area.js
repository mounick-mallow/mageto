define([
    'jquery',
    'owl.carousel/owl.carousel.min'
], function ($) {
'use strict';
    return function (config) {
        $(document).ready(function() {
            var categoryId = config.categoryId;
            $("#cat_prod_"+categoryId+" .owl-carousel").owlCarousel({
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                loop: true,
                navRewind: true,
                margin: 10,
                nav: false,
                navText: ["<em class='porto-icon-left-open-huge'></em>","<em class='porto-icon-right-open-huge'></em>"],
                dots: true,
                lazyLoad: true,
                responsive: window.columnsOwlCarousel
            });
        });
    }
});