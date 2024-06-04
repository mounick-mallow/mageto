require([
    'jquery',
    'mage/url',
    'Magento_Ui/js/modal/modal'
], function ($, url, modal) {
    const affiliateForm = $('#affiliate-form');
    const resultPopup = $('#affiliate-request-result');

    $(document).on('click', '#affiliate-submit', function(e) {
        e.preventDefault();
        const isValid = affiliateForm.validation('isValid');
        if (isValid === true) {
            $.ajax({
                url: url.build('affiliate/index/post'),
                dataType: 'json',
                type: 'post',
                data: affiliateForm.serialize()
            }).done(msg => {
                if (msg.error === false) {
                    const options = {
                        type: 'popup',
                        responsive: true,
                        innerScroll: true,
                        title: $.mage.__('Success!'),
                    };

                    affiliateForm[0].reset();
                    modal(options, resultPopup);
                    resultPopup.modal("openModal");
                }
            });
        }
    });
});
