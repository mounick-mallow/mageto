define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate'
], function ($, modal, $t) {
    'use strict';

    $.widget('mage.productEstimateDelivery', {

        _init: function () {
            const self = this;
            this.element.on('click', function () {
                self._deliveryPopup();
            });
        },

        _deliveryPopup: function () {
            let options = {
                type: 'popup',
                responsive: true,
                innerScroll: false,
                modalClass: 'estimated-delivery-reference',
                title: $t('Estimated Delivery'),
                buttons: []
            };
            let popup = $('#estimated-delivery-reference');
            modal(options, popup);
            popup.modal('openModal');
        }
    });

    return $.mage.productEstimateDelivery;
});
