define([
    'jquery',
    'weltpixel_quickview',
    'domReady!'
], function   ($, quickview) {
    $('.weltpixel-quickview').bind('click', function() {
        var prodUrl = $(this).attr('data-quickview-url');
        if (prodUrl.length) {
            quickview.displayContent(prodUrl);
        }
    });
});
