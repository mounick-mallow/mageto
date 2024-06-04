define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'domReady!'
], function ($, modal) {
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        title: $.mage.__('Price match ticket'),
        buttons: [{
            text: $.mage.__('Close'),
            class: 'modal-close',
            click: function (){
                this.closeModal();
            }
        }]
    };

    var btnSecond = document.getElementById("price-match-create-ticket-bth-new");
    modal(options, $('#priceMatchModalSecond'));

    if (btnSecond) {
        btnSecond.onclick = function() {
            $('#priceMatchModalSecond').modal("openModal");
        }
    }
});
