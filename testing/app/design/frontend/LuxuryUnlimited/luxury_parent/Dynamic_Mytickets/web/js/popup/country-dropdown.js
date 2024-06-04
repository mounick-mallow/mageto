define([
    'jquery',
    'uiComponent',
    'ko',
    'underscore',
    'mage/cookies'
], function (
    $,
    Component,
    ko,
    _
) {
    'use strict';

    return Component.extend({
        availableCountries: ko.observableArray([]),
        chosenCountries: ko.observableArray(['GB']),

        /**
         * Init component
         */
        initialize: function (config) {
            this._super();
            var self = this;

            var options = [];

            _.each(config, function (dropdownValue, key) {
                if (typeof dropdownValue === 'object') {
                    var value = dropdownValue.value;
                    var label = dropdownValue.label;
                    var option = {
                        'value': value,
                        'label': label
                    };
                    options.push(option);
                }
            });

            const dropdownInterval = setInterval(function () {
                var modalParent = $('.special-requst-modal');
                if (modalParent.hasClass('_show')) { 
                    self.renderOptions(options);
                    clearInterval(dropdownInterval); 
                }
            }, 1000);

            return this;
        },

        renderOptions : function(options) {
            this.availableCountries(options); 
        }
      
    });
});