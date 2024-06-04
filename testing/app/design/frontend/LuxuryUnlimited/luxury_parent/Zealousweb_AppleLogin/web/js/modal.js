define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function($,  modal) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            modalClass: 'apple-login-popup',
            buttons: []
        };

        var popup = modal(options, $('#apple-popup-modal'));
        $(".login-popup").on('click',function(){
            $("#apple-popup-modal").modal("openModal");
        });
    }
);
