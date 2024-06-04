define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate'
], function ($, modal, $t) {
    'use struct';

    return function () {
        let options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            modalClass: 'secure_shopping',
            title: $t('This site is secure'),

        };

        let popup = $('#secure_shopping');
        modal(options, popup);

        $('.help-btn').on('click', function () {
            popup.modal('openModal');
        });

        // Function to close the modal
        function closePopupSpecialRequest() {
            popup.modal('closeModal');
        }
        window.closePopupspecialrequest = closePopupSpecialRequest;
    }
});
