require([
        "jquery"
], function($){
        $(document).ready(function () {
            'use strict';
            var validationErrorMoved = false;
            $('#product-addtocart-button').click(function() {
                if (!validationErrorMoved) {
                    $('.swatch-attribute').each(function() {
                        var attrId = $(this).attr('attribute-id');
                        var validationElement = $('input[name="super_attribute['+attrId+']"]').get(0);
                        $(validationElement).appendTo(this);
                    });
                    validationErrorMoved = true;
                }
            });
            $('[data-gallery-role=gallery-placeholder]').on('gallery:loaded', function () {
                $(this).on('fotorama:ready', function(){
                    $('.fotorama__stage__frame.fotorama__active').closest('.fotorama__stage__shaft').css('transform','none');
                });
            });
        });

});
