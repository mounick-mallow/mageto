define([
    'jquery',
    'domReady!'
], function($) {
    'use strict';

    var errorTemplate = '<div'
        + ' class="mage-error pslogin-fake-email"'
        + ' id="email-error"'
        + ' for="email"'
        + ' generated="true"'
        + '>'
        + $.mage.__('Please enter valid email address.')
    + '</div>';

    $("#email").removeClass("valid").addClass("mage-error").after(errorTemplate);

    var reset = true;
    $("#email").on('click focus', function() {
        if(reset) {
            $(this).removeClass("mage-error").addClass("valid").val('');
            $('#email-error.pslogin-fake-email').remove();
        }

        reset = false;
    });
});
