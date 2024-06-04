define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/url'
], function ($, modal, urlBuilder) {
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: 'special-requst-modal',
        title: $.mage.__('Special Requests'),
    };

    var popup = modal(options, $('#special-requests-modal'));

    $("#clsspecialrequest-btn").on('click', function () {
        var dataForm = $('.cls_popupspecialrequest_form');
        $('.clsmsgsuccessbox').removeClass('success');
        $('.cls_popupspecialrequest_form').removeClass('success_hide');
        $('.clsmsgsuccessbox').removeClass('error-msg');
        dataForm[0].reset();
        $("#special-requests-modal").modal("openModal");
    });

    // Function to close the modal
    function closePopupSpecialRequest() {
        $("#special-requests-modal").modal("closeModal");
    }
    window.closePopupspecialrequest = closePopupSpecialRequest;
    $(document).on('change', '#specialcountry', function () {
        var $this = $(this);
        var selectedOption = $(this).find(':selected');
        var countryCode = selectedOption.val();
        var countryCodeLowercase = selectedOption.val().toLowerCase(); // Convert the country code to lowercase
        var flagIconClass = 'fi-' + countryCodeLowercase; // Use lowercase country code
        var optionTextLowercase = selectedOption.text().toLowerCase();
        $('.selected-flag i').attr('class', 'fi ' + flagIconClass);
        selectedOption.text(optionTextLowercase);

        $.ajax({
            url: urlBuilder.build('belvg_country/index/index'),
            type: 'get',
            data: 'country_id=' + countryCode,
            dataType: 'json'
        }).done(function(msg) {
            if (msg.success === true) {
                $this.closest('.field').find('input').val(msg.phone_code);
            }
        });
    });
});
