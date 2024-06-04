define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'jquery-ui-modules/widget'
], function ($, modal) {
    'use strict';

    $.widget('mage.notifyRequest', {
        _create: function () {

            const modalForm = $('#modal-notify-me');
            const loaderOos = $('#loader-oos');
            const customSuccess = $('.custom-success');
            const url = this.options.notifyUrl;
            const dataProductSize = this.options.dataProductSize;

            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                modalClass: 'modal-product-oos',
                title: $.mage.__('Notify Requests'),
                buttons: []
            };

            modal(options, modalForm);

            $('.notifyme').click(function () {
                modalForm.modal("openModal");
            });

            $('#notifyme-btn').on('click', function () {
                const productSku = $('.notify-product-sku').val();
                const email = $('#email.notify-email').val();
                const phone = $('#phone.notify-phone').val();
                const data = {
                    product_sku: productSku,
                    email: email,
                    phone: phone
                };
                if (dataProductSize !== '') {
                    data.product_size = $(dataProductSize).val();
                }

                $.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    dataType: 'json',
                    async: true,
                    beforeSend: function () {
                        $('body').trigger('processStart');
                    },
                    complete: function () {
                        $('body').trigger('processStop');
                    },
                    success: function (response) {
                        $(modalForm).fadeOut(200);
                        $(loaderOos).show();
                        $(customSuccess).fadeIn(200);
                        $('.modal-product-oos').find('.modal-content').append(response.message);
                    },
                    error: function (response) {
                        modalForm.modal("closeModal");
                    },
                });
            });
        },
    });

    return $.mage.notifyRequest;
});