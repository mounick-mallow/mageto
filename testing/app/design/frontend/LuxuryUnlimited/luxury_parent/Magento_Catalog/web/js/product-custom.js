define([
    'jquery',
    'domReady!'
], function ($) {

    var p_scrolled = false;
    var offset = $('.box-tocart').offset().top;
    $(window).scroll(function() {
        if(offset < $(window).scrollTop() && !p_scrolled){
            p_scrolled = true;
            $('.product-info-main .product-info-price > *').each(function(){
                $(this).parent().append($(this).clone());
                var tmp = $(this).detach();
                $('.sticky-product .product-info-price').append(tmp);
            });
            $(".sticky-product").removeClass("hide");
            $("#product-addtocart-button").off("DOMSubtreeModified").on("DOMSubtreeModified",function(){
                $("#product-addtocart-button-clone").html($(this).html());
                $("#product-addtocart-button-clone").attr("class",$(this).attr("class"));
            });
        }
        if(offset >= $(window).scrollTop() && p_scrolled){
            p_scrolled = false;
            $('.product-info-main .product-info-price > *').remove();
            $('.sticky-product .product-info-price > *').each(function(){
                var tmp = $(this).detach();
                $('.product-info-main .product-info-price').append(tmp);
            });
            $(".sticky-product").addClass("hide");
        }
    });
    $("#product-addtocart-button-clone").click(function(){
        $("#product-addtocart-button").trigger("click");
    });
});
