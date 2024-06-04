define([
    'jquery', 
    'mageplaza/core/owl.carousel'
], function ($) {
    'use strict';
    return function (config) {
        var sliderOption = config.sliderOption;
        var sliderOptions =  sliderOption.replace(/'/g, '"');
        if(/Android|webOS|iPhone|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            setTimeout(function(){
                $('#bannerslider-list-items-'+config.sliderId).
                owlCarousel(
                    JSON.parse(sliderOptions)
                );

                var widthMb = parseFloat($('.page-wrapper').width()) - 30;
                $('.mp-banner-sidebar').attr('style','max-width: '+widthMb+'px');
             }, 3000);
        }else{
            $('#bannerslider-list-items-'+config.sliderId).
            owlCarousel(
               JSON.parse(sliderOptions)
            );
        }           

    }
});