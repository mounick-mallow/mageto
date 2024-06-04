define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'mage/url'
],
    function($, alert, urlBuilder) {

    const addressInfBlock = $('#address-inf-block');

    $(".save-address-button").click(function() {
        var isValid = $("#form-validate").valid();
        var id = '';

        if (!isValid) {
            return false;
        }

        var button = $(this);

        button.prop('disabled', true);
        var form = $("#form-validate");
        $('body').trigger('processStart');
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',

            success: function(response) {
                const addressId = addressInfBlock.attr('data-id') || '';
                if (response.success && id !== addressId) {
                    // Show success popup
                    alert({
                        title: $.mage.__('Address Updated Successfully'),
                        content: $.mage.__('Your address information has been successfully updated in our records..'),
                        actions: {
                            always: function() {}
                        },
                        buttons: [{
                            text: $.mage.__('Back to Address book'),
                            class: 'action new',
                            click: function (event) {
                                // New action
                                this.closeModal(event, true);
                                window.location.href = urlBuilder.build('customer/address/index');
                            }
                        }]
                    });
                } else if (response.success && id === '') {
                    // Show success popup
                    alert({
                        title: $.mage.__('Address Created Successfully'),
                        content: $.mage.__('Your address information has been successfully created in our records.'),
                        actions: {
                            always: function() {}
                        },
                        buttons: [{
                            text: $.mage.__('Back to Address book'),
                            class: 'action new',
                            click: function (event) {
                                // New action
                                this.closeModal(event, true);
                                window.location.href =  urlBuilder.build('customer/address/index');
                            }
                        }]
                    });
                    $("#form-validate")[0].reset();
                } else {
                    // Show error message if needed

                }
                $('body').trigger('processStop');
                button.prop('disabled', false);
            },

            error: function(xhr, status, error) {
                // Handle error if needed
                $('body').trigger('processStop');
                button.prop('disabled', false);
            }
        });
    });
});
