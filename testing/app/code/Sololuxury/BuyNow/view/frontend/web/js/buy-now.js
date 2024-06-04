define([
    'jquery',
    'mage/url'
], function ($,urlBuilder) {
    "use strict";
    return function (config, element) {
        $(element).click(function (event) {
            event.preventDefault();
            var confProduct = $(this).parents('form').find('.super-attribute-select').length;
            if(confProduct){
                //size is selected
                var selectedSize = $(this).parents('form').find('.super-attribute-select').val();
                if(selectedSize!=""){
                    confProduct = 0;
                }
            }
            if(!confProduct) {
                let form = $(config.form),
                    baseUrl = form.attr('action'),
                    addToCartUrl = 'checkout/cart/add',
                    buyNowCartUrl = 'buynow/cart/add',
                    buyNowUrl = baseUrl.replace(addToCartUrl, buyNowCartUrl);
                $.ajax({
                    url: buyNowUrl,
                    type: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    async: false,
                    beforeSend: function () {
                        //console.log(dataForm.serialize());
                    },
                    complete: function () {
                        //$('#loader-message').hide();
                    },
                    success: function (response) {
                        if (response.error === false) {
                            var customLink = urlBuilder.build('checkout/cart');
                            window.location = customLink;
                        } else {
                            alert("something went wrong");
                        }
                    },
                    error: function (response) {
                        alert("something went wrong");
                    }
                });
            }
        });
    };
});
