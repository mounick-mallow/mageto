define([
    'jquery',
    'weltpixel_quickview',
    'mage/url'
], function ($, quickview, urlBuilder) {

    return function(config) {

        var aspect_ratio = config.aspectRatio;
        var image_width = config.imageWidth;
        var image_height = config.imageHeight;
        var product_count = config.productCount;
        var columns = config.columns;

        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(2n)').addClass('nth-child-2n');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(2n+1)').addClass('nth-child-2np1');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(3n)').addClass('nth-child-3n');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(3n+1)').addClass('nth-child-3np1');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(4n)').addClass('nth-child-4n');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(4n+1)').addClass('nth-child-4np1');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(5n)').addClass('nth-child-5n');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(5n+1)').addClass('nth-child-5np1');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(6n)').addClass('nth-child-6n');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(6n+1)').addClass('nth-child-6np1');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(7n)').addClass('nth-child-7n');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(7n+1)').addClass('nth-child-7np1');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(8n)').addClass('nth-child-8n');
        $('.main .products.grid .product-items.filterproducts li.product-item:nth-child(8n+1)').addClass('nth-child-8np1');

        $('.load-more-area > a').off("click").on("click", function(){
            url = urlBuilder.build('swporto/index/showcategoryproducts');
            cat_id = $(this).attr("cat_id");

            $(".ajax-products .load-more-area > .ajax-loader-area").fadeIn();

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
            }).fail(function(){

            }).always(function(){
                $(".ajax-products > .category-detail > .ajax_products_loader").fadeOut();
                if (typeof enable_quickview != 'undefined' && enable_quickview == true) {
                    $('.weltpixel-quickview').off('click').on('click', function() {
                        var prodUrl = $(this).attr('data-quickview-url');
                        if (prodUrl.length) {
                            quickview.displayContent(prodUrl);
                        }
                    });
                }
            });
        });
    }
});
