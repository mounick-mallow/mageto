require([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/url'
], function ($, modal, urlBuilder) {
    const options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: 'siginerror-modal',
        title: $.mage.__('Customer Login'),
    };

    modal(options, $('#signin-error'));

    $(document).on('click', '#ajax-login-button', function () {
        const loginForm = $('#ajax-login-form');
        const isValid = loginForm.validation('isValid');
        if (isValid === true) {
            $.ajax({
                url: urlBuilder.build('belvg_login/index/post'),
                type: 'POST',
                dataType: 'json',
                data: loginForm.serialize()
            }).done(msg => {
                if (msg.success === false) {
                    $("#signin-error").modal("openModal");
                    return null;
                }

                location.reload();
            })
        }
    });
});
