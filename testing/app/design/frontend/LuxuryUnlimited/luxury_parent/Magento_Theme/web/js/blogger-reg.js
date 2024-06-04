define([
    'jquery',
    'domReady!'
], function ($) {

    $("#close-message").click(function () {
        $(".message-container").fadeOut("slow");
    });

});
