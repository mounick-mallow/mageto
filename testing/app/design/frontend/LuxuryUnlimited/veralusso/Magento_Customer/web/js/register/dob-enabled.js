define([
    'jquery',
    'mage/mage'
], function($){

    var dataForm = $('#form-validate');
    var ignore = 'input[id$="full"]';

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

    $(".customer-dob").find("#dob").attr("placeholder",$.mage.__("Date Of Birth"));
});
