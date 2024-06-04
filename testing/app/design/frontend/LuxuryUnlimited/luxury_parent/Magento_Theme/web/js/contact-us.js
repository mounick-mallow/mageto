define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: 'special-requst-modal help-modal contact-us-modal',
        title: $.mage.__("We'll Get In Touch Soon."),
    };

    var referenceModal = modal(options, $('#contact-us-result'));

    $(".action.submit.primary").on("click",function(e) {
        e.preventDefault();
        var dataForm = $(this).closest('form');
        if(dataForm.validation('isValid')) {
            $.ajax({
                url: dataForm.attr('action'),
                type: dataForm.attr('method'),
                data: dataForm.serialize(),
                dataType: 'json',
                async: true,
                success: function (response) {
                    console.log(response);
                    $('#contact-us-result .response-container').html(response.message);
                    $('#contact-us-result').modal("openModal");
                },
                error: function () {
                    alert('error');
                }
            });
        }
        e.stopImmediatePropagation();
        return false;
    });
});
