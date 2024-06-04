define([
    "jquery", 
    'Magento_Ui/js/modal/modal', 
    'jquery/ui'
],function($,modal){
    'use strict';

    var selectedOrderId;

    $('.cancel-order-button').click(function(){
        var orderNo = this.id.split("-").pop();
        selectedOrderId = orderNo;
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: $.mage.__('Cancel Order #'+orderNo),
            buttons: []
        };

        modal(options, ".show-"+orderNo);
        $(".show-"+orderNo).modal("openModal");
    });

    $('#order-return-ticket-modal').on('modalclosed', function() { 
        if (selectedOrderId) {
            $(".show-"+selectedOrderId).modal("closeModal");
        }
    });

});