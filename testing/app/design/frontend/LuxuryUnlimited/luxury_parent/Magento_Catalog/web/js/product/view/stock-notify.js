define([
    'jquery', 
    'Magento_Ui/js/modal/modal', 
    'mage/validation'
], function($, modal){
    'use strict';

    return function(config) {

        var sizeId = config.sizeId;
        var notifyUrl = config.notifyUrl;
        var soldOutText = config.soldOutText;
        
        $('#stock-notifyme-loader').hide();
        $('.notify-link').on('click', function() {
            $('#stock-notifyme').modal('openModal');
            if($('#logged-in-notifyme').data("value")) {
                $('#btn_notifysubmit').trigger('click');
            }
        });

        $('#attribute'+sizeId).on('change', function() {
            var thistext = $(this).find('option:selected').text();
            if(this.value == "notfound") {
                $('#stock-notifyme').modal('openModal');
                $('#attribute'+sizeId).val("");
            } else if(thistext.indexOf(soldOutText) != -1) {
                $('#stock-notifyme').modal('openModal');
            }
        });

        var url = notifyUrl;
        $('#btn_notifysubmit').click(function() {
            var salutation = $("#salutation-notifyme").val();
            var name = $("#name-notifyme").val();
            var lastname = $("#last-name-notifyme").val();
            var phone = $("#phone-notifyme").val();

            var email = $("#email-notifyme").val();
            var size = $("#selected-notifyme").val();
            var productsku = $("#productsku").val();

            var isValid = $("#stock-notifyme-form").valid();

            if (!isValid) {
                return false;
            }

            $("#stock-notifyme-error").html('');
            $('.stock-notifyme-form').hide();
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
                    $('#stock-notifyme-loader').show();
                },
                complete: function(){
                    $('#stock-notifyme-loader').hide();
                },
                success: function (response) {
                        if(response.errors == false) {
                        $("#stock-notifyme input").val('');
                        $('#stock-notifyme').modal('closeModal');
                        $('#stock-notifyme-success').modal('openModal');
                        $("#stock-notifyme-result").html(response.message);
                        $('.stock-notifyme-form').show();
                    } else {
                        $("#stock-notifyme-error").html(response.message);
                        $('.stock-notifyme-form').show();
                    }
                }
            });
        });
    }
});
