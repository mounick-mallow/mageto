
define([
    'jquery',
    'mage/translate'
], function ($, $t) {
    'use strict';

    $.widget('lx.showMore', {
        options: {
            moreText: 'more',
            lessText: 'less'
        },

        _init: function () {
            $(this.element).on('click', {self: this}, function (event){
               const _self = event.data.self,
                     brandListContainer = $(this).siblings('.lx-brand-list.long'),
                     isOpen = brandListContainer.hasClass('open');

                if (!isOpen) {
                    brandListContainer.addClass('open');
                    $(this).text($t(_self.options.lessText));
                } else {
                    brandListContainer.removeClass('open');
                    $(this).text($t(_self.options.moreText));
                }
            });
        }
    });

    return $.lx.showMore;
});
