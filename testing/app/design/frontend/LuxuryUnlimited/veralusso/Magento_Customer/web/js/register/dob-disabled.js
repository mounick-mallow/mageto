require([
    'jquery',
    'mage/mage'
], function($){
    var dataForm = $('#form-validate');
    var ignore =  'null';

    dataForm.mage('validation', {
        ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'

    }).find('input:text').attr('autocomplete', 'off');

    $(".customer-dob").find("#dob").attr("placeholder",$.mage.__("Date Of Birth"));
});
