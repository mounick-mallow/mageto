define([
    'jquery',
    'domReady!'
    ], function($) {
        return function (config) {
            var donationBtn = $('.input-radio-amount-sidebar-donation-list');
            var configRate = config.rate || 1;

            donationBtn.map((index, elem) => {
                $(elem).click(function(e) {
                    $("#donation-value").val(Math.round(e.target.getAttribute('value') / configRate));
                });
            });

            $("#donation-value").keyup(function() {
                let dInput = $('#donation-value').val();

                dInput = dInput * configRate;
                $(".custom-donation-amount-field input.validate-digits-range").val(dInput);
            });
        }
});
