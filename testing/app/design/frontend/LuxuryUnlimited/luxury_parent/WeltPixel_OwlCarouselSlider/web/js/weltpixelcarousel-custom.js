define([
    'jquery', 
    'underscore', 
    'owl_carousel', 
    'owl_config' 
],
function ($, _) {
    'use strict';

    return function(config) {

        $(document).ready(function() {
            var imageAdjustmentRequired = config.imageAdjustmentRequired;
            var mobileBreakPoin = config.mobileBreakPoin;
            var slider_id = config.sliderId;

            var sliderOption = config.sliderConfig;       
            var sliderOptions =  sliderOption.replace(/'/g, '"');
            var slider_config = JSON.parse(sliderOptions);
            
            var breakpoint1 = config.breakpoint1;
            var breakpoint2 = config.breakpoint2;
            var breakpoint3 = config.breakpoint3;
            var breakpoint4 = config.breakpoint4;

            var items = ((slider_config.items >= 0 && slider_config.items != null) ? slider_config.items : 1);
            if(slider_config.transition != 'slide') {
                items = 1;
            }
            var stagePadding = slider_config.stagePadding != '' ? parseInt(slider_config.stagePadding) : 0;
            var animate_Out = slider_config.transition != 'fadeOut' ? true : false;

            if (imageAdjustmentRequired == "1") {
                var mobileBreakPoint = mobileBreakPoin;
                
                function adjustOwlImages() {
                    var windowWidth = $(window).width();

                    $('.banner-image img').each(function () {
                        if (windowWidth < mobileBreakPoint) {
                            if ($(this).attr('data-src-mobile')) {
                                if ($(this).attr('data-src-retina')) {
                                    $(this).attr('data-src', $(this).attr('data-src-mobile'));
                                    $(this).attr('data-src-retina', $(this).attr('data-src-mobile'));
                                }
                            }
                            if ($(this).attr('src')) {
                                $(this).attr('src', $(this).attr('data-src-mobile'));
                            }
                        } else {
                            if ($(this).attr('data-src-desktop')) {
                                if ($(this).attr('data-src-retina')) {
                                    $(this).attr('data-src', $(this).attr('data-src-desktop'));
                                    $(this).attr('data-src-retina', $(this).attr('data-src-desktop'));
                                }
                            }
                            if ($(this).attr('src')) {
                                $(this).attr('src', $(this).attr('data-src-desktop'));
                            }
                        }
                    });
                }

                $('.owl-carousel-custom-'+slider_id).on('resized.owl.carousel', function (event) {
                    var $this = $(this);
                    $this.find('.owl-height').css('height', $this.find('.owl-item.active').height());
                });

                $('.owl-carousel-custom-'+slider_id).on('changed.owl.carousel', function (event) {
                    var $that = $(this);
                    setTimeout(function(){
                        $that.find('.owl-height').css('height', $that.find('.owl-item.active').height());
                    }, 1);
                });

                $(window).resize(function(){
                    adjustOwlImages();
                });

                adjustOwlImages();
            }

            /** Lazyload bug when fewer items exist in the carousel then the ones displayed */
            $('.owl-carousel-custom-'+slider_id).on('initialized.owl.carousel', function(event){
                var scopeSize = event.page.size;
                for (var i = 0; i < scopeSize; i++){
                    var imgsrc = $(event.target).find('.owl-item').eq(i).find('img').attr('data-src');
                    if ($(event.target).find('.owl-item').eq(i).find('img').attr('src')) {
                        $(event.target).find('.owl-item').eq(i).find('img').attr('src', imgsrc);
                        $(event.target).find('.owl-item').eq(i).find('img').attr('style', 'opacity: 1;');
                    }
                }
            });

            $('.owl-carousel-custom-'+slider_id).on('loaded.owl.lazy', function (event) {
                var $that = $(this);
                setTimeout(function(){
                    $that.find('.owl-height').css('height', $that.find('.owl-item.active').height());
                }, 1);
            });

            $('.owl-carousel-custom-'+slider_id).on('initialized.owl.carousel', function(event) {
                setTimeout(function(){
                    $('.owl-thumbs').each(function() {
                        if (!$('.owl-thumbs').children().length) {$(this).remove();}
                    });
                    $('.cssload-loader').parent().remove();
                }, 400);
            });

            var options = {
                thumbs:            parseInt(slider_config.thumbs) == 1 ? true : false,
                thumbsPrerendered: parseInt(slider_config.thumbs) == 1 ? true : false,
                nav               :parseInt(slider_config.nav) == 1 ? true : false,
                dots              :parseInt(slider_config.dots) == 1 ? true : false,
                center            :(slider_config.center == 1 && animate_Out) ? true : false,
                items             :items,
                loop              :parseInt(slider_config.loop) == 1 ? true : false,
                margin            :(slider_config.margin != '' && animate_Out) ? parseInt(slider_config.margin) : 0,
                stagePadding      :parseInt(slider_config.center) == 1 ? 0 : stagePadding,
                lazyLoad          :parseInt(slider_config.lazyLoad) == 1 ? true : false,
                autoplay          :parseInt(slider_config.autoplay) == 1 ? true : false,
                autoplayTimeout   :(parseInt(slider_config.autoplayTimeout) > 0 && slider_config.autoplayTimeout != null) ? parseInt(slider_config.autoplayTimeout) : 3000,
                autoplayHoverPause:parseInt(slider_config.autoplayHoverPause) == 1 ? true : false,
                autoHeight        :parseInt(slider_config.autoHeight) == 1 ? true : false,
                animateOut        :slider_config.transition == 'slide' ? false : slider_config.transition,


                responsive:{
                    1 :{
                        nav     :parseInt(slider_config.nav_brk1) == 1 ? true : false,
                        items   :parseInt(slider_config.items_brk1  >= 0 ? slider_config.items_brk1 : 0),
                    },
                    2 :{
                        nav     :parseInt(slider_config.nav_brk2) == 1 ? true : false,
                        items   :parseInt(slider_config.items_brk2  >= 0 ? slider_config.items_brk2 : 0),
                    },
                    3 :{
                        nav     :parseInt(slider_config.nav_brk3) == 1 ? true : false,
                        items   :parseInt(slider_config.items_brk3  >= 0 ? slider_config.items_brk3 : 0),
                    },
                    4 :{
                        nav     :parseInt(slider_config.nav_brk4) == 1 ? true : false,
                        items   :parseInt(slider_config.items_brk4  >= 0 ? slider_config.items_brk4 : 0),
                    }
                }
            };

            options.responsive[breakpoint1] = options.responsive[1];
            options.responsive[breakpoint2] = options.responsive[2];
            options.responsive[breakpoint3] = options.responsive[3];
            options.responsive[breakpoint4] = options.responsive[4];
            delete options.responsive[1];
            delete options.responsive[2];
            delete options.responsive[3];
            delete options.responsive[4];

            $('.owl-carousel-custom-'+slider_id).owlCarousel(options);
        });
    }
});