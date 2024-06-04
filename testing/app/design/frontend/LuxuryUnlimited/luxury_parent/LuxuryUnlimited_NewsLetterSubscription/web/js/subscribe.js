define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    $('#newsletter-validate-detail').submit(function (e) {
        if ($(this).valid()) {
            var optionssubscribe = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                modalClass: 'subscribe-requst-modal-popup',
                title: $.mage.__('News letter Subscription'),
                buttons: []
            };
            var popup = modal(optionssubscribe, $('#subscribe-popup'));
            var form = $('#newsletter-validate-detail');
            var url = form.attr('action');
            var postData = form.serializeArray();

            $.ajax({
                url: url,
                dataType: 'json',
                type: 'POST',
                showLoader: true,
                data: $.param(postData),
                complete: function (data) {
                    if (typeof data === 'object') {
                        data = data.responseJSON;
                        if (typeof data != "undefined") {
                            if (data.error) {
                                $("#subscribe-success-popup").show();
                                $("#subscribe-result-message-popup").addClass('error');
                                $("#subscribe-result-message-popup").text(data.message);
                                $("#subscribe-popup").modal("openModal");
                            } else {
                                $("#subscribe-success-popup").show();
                                $("#subscribe-result-message-popup").removeClass('error');
                                $("#subscribe-popup").find('.subscribe-data').hide();
                                $("#subscribe-result-message-popup").text(data.message);
                                $("#subscribe-popup").modal("openModal");
                            }
                        } else {
                            $("#subscribe-success-popup").show();
                            $("#subscribe-result-message-popup").addClass('error');
                            $("#subscribe-result-message-popup").text(
                                $.mage.__('Something went wrong, please try again')
                            );
                            $("#subscribe-popup").modal("openModal");
                        }
                        $('#newsletter-validate-detail :input[type="submit"]').prop('disabled', false);
                        $('#newsletter').val('');
                    }
                }
            });
        }
        return false;
    });

    $('.subscribe-continue').on('click', function () {
        $('.subscribe-requst-modal-popup .action-close').trigger('click');
    });

    function closePopupSpecialRequest() {
        $("#subscribe-popup").modal("closeModal");
    }

    window.closePopupspecialrequest = closePopupSpecialRequest;
});
