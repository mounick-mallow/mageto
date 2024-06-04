
define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('lx.brandsSearch', {
        options: {
            brandsSearchFieldSelector: '#lx-brands__search-field',
            brandsListSelector: '.lx-brands__list-section',
            brandsListItemSelector: '.lx-brand-list__name',
            noResultSectionSelector: '#no-result',
            brandsListSectionSelector: '#lx-brands__result',
            clearFilterBtnSelector: '.lx-brands__clear-search',
            alphabetNavigationSelector: '.lx-brands__alphabet-item',
            viewAllBtnWrapperSelector: '.lx-brands__clear-wrapper'
        },

        _init: function () {
            this.options.clearFilterBtn = $(this.options.brandsSearchFieldSelector).next(this.options.brandsSearch);
            this.options.viewAllBtnWrapper = $(this.options.viewAllBtnWrapperSelector);
            this.initNewJQuerySelector();
            $(this.options.brandsSearchFieldSelector).on('keyup', {self: this}, this.textBrandsFilter);
            $(this.options.alphabetNavigationSelector).on('click', {self: this}, this.alphabetSelect);
            $(this.options.clearFilterBtnSelector).on('click', {self: this}, this.resetFilters);
        },

        alphabetSelect: function (event) {
            const value = $(this).data('symbol'),
                _self = event.data.self,
                selectedItem = $(`${_self.options.brandsListSelector}[data-symbol='${value}']`);

            if (selectedItem.length) {
                _self.clearAllFilters();
                _self.hideBrands();
                selectedItem.show();
                selectedItem.find(_self.options.brandsListItemSelector).show();
            } else {
                _self.showNoResultSection();
                $(_self.options.viewAllBtnWrapper).hide();
            }
        },

        textBrandsFilter: function (event) {
            const value = $(this).val().toLowerCase(),
                 _self = event.data.self;

            if (value) {
                _self.showBrandsSection();
                $('.lx-brand-list.long').removeClass('long').addClass('short');
                $(_self.options.clearFilterBtn ).parent().addClass('active');
                let brandsSections = $(`${_self.options.brandsListSelector}:containsIN(${value})`),
                    brandsItems = brandsSections.find(`${_self.options.brandsListItemSelector}:containsIN(${value})`);

                _self.hideBrands();
                if (brandsItems.length) {
                    brandsSections.show();
                    brandsItems.show();
                } else {
                    _self.showNoResultSection();
                }

            } else {
                _self.clearAllFilters();
            }
        },

        resetFilters: function (event) {
            const _self = event.data.self;

            event.preventDefault();
            event.stopPropagation();
            _self.clearAllFilters();
        },


        clearAllFilters: function () {
            this.showBrands();
            this.showBrandsSection();
            $(this.options.clearFilterBtn).parent().removeClass('active');
            $('.lx-brand-list.short').removeClass('short').addClass('long');
            $('.lx-brand-list.long.open').removeClass('open');
            document.getElementById(this.options.brandsSearchFieldSelector.substr(1)).value = '';
        },

        clearShowMore: function () {

        },

        showBrandsSection: function () {
            $(this.options.noResultSectionSelector).removeClass('active');
            $(this.options.brandsListSectionSelector).show();
        },

        showNoResultSection: function () {
            $(this.options.noResultSectionSelector).addClass('active');
            $(this.options.brandsListSectionSelector).hide();
        },

        hideBrands: function () {
            $(this.options.brandsListSelector).hide();
            $(this.options.brandsListItemSelector).hide();
        },

        showBrands: function () {
            $(this.options.brandsListSelector).show();
            $(this.options.brandsListItemSelector).show();
        },

        initNewJQuerySelector: function () {
            $.extend($.expr[":"], {
                "containsIN": function(elem, i, match, array) {
                    return (elem.textContent || elem.innerText || "")
                        .toLowerCase()
                        .indexOf((match[3] || "").toLowerCase()) >= 0;
                }
            });
        }
    });

    return $.lx.brandsSearch;
});
