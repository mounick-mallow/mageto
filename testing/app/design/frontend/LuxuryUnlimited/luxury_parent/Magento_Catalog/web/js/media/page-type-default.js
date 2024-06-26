define([
    'jquery',
    'Magento_Catalog/js/jquery.zoom.min'
], function ($) {
    var loaded = false;
    $('.product.media .gallery-placeholder').bind("DOMSubtreeModified",function(){
        $('.product.media .fotorama').on('fotorama:ready', function (e, fotorama, extra) {
            loaded = false;
            $('.product.media .fotorama').on('fotorama:load', function (e, fotorama, extra) {
                if (!loaded) {
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
            $('.fotorama').off('fotorama:fullscreenenter').on('fotorama:fullscreenenter',
                function (e, fotorama, extra) {
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
                $('.product.media .fotorama').off('fotorama:showend').on('fotorama:showend',
                    function (e, fotorama, extra) {
                        $('.product.media .fotorama__stage .fotorama__loaded--img').trigger('zoom.destroy');
                        $('.product.media .fotorama__stage .fotorama__active').zoom({
                            touch:false
                        });
                    });
            });
        });
    });
});
