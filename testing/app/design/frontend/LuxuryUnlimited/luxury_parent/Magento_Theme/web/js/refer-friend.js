define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: 'referral-modal',
        title: $.mage.__('Referral Sent'),
    };

    var popup = modal(options, $('#referral-modal'));

    $("#btn_registers").on('click', function () {
        $("#referral-modal").modal("openModal");
    });

    function closePopupSpecialRequest() {
        $("#referral-modal").modal("closeModal");
    }

    window.closePopupspecialrequest = closePopupSpecialRequest;
});
