define([
    'jquery',
    'mage/url',
    'Magento_Checkout/js/model/cart/cache',
    'Magento_Checkout/js/model/cart/totals-processor/default',
    'Magento_Checkout/js/model/quote',
    'Magento_Ui/js/modal/modal'

], function ($, urlBuilder, cartCache, totalsProcessor, quote, modal) {
    const couponTicketBlock = $('#coupon-ticket-modal');

    $(document).on('click', '#btn_ticket_coupon', function(e) {
        e.preventDefault();
        const ticketForm = $('#coupon-ticket-form');
        const isFormValid = ticketForm.validation('isValid');
        if (isFormValid) {
            $.ajax({
                url: ticketForm.attr('action'),
                type: 'POST',
                dataType: 'json',
                data: ticketForm.serialize()
            }).done(msg => {
                if (msg.errors === false) {
                    ticketForm[0].reset();
                    couponTicketBlock.find('.modal-content').html(msg.message);
                }
            });
        }
    });

    const initCouponTicket = () => {
        const options2 = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: $.mage.__('Coupon Ticket'),
            buttons: [
                {
                    text: $.mage.__('Close'),
                    click: function () {
                        this.closeModal();
                    }
                }, {
                    text: $.mage.__('Create Ticket'),
                    click: function () {
                        this.closeModal();
                        initCouponTicket();
                    }
                }
            ],
            opened: function($Event) {
                $(".modal-footer").hide();
            }
        };

        const couponTicketPopup = modal(options2, couponTicketBlock);
        couponTicketBlock.modal('openModal');
    }

    $('.discount-coupon-form .primary').on('click', function () {
        if ($(this).hasClass('apply')) {

            $('.coupon_error_message').html('');
            var couponText = $('.discount-coupon-form .field input[name="coupon_code"]').val();
            var remove = $('.discount-coupon-form input[name="remove"]').val();

            $.ajax({
                url: urlBuilder.build('checkout/cart/couponPost'),
                type: 'POST',
                dataType: 'json',
                data: {'form_key': $.mage.cookies.get('form_key'), 'remove': remove, 'coupon_code': couponText},
                showLoader: true,
                success: function (data) {
                    if (data.status === "success") {
                        $('.coupon_error_message').html('<div class="message success">' + data.message + '</div>');
                        $('.discount-coupon-form .actions-toolbar .primary.action').removeClass('apply');
                        $('.discount-coupon-form .actions-toolbar .primary.action').addClass('cancel');
                        $('.discount-coupon-form .actions-toolbar .primary.action').val('Cancel Coupon');
                        $('.discount-coupon-form .actions-toolbar .primary.action span').text($.mage.__('Cancel Coupon'))
                            .css("font-size", "12px");
                        $('.discount-coupon-form .field input[name="coupon_code"]').attr("disabled", true);

                        cartCache.clear('cartVersion');
                        totalsProcessor.estimateTotals(quote.shippingAddress());

                        //CUSTOM CODE
                        dataLayer.push({
                            'event': 'userEngagement',
                            'eventCategory': 'cart',
                            'eventAction': 'add_coupon_cart',
                            'eventLabel': couponText + ' - apply'
                        });
                        //CUSTOM CODE END
                    }

                    if (data.status === 'error') {
                        const errorModalBlock = $('#belvg-error-modal');
                        const options = {
                            type: 'popup',
                            responsive: true,
                            innerScroll: true,
                            modalClass: 'coupon-ticket',
                            title: $.mage.__('Coupon Error'),
                            buttons: [
                                {
                                    text: $.mage.__('Close'),
                                    click: function () {
                                        this.closeModal();
                                    }
                                }, {
                                    text: $.mage.__('Create Ticket'),
                                    click: function () {
                                        this.closeModal();
                                        initCouponTicket();
                                    }
                                }
                            ]
                        };

                        errorModalBlock.find('.modal-content').text(data.message);
                        const errorPopup = modal(options, errorModalBlock);
                        errorModalBlock.modal('openModal');
                        return null;
                    }
                },
                error: function (data) {
                    console.log('error');
                }
            });
        }

        if ($(this).hasClass('cancel')) {
            var couponText = $('.discount-coupon-form .field input[name="coupon_code"]').val();
            $.ajax({
                url: urlBuilder.build('checkout/cart/couponPost'),
                type: 'POST',
                dataType: 'json',
                data: {'form_key': $.mage.cookies.get('form_key'), 'remove': 1, 'coupon_code': couponText},
                showLoader: true,
                success: function (data) {
                    if (data.status === 'success') {
                        $('.coupon_error_message').html('<div class="message success">' + data.message + '</div>');
                        $('.discount-coupon-form .field input[name="coupon_code"]').removeAttr("disabled");
                        $('.discount-coupon-form .field input[name="coupon_code"]').val('');
                        $('.discount-coupon-form .actions-toolbar .primary.action').removeClass('cancel');
                        $('.discount-coupon-form .actions-toolbar .primary.action').addClass('apply');
                        $('.discount-coupon-form .actions-toolbar .primary.action').val('Apply Coupon');
                        $('.discount-coupon-form .actions-toolbar .primary.action span').text($.mage.__('Apply Coupon'));

                        cartCache.clear('cartVersion');
                        totalsProcessor.estimateTotals(quote.shippingAddress());

                        //CUSTOM CODE
                        dataLayer.push({
                            'event': 'userEngagement',
                            'eventCategory': 'cart',
                            'eventAction': 'add_coupon_cart',
                            'eventLabel': couponText + ' - cancel'
                        });
                        //CUSTOM CODE END
                    }

                    if (data.status === 'error') {
                        $('.coupon_error_message').html('<div class="message error">' + data.message + '</div>');
                    }

                    setTimeout(function () {
                        $('.coupon_error_message').html('');
                    }, 5000);
                },
                error: function (data) {
                    console.log('error');
                }
            });
        }
    });
});
