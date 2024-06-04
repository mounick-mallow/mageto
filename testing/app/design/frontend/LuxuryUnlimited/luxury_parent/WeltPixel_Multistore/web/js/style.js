define([
    'jquery'
], function ($) {
    'use strict';
    return function (config) {
        const MAX_WIDHT_MOBILE = 768;
        $(document).ready(function() {
        	$('.multistore-switcher.switcher').css('background-color', config.multistore_mobile_switcher_language)
        	$('.multistore-mobile-switcher-language').css('background-color', config.multistore_mobile_switcher_language)
        	$('ul.weltpixel_multistore li img').css('height', config.multistore_img_height);

        	if (config.multistore_img_width) {
        		$('ul.weltpixel_multistore li img').css('width', config.multistore_img_width);
        	}

        	if ($(window).width() > MAX_WIDHT_MOBILE) {
        		if (config.is_pearltheme_used) {
        			 $(".weltpixel_multistore").css('max-width', config.multistore_max_width);
        		}
	      	}
        })
    }
});