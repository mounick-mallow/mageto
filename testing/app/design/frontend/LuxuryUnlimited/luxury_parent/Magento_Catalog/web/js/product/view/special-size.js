define([
    'jquery', 
    'Magento_Ui/js/modal/modal', 
    'Magento_Ui/js/modal/alert', 
    'mage/validation'
], 
function($, modal, alert){
    'use strict';

    return function(config) {
        var sizeId = config.sizeId;
        var notifyMeUrl = config.notifyMeUrl;
        
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            buttons: []
        };

        var existCondition = setInterval(function() {
            if ($("#product-options-wrapper .fieldset").length > 0 && $("#attribute"+sizeId).length > 0
                && $("#attribute"+sizeId+" option").length > 1) {
                clearInterval(existCondition);
                runMyFunction();
            }
        }, 100);
        $('#missing-size-loader').hide();
        var popup = modal(options, $('#myModalspecsize'));

        function runMyFunction(){
            $('#attribute'+sizeId).append('<option value="notfound">' +
            $.mage.__('Missing Size') + '</option>');
        }

        $('#attribute'+sizeId).on('change', function() {
            var thistext = $(this).find('option:selected').text();
            if(this.value == "notfound") {
                $('#missing-size').modal('openModal');
                $('#attribute'+sizeId).val("");
            } else if(thistext.indexOf($.mage.__('Sold Out')) != -1) {
                $('#missing-size').modal('openModal');
            }
        });

        $('button#product-addtocart-button').click(function() {
            if ($("#attribute"+sizeId).length){
                var sizeVal = $("#attribute"+sizeId).val();
                // Size Attribute Required POPUP
                if (sizeVal == "") {
                    alert({
                        title: $.mage.__('Size Selection Required'),
                        content: $.mage.__('Before adding this item to your cart, Please select a size option.'),
                        actions: {
                            always: function(){}
                        },
                        buttons: [{
                                text: $.mage.__('Continue Shopping'),
                                class: 'action new',
                                click: function (event) {
                                    // New action
                                    this.closeModal(event, true);
                                }
                        }]
                    });
                }
            }
        });

        var url = notifyMeUrl;
        $('#btn_sizesubmit').click(function() {
            var salutation = $("#salutation-size").val();
            var name = $("#name-size").val();
            var lastname = $("#last-name-size").val();
            var phone = $("#phone-size").val();

            var email = $("#email-size").val();
            var size = $("#selected-size").val();
            var productsku = $("#productsku").val();

            var isValid = $("#missing-size-form").valid();

            if (!isValid) {
                return false;
            }

            $("#missing-size-error").html('');
            $('.missing-size-form').hide();
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    salutation: salutation,
                    name: name,
                    lastname: lastname,
                    phone: phone,
                    email: email,
                    product_sku: productsku,
                    product_size: size
                },
                dataType: "JSON",
                beforeSend: function() {
                    $('#missing-size-loader').show();
                },
                complete: function(){
                    $('#missing-size-loader').hide();
                },
                success: function (response) {
                        if(response.errors == false) {
                        $("#missing-size input").val('');
                        $('#missing-size').modal('closeModal');
                        $('#missing-size-success').modal('openModal');
                        $("#missing-size-result").html(response.message);
                        $('.missing-size-form').show();
                    } else {
                        $("#missing-size-error").html(response.message);
                        $('.missing-size-form').show();
                    }
                }
            });
        });
    }
        
});
