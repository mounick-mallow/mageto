define([
    "jquery",
    "Magento_Ui/js/modal/modal",
    "mage/mage"
], function($, modal) {
    'use strict';
    
    return function(config) {
        var dataForm = $('#form-validate');
        var ignoreVal = config.ignoreVal;
        var isDobEnable = config.isDobEnable;
        var ignore = '';
        if (ignoreVal == 1) {
            ignore = '\'input[id$="full"]\'';
        } else {
            ignore = 'null';
        }

        $(document).ready(function () {
            $("#skypem").datepicker({
                changeMonth: true,
                changeYear: true,
                onSelect: function (selectedDate) {
                    var marriage_date = $(this).val();
                    var hidden_date = $("#skype").val(marriage_date);
                }
            });

            var options = {
                type: 'popup',
                responsive: true,
                title: $.mage.__('Account Updated Successfully'),
                modalClass: 'modal-edit-account',
                buttons: [{
                    text: $.mage.__('Back to My Account'),
                    class: 'action primary',
                    click: function () {
                        dataForm.submit();
                    }
                }]
            };

            var popup = modal(options, $('#modal-edit'));
            $(".actions-toolbar button").click(function(e) {
                e.preventDefault();

                if(dataForm.validation('isValid')) {
                    $('#modal-edit').modal('openModal');
                }
            });
        });

         if (isDobEnable) {
            dataForm.mage('validation', {
                errorPlacement: function(error, element) {
                    if (element.prop('id').search('full') !== -1) {
                        var dobElement = $(element).parents('.customer-dob'),
                            errorClass = error.prop('class');
                        error.insertAfter(element.parent());
                        dobElement.find('.validate-custom').addClass(errorClass)
                            .after('<div class="' + errorClass + '"></div>');
                    }
                    else {
                        error.insertAfter(element);
                    }
                },
            ignore: ':hidden:not(' + ignore + ')'
            }).find('input:text').attr('autocomplete', 'off');

        } else {
            dataForm.mage('validation', {
                ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'
            }).find('input:text').attr('autocomplete', 'off');
        }
    }
});
