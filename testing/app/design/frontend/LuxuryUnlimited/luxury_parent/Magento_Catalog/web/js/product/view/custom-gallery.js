define([
    'jquery',
    'owl.carousel/owl.carousel.min',
    'Magento_Catalog/js/jquery.zoom.min'
], function ($) {
    'use strict';

    return function(config) {
        var pageType = config.pageType;
        if (pageType == 'carousel') {
            carouselGalleryImages();
        } else if (pageType == 'sticky_right') {
            scrollPageProduct();
        } else if (pageType == 'fullwidth') {
            scrollPageProductFullWidth()
        } else {
            galleryPlaceHolder();
        }
    }

    function carouselGalleryImages() {
        $(document).ready(function() {
            $("#gallery_images").owlCarousel({
                autoplay: false,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                loop: true,
                navRewind: true,
                margin: 0,
                nav: true,
                navText: ["<em class='porto-icon-left-open-huge'></em>","<em class='porto-icon-right-open-huge'></em>"],
                dots: false,
                responsive: {
                  0: {
                    items:1
                  },
                  768: {
                    items:1
                  },
                  992: {
                    items:2
                  },
                  1200: {
                    items:3
                  }
                }
            });

            $(".product-info-main > .product-info-price").before($(".short-custom-block").show().detach());
            $(".page-main").after($(".fullwidth-custom-block").show().detach());
            $(".product-info-main > .prev-next-products").after($(".product-social-links").detach());
        });        
    }


    function scrollPageProduct() {
        var product_info_top = 0;
        var product_image_box_pos = $(".product.media").offset().top;

        $(window).scroll(function(){
            product_image_box_pos = $(".product.media").offset().top;
            if($(window).innerWidth() >= 992) {
                $(".product-info-main").each(function(){
                    if(($(window).scrollTop() > product_image_box_pos - 50) && (product_image_box_pos + $(".product.media").outerHeight()) > ($(window).scrollTop() + $(this).outerHeight() + 50)) {
                        product_info_top = $(window).scrollTop() - product_image_box_pos + 50;
                        $(this).css('top',product_info_top + 'px');
                    } else if ($(window).scrollTop() < product_image_box_pos || $(".product.media").outerHeight() < $(this).outerHeight()) {
                        product_info_top = 0;
                        $(this).css('top',product_info_top + 'px');
                    } else {
                        product_info_top = $(".product.media").outerHeight() - $(this).outerHeight();
                        $(this).css('top',product_info_top + 'px');
                    }
                });
            } else {
                product_info_top = 0;
                $(".product-info-main").css('top',product_info_top + 'px');
            }
        });

        $(window).resize(function(){
            product_image_box_pos = $(".product.media").offset().top;
            if($(window).innerWidth() >= 992) {
                $(".product-info-main").each(function(){
                    if(($(window).scrollTop() > product_image_box_pos - 50) && (product_image_box_pos + $(".product.media").outerHeight()) > ($(window).scrollTop() + $(this).outerHeight() + 50)) {
                        product_info_top = $(window).scrollTop() - product_image_box_pos + 50;
                        $(this).css('top',product_info_top + 'px');
                    } else if ($(window).scrollTop() < product_image_box_pos || $(".product.media").outerHeight() < $(this).outerHeight()) {
                        product_info_top = 0;
                        $(this).css('top',product_info_top + 'px');
                    } else {
                        product_info_top = $(".product.media").outerHeight() - $(this).outerHeight();
                        $(this).css('top',product_info_top + 'px');
                    }
                });
            } else {
                product_info_top = 0;
                $(".product-info-main").css('top',product_info_top + 'px');
            }
        });
    }

    function scrollPageProductFullWidth() {
        var product_image_box_top = 0;
        var product_info_pos = $(".product-info-main").offset().top;

        $(document).ready(function(){
            $(".product.info.detailed").detach().appendTo($(".product-info-main"));
            $(".page-main").after($(".fullwidth-custom-block").show().detach());
        });

        $(window).scroll(function(){
            product_info_pos = $(".product-info-main").offset().top;
            if($(window).innerWidth() >= 768) {
                $(".product.media").each(function(){
                    if(($(window).scrollTop() > product_info_pos - 50) && (product_info_pos + $(".product-info-main").outerHeight()) > ($(window).scrollTop() + $(this).outerHeight() + 50)) {
                        product_image_box_top = $(window).scrollTop() - product_info_pos + 50;
                        $(this).css('top',product_image_box_top + 'px');
                    } else if ($(window).scrollTop() < product_info_pos) {
                        product_image_box_top = 0;
                        $(this).css('top',product_image_box_top + 'px');
                    }
                });
            } else {
                product_image_box_top = 0;
                $(".product.media").css('top',product_image_box_top + 'px');
            }
        });

        $(window).resize(function(){
            product_info_pos = $(".product-info-main").offset().top;
            if($(window).innerWidth() >= 768) {
                $(".product.media").each(function(){
                    if(($(window).scrollTop() > product_info_pos - 50) && (product_info_pos + $(".product-info-main").outerHeight()) > ($(window).scrollTop() + $(this).outerHeight() + 50)) {
                        product_image_box_top = $(window).scrollTop() - product_info_pos + 50;
                        $(this).css('top',product_image_box_top + 'px');
                    } else if ($(window).scrollTop() < product_info_pos) {
                        product_image_box_top = 0;
                        $(this).css('top',product_image_box_top + 'px');
                    }
                });
            } else {
                product_image_box_top = 0;
                $(".product.media").css('top',product_image_box_top + 'px');
            }
        });
    }


    function galleryPlaceHolder() {
        $(document).ready(function() {
            var loaded = false;
            $('.product.media .gallery-placeholder').bind("DOMSubtreeModified",function(){
                $('.product.media .fotorama').on('fotorama:ready', function (e, fotorama, extra) {
                    loaded = false;
                    $('.product.media .fotorama').on('fotorama:load', function (e, fotorama, extra) {
                        if(!loaded){
                            $('.product.media .fotorama__stage .fotorama__loaded--img').trigger('zoom.destroy');
                            $('.product.media .fotorama__stage .fotorama__active').zoom({
                                touch:false
                            });
                            loaded = true;
                        }
                    });
                    $('.product.media .fotorama').on('fotorama:showend', function (e, fotorama, extra) {
                        $('.product.media .fotorama__stage .fotorama__active').zoom({
                            touch:false
                        });
                    });
                    $('.fotorama').off('fotorama:fullscreenenter').on('fotorama:fullscreenenter', function (e, fotorama, extra) {
                        $('.product.media .fotorama__stage .fotorama__loaded--img').trigger('zoom.destroy');
                        $('img.zoomImg').remove();
                    });
                    $('.fotorama').off('fotorama:fullscreenexit').on('fotorama:fullscreenexit', function (e, fotorama, extra) {
                        $('.product.media .fotorama__stage .fotorama__loaded--img').trigger('zoom.destroy');
                        $('img.zoomImg').remove();
                        $('img.fotorama__img').not('.fotorama__img--full').each(function(){
                            $(this).after($(this).parent().children("img.fotorama__img--full"));
                        });
                        $('.product.media .fotorama__stage .fotorama__active').zoom({
                            touch:false
                        });
                        $('.product.media .fotorama').off('fotorama:showend').on('fotorama:showend', function (e, fotorama, extra) {
                            $('.product.media .fotorama__stage .fotorama__loaded--img').trigger('zoom.destroy');
                            $('.product.media .fotorama__stage .fotorama__active').zoom({
                                touch:false
                            });
                        });
                    });
                });
            });
        });
    }

});
