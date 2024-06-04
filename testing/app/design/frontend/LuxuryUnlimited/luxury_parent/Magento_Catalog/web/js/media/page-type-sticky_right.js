define([
    'jquery'
], function ($) {
    var product_info_top = 0;
    var product_image_box_pos = $(".product.media").offset().top;

    $(window).scroll(function(){
        product_image_box_pos = $(".product.media").offset().top;
        if ($(window).innerWidth() >= 992) {
            $(".product-info-main").each(function(){
                if (($(window).scrollTop() > product_image_box_pos - 50) &&
                    (product_image_box_pos + $(".product.media").outerHeight()) >
                    ($(window).scrollTop() + $(this).outerHeight() + 50)) {
                    product_info_top = $(window).scrollTop() - product_image_box_pos + 50;
                    $(this).css('top',product_info_top + 'px');
                } else if ($(window).scrollTop() < product_image_box_pos ||
                    $(".product.media").outerHeight() < $(this).outerHeight()) {
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
        if ($(window).innerWidth() >= 992) {
            $(".product-info-main").each(function(){
                if (($(window).scrollTop() > product_image_box_pos - 50)
                    && (product_image_box_pos + $(".product.media").outerHeight()) >
                    ($(window).scrollTop() + $(this).outerHeight() + 50)) {
                    product_info_top = $(window).scrollTop() - product_image_box_pos + 50;
                    $(this).css('top',product_info_top + 'px');
                } else if ($(window).scrollTop() < product_image_box_pos ||
                    $(".product.media").outerHeight() < $(this).outerHeight()) {
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
});
