define([
    'jquery',
    'domReady!',
], function($){
    $(".product.info.detailed").detach().appendTo($(".product-info-main"));
    $(".page-main").after($(".fullwidth-custom-block").show().detach());

    var product_image_box_top = 0;
    var product_info_pos = $(".product-info-main").offset().top;
    $(window).scroll(function(){
        product_info_pos = $(".product-info-main").offset().top;
        if($(window).innerWidth() >= 768) {
            $(".product.media").each(function(){
                if(($(window).scrollTop() > product_info_pos - 50) &&
                    (product_info_pos + $(".product-info-main").outerHeight()) >
                    ($(window).scrollTop() + $(this).outerHeight() + 50)) {
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
                if(($(window).scrollTop() > product_info_pos - 50) &&
                    (product_info_pos + $(".product-info-main").outerHeight()) >
                    ($(window).scrollTop() + $(this).outerHeight() + 50)) {
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
});
