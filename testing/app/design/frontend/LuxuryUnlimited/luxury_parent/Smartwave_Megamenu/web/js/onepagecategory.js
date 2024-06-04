define([
    'jquery',
], function ($) {
'use strict';
    return function () {
    
        $(document).ready(function() {
            
            $(".category-detail > .title-menu > a.parent").off("click").on("click", function(e){
                if($(this).hasClass("opened")) {
                    $(this).parent().children(".menu-popup").fadeOut(200);
                    $(this).removeClass("opened");
                } else {
                    $(this).addClass("opened");
                    $(this).parent().children(".menu-popup").fadeIn(200);                
                }
                e.stopPropagation();
            });
            
            $(".category-detail > .title-menu > a.parent").parent().click(function(e){
                e.stopPropagation();
            });
            
            $("html,body").click(function(){
                $(".category-detail > .title-menu > a.parent").parent().children(".menu-popup").fadeOut(200);
                $(".category-detail > .title-menu > a.parent").removeClass("opened");
            });

            $(".onepage-category .category-list > ul > li > a").off("click").on("click", function(){
                var cat_id = $(this).attr("data-cat");
                $("#category_"+cat_id).scrollToMe();
                var cur_item = $(this);
                setTimeout(function(){
                    $(".onepage-category .category-list > ul > li > a").removeClass("active");
                    $(cur_item).addClass("active");
                },500);
            });

            $(window).scroll(function() {
                
                $(".onepage-category .category-list > ul > li > a").each(function(){
                    if($("#category_"+$(this).attr("data-cat")).offset() && ($(window).scrollTop() >= $("#category_"+
                    $(this).attr("data-cat")).offset().top - $(window).innerHeight() / 2)
                    && ($(window).scrollTop() <= $("#category_"+$(this).attr("data-cat")).offset().top +
                    $("#category_"+$(this).attr("data-cat")).height() - $(window).innerHeight() / 2)) {
                        $(this).addClass("active");
                        $(".onepage-category .category-list > ul > li > a:not([data-cat='"+
                        $(this).attr("data-cat")+"'])").removeClass("active");
                    }
                });

                if($(".onepage-category .category-list > ul").outerHeight() < $(this).innerHeight()) {
                    $(".onepage-category .category-list > ul").removeClass("fixed-bottom");
                    if($(this).scrollTop() >= $(".onepage-category .category-list").offset().top - 24) {
                        $(".onepage-category .category-list > ul").addClass("fixed-top");
                    } else {
                        $(".onepage-category .category-list > ul").removeClass("fixed-top");
                    }
                } else {
                    $(".onepage-category .category-list > ul").removeClass("fixed-top");
                    if($(this).scrollTop() >= $(".onepage-category .category-list").offset().top +
                    $(".onepage-category .category-list > ul").outerHeight() + 46 - $(this).innerHeight()) {
                        $(".onepage-category .category-list > ul").addClass("fixed-bottom");
                    } else {
                        $(".onepage-category .category-list > ul").removeClass("fixed-bottom");
                    }
                }

                if(($(".onepage-category .category-list > ul").hasClass("fixed-bottom") && ($(this).scrollTop() +
                $(window).innerHeight() >= $(".page-footer").offset().top)) ||
                ($(".onepage-category .category-list > ul").hasClass("fixed-top") && ($(this).scrollTop() +
                $(window).innerHeight() >= $(".page-footer").offset().top) &&
                ($(".onepage-category .category-list > ul").offset().top +
                $(".onepage-category .category-list > ul").outerHeight() >= $(".page-footer").offset().top)
                && ($(this).scrollTop() +
                $(".onepage-category .category-list > ul").outerHeight() + 70 >= $(".page-footer").offset().top))) {
                    $(".onepage-category .category-list > ul").addClass("absolute-bottom");
                } else {
                    $(".onepage-category .category-list > ul").removeClass("absolute-bottom");
                }
                
            });
        });
    }
});