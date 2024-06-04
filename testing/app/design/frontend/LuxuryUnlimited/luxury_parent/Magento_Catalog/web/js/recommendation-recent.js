define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'domReady!'
], function ($, modal) {
    'use strict';

    return function(config) {
        var modalContent = document.getElementById("myModalspec");
        var myModalPriceSuccess = document.getElementById("myModalPriceSuccess");
        var btn = document.getElementById("myspecialreq");
        var span = document.getElementsByClassName("close")[0];
        var pricesucessClose = document.getElementById("pricesucessClose");
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: $.mage.__('Special Requests'),
            buttons: [{
                text: $.mage.__('Close'),
                class: '',
                click: function () {    
                    this.closeModal();
                }
            }]
        };

        modal(options, $('#myModalspec'));

        pricesucessClose.onclick = function() {
            $('#myModalspec').modal('closeModal');
        };

        if (btn) {
            btn.onclick = function() {
                $('#myModalspec').modal('openModal');
            }
        }
    
        span.onclick = function() {
            $('#myModalspec').modal('closeModal');
        };
    
        window.onclick = function(event) {
            if (event.target === modalContent) {
                $('#myModalspec').modal('closeModal');
            }
        };
    
        $(document).on('click', '#recommendations-request', function(){
            var dataForm = jQuery('#' + $(this).closest('form').attr('id'));
            if (dataForm.validation('isValid')) {
                $.ajax({
                    url: dataForm.attr('action'),
                    type: dataForm.attr('method'),
                    data: dataForm.serialize(),
                    dataType: 'json',
                    async: true,
                    beforeSend: function() {
                        $('body').trigger('processStart');
                    },
                    complete: function(){
                        $('body').trigger('processStop');
                    },
                    success: function (response) {
                        if(response.errors === false) {
                            $('#myModalspec').find('.modal-content').html(response.message);
                            dataForm[0].reset();
                        } else {
                            $('#result-message').html(response.message);
                            dataForm[0].reset();
                        }
                    },
                    error: function (response) {
                        console.log(JSON.parse(response));
                    },
                });
                event.stopImmediatePropagation();
                return false;
            }
        });
    }
});
