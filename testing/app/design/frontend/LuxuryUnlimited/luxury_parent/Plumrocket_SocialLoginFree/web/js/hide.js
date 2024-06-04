define([
    'jquery',
    'domReady!'
], function($) {
    'use strict';

    $('div.password.current').hide();

    $('#change-email, #change-password').each(function (){
        var el = $(this);
        el.parent('.field').hide();
        el.prop('checked', true).on('click', function () {
            return false;
        });
    });
});
