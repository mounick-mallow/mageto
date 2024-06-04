define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'Magento_Ui/js/modal/alert',
    'mage/translate'
], function ($, modal, alert, $t) {
    'use strict';

    $.widget('mage.productSizePopup', {

        _init: function () {
            const self = this;
            $('button#product-addtocart-button,button#buy-now').on('click', function() {
                self.validationSizePopup();
            });

            $('#click-me').on('click',function(){
                $('.size-container').show();
                self.sizePopup();
            });
        },

        sizePopup: function () {
            let options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                content: 'gallery.phtml',
                buttons: [{
                    text: $t('Continue'),
                    class: '',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };

            let popup = $('#popup-mpdal');
            modal(options, popup);
            popup.modal('openModal');
        },

        validationSizePopup: function () {
            let size = $('.super-attribute-select');
            if (size.length === 0) {
                return false;
            }

            if (size.val().length === 0) {
                $('.product-message').hide();
                alert({
                    title: $t('Size Selection Required'),
                    content: $t('Before adding this item to your cart, Please select a size option.'),
                    actions: {
                        always: function(){}
                    },
                    buttons: [{
                        text: $t('Continue Shopping'),
                        click: function (event) {
                            // New action
                            this.closeModal(event, true);
                        }
                    }]
                });
            }
        }
    });

    return $.mage.productSizePopup;
});
