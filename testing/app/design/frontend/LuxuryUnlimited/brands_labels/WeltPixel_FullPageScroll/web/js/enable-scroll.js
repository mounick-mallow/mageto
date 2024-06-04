define([
    'jquery',
    'fullpagescroll'
], function ($, FullPageScroll) {
    'use strict';
    return function (config) {
        var _countBlocks = config.countBlocks;
        $(document).ready(function(){
            FullPageScroll.action(_countBlocks);
            $(window).trigger('resize');
        });
    }
});