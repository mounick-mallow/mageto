define([
    'jquery',
    'mage/url',
    'domReady!'
], function($, urlBuilder) {

    var successUrl = urlBuilder.build('orderreturn/index/success');

    $('.order-return-modal .close').click(function(){
        $('.order-return-modal').fadeOut(200);
        $('#return-item-sku').val("");
    });

    $('.order-return').click(function(){
        $('.order-return-modal').fadeIn(200);
        $('#return-item-sku').val($(this).attr("data-item-sku"));
    });

    $(".submit-return").click(function(){
        var dataForm = jQuery('#'+$(this).closest('form').attr('id'));
        $("#result-return").text('').css({"display":"block"});
        if (dataForm.validation('isValid')) {
            $.ajax({
                url: dataForm.attr('action'),
                type: dataForm.attr('method'),
                data: dataForm.serialize(),
                dataType: 'json',
                async: true,
                beforeSend: function() {
                    $('#loader-return').show();
                },
                complete: function(){
                    $('#loader-return').hide();
                },
                success: function (response) {
                    if (response.errors === false) {
                        window.location.href = successUrl;
                        dataForm[0].reset();
                    } else {
                        $("#result-return").text(response.message);
                        $('#result-return').delay(3000);
                    }
                }
            });
            event.stopImmediatePropagation();
            return false;
        }
    });
});
