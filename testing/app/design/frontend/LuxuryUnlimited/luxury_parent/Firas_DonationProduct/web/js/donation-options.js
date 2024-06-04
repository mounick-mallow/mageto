define([
    "jquery",
], function ($) {
    var amountInput =  $('#amount');
    $('#product_addtocart_form').on('change', '.input-radio-fixed-donation', function () {
        amountInput.removeClass('required');
        amountInput.validation().valid();
    });

    $('#product_addtocart_form').on('change', '#amount', function () {
        amountInput.validation().valid();
    });
});
