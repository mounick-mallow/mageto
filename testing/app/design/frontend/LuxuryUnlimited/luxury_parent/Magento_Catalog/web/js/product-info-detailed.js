define([
    'jquery',
    'domReady!'
], function ($) {
    $(".product.info.detailed").detach().appendTo($(".product-info-main"));
});
