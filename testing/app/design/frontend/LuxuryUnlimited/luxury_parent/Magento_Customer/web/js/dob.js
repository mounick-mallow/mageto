define([
    'jquery',
    'mage/mage'
], function ($) {
    "use strict";

    return function(config) {
        var ignoreVal = config.ignoreVal;
        var isDobEnable = config.isDobEnable;
        var ignore = '';

        var dataForm = $('#form-validate');

        if (ignoreVal == 1) {
            ignore = '\'input[id$="full"]\'';
        } else {
            ignore = 'null';
        }

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

        $(".customer-dob").find("#dob").attr("placeholder","Date Of Birth");
    }
});