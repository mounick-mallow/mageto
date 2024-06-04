define([
    'jquery',
    'domReady!'
], function ($) {

    $("a.right-side-open").click(function(){
        $(".sidebar.sidebar-additional").addClass("open");
    });

    $(".side-overlay").click(function(){
        $(".sidebar.sidebar-additional").removeClass("open");
    });
});
