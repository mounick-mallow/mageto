define([
    'jquery',
    'mage/url',
    'weltpixel_quickview',
    'domReady!'
], function ($, urlBuilder, quickview) {

    var infBlock = $('#smartwave-porto-ajax-info');
    var aspect_ratio = infBlock.attr('data-aspect');
    var image_width = infBlock.attr('data-image-width');
    var image_height = infBlock.attr('data-image-height');
    var product_count = infBlock.attr('data-product-count');
    var columns = infBlock.attr('data-columns');

    $(".ajax-products > .category-list > ul > li > a").off("click").on("click", function() {
        url = urlBuilder.build("swporto/index/showcategoryproducts");
        cat_id = $(this).attr("cat_id");
        $(this).parent().parent().children("li").children("a").removeClass("active");
        $(this).addClass("active");
        $(".ajax-products > .category-detail > .ajax_products_loader").fadeIn();

        $.ajax({
            method:"POST",
            url:url,
            data:{
                aspect_ratio: aspect_ratio,
                image_width: image_width,
                image_height: image_height,
                product_count: product_count,
                columns: columns,
                category_id:cat_id
            },
            dataType: 'json'
        }).done(function(data){
            $(".ajax-products > .category-detail > .products-area").html(data.result);

            if (typeof enable_quickview != 'undefined' && enable_quickview == true) {
                $('.weltpixel-quickview').off('click').on('click', function() {
                    var prodUrl = $(this).attr('data-quickview-url');
                    if (prodUrl.length) {
                        quickview.displayContent(prodUrl);
                    }
                });
            }
        }).fail(function() {

        }).always(function() {
            $(".ajax-products > .category-detail > .ajax_products_loader").fadeOut();
        });
    });

    $(".ajax-products > .category-list > ul > li:first-child > a").click();
});
