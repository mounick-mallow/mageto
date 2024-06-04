define([
    'jquery'
], function ($) {
    'use strict';
    return function (config) {
        $(document).ready(function(){
            $("#maincontent .columns").before($(".onepage-cat.category-list").detach());
            $("#maincontent").addClass("onepage-category");
            $(".onepage-cat.category-list > ul > li > a").each(function(){
                var href = $(this).attr("href");
                if(href.indexOf(window.location.pathname) > -1)
                    $(this).addClass("active");
            });
            $(".onepage-category .columns").css("min-height", $(".onepage-cat.category-list").height());
            $(window).scroll(function(){
                if($(".onepage-cat.category-list > ul").outerHeight() < $(this).innerHeight()) {
                    $(".onepage-cat.category-list > ul").removeClass("fixed-bottom");
                    if($(this).scrollTop() >= $(".onepage-cat.category-list").offset().top + 46) {
                        $(".onepage-cat.category-list > ul").addClass("fixed-top");
                    } else {
                        $(".onepage-cat.category-list > ul").removeClass("fixed-top");
                    }
                } else {
                    $(".onepage-cat.category-list > ul").removeClass("fixed-top");
                    if($(this).scrollTop() >= $(".onepage-cat.category-list").offset().top +
                     $(".onepage-cat.category-list > ul").height() + 46 - $(this).innerHeight()) {
                        $(".onepage-cat.category-list > ul").addClass("fixed-bottom");
                    } else {
                        $(".onepage-cat.category-list > ul").removeClass("fixed-bottom");
                    }
                }
                if(($(".onepage-cat.category-list > ul").hasClass("fixed-bottom") && ($(this).scrollTop() +
                $(window).innerHeight() >= $(".page-footer").offset().top)) ||
                ($(".onepage-cat.category-list > ul").hasClass("fixed-top") &&
                ($(this).scrollTop() + $(window).innerHeight() >= $(".page-footer").offset().top)
                && ($(".onepage-cat.category-list > ul").offset().top +
                $(".onepage-cat.category-list > ul").outerHeight() >= $(".page-footer").offset().top)
                && ($(this).scrollTop() +
                $(".onepage-cat.category-list > ul").outerHeight() + 70 >= $(".page-footer").offset().top))) {
                    $(".onepage-cat.category-list > ul").addClass("absolute-bottom");
                } else {
                    $(".onepage-cat.category-list > ul").removeClass("absolute-bottom");
                }
            });
        });
    }
});