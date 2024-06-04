define([
    'jquery',
], function  ($) {
    'use strict';
	$(document).ready(function(){
        $('.return-submit').click(function(){
            $('.returns-left').css({"left":"0"});
            $('.returns-right').fadeIn(2500);
        });
    });
});