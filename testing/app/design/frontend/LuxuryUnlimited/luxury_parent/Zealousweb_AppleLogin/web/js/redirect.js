define([
    'jquery',
    'mage/url',
    'jquery/ui'
], function($, urlBuilder){

    var infBlock = $('#zealous-login-info-block');
    var loginUrl = infBlock.attr('data-login-url');

    $('#signinwithapple').on('click', function() {
        var isiPad = navigator.userAgent.match(/iPad/i) != null;
        var isiPhone = navigator.userAgent.match(/iPhone/i) != null;

        if (isiPad || isiPhone) {
            window.open(urlBuilder.build('applelogin/apple/redirect'));
        } else {
            window.open(loginUrl,'popup','width=600,height=600');
        }

        return false;
    });
});
