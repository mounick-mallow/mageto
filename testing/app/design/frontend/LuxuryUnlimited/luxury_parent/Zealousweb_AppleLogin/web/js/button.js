define([
    'jquery',
    'mage/url'
], function($, urlBuilder){

    var infBock = $('#zealous-apple-login-button-info');
    var loginUrl = infBock.attr('data-login-url');

    $('#signinwithapple_login').on('click', function(event) {
        var isiPad = navigator.userAgent.match(/iPad/i) != null;
        var isiPhone = navigator.userAgent.match(/iPhone/i) != null;
        if (isiPad || isiPhone) {
            window.open(urlBuilder.build('applelogin/apple/redirect'),'popup','width=600,height=600');
        } else {
            window.open(loginUrl,'popup','width=600,height=600');
        }

        return false;
    });
});
