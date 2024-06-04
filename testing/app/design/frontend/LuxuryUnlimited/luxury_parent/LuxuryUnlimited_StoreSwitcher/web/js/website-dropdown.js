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
    
    window.triggerActionCurrencyChange = false;

    return Component.extend({
        availableOptions: ko.observableArray([]),
        chosenCountries: ko.observableArray([]),

        /**
         * Init component
         */
        initialize: function (config) {
            this._super();
            var self = this;
            
            var selectedValue;
            var options = [];
            
            $('#cookie-popup-window .currency-placeholder .currency').hide();
            _.each(config, function (dropdownValue, key) {
                if (typeof dropdownValue === 'object') {
                    var value = dropdownValue.value;
                    var label = dropdownValue.label;
                    var option = {
                        'value': value,
                        'label': label
                    };
                    options.push(option);
                    if (dropdownValue.selected === 1) {
                        selectedValue = value;
                    }
                }
            });


            $('#redirect').prop('disabled', true); 
            const dropdownInterval = setInterval(function () {
            var modalParent = $('#cookie-popup-window').closest('.custom-window-block');
            if (modalParent.hasClass('_show')) { 
                    self.renderOptions(options).then(
                        function() { 
                            self.setSelectedItem(selectedValue);
                        }
                    );
                    clearInterval(dropdownInterval); 
                }
            }, 1000);

            return this;
        },

        renderOptions : async function(options) {
            this.availableOptions(options); 
        },

        setSelectedItem : function(selectedValue) {
            var self = this;
            window.triggerActionCurrencyChange = true;
            this.chosenCountries([selectedValue]); 
            this.triggerCurrency().then(
                function() { 
                    self.showCurrency();
                }
            );
        },

        triggerCurrency: async function() {
            $('#websites').trigger('change');
        },

        showCurrency: function() {
            $('#cookie-popup-window .currency-placeholder .currency').show();
            $('#redirect').prop('disabled', false); 
        }
    });
});