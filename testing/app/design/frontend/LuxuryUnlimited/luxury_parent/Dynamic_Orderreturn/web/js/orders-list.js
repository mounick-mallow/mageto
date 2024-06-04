define(['jquery', 'Magento_Ui/js/modal/modal'], function ($, modal) {
    let dynamicOrdersListInfoBlock = $('#dynamic-orders-list-info');

    let websiteId = dynamicOrdersListInfoBlock.attr('data-store'),
        langCode = dynamicOrdersListInfoBlock.attr('data-lang'),
        modalId = '#order-return-ticket-modal-item';

    $(".item-return-button").click(function () {
        let returnType = '2',
            productSku = $(this).data("item-sku"),
            itemType = 'itemReturn',
            returnAction = 'Return',
            itemId = $(this).data("item-id"),
            productName = $("#order-item-id-" + itemId + ' .product-name').html(),
            incrementId = $("#order-item-id-" + itemId).data('increment-id'),
            orderId = $("#order-item-id-" + itemId).data('order-id'),
            returnReason = $(".return-reason-" + itemId).html(),
            sku = $("#order-item-id-" + itemId).data('sku'),
            returnOptions = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                modalClass: 'detail-rc-modal',
                title: $.mage.__('Return Item Request Ticket'),
                buttons: [{
                    text: $.mage.__('Close'),
                    class: 'ticketmodal1',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };

        isReturnCancelEligible(itemType, productSku, itemId, incrementId, sku, orderId).done(function (result) {
            if (result.erpResponse === true) {
                returnOptions.title = $.mage.__('Return Item: ') + productName;
                let canReturn = $('#item-can-return-' + itemId);
                modal(returnOptions, $(canReturn));
                $(canReturn).modal('openModal');

                $('#order-return-submit-' + incrementId + '-' + itemId).click(function () {
                    let returnReason = $('#order-return-reason-' + incrementId + '-' + itemId).val();
                    $('#result-'+sku).text('').css({'display': 'block'});
                    $.ajax({
                        url: $('input[name=order-return-sub-url]').val(),
                        type: 'post',
                        data: {
                            customer_email: $('input[name=customer-email-'+incrementId+']').val(),
                            website: websiteId,
                            order_id: incrementId,
                            orderoriginal_id: orderId,
                            item_id: itemId,
                            product_sku: productSku,
                            type: 'return',
                            reason: returnReason,
                            lang_code: langCode
                        },
                        dataType: 'json',
                        async: true,
                        beforeSend: function () {
                            $('#loader-'+sku).show();
                        },
                        complete: function () {
                            $('#loader-'+sku).hide();
                        },
                        success: function (response) {
                            if (response.errors === false) {
                                const message = response['message'];
                                UpdateOrderItem(message, 'Return', returnReason, itemId, orderId);
                            } else {
                                $('#result-'+sku).text(response.message);
                                $('#result-'+sku).delay(3000).fadeOut(800);
                            }
                        }
                    });
                });
            } else {
                showItemRequestTicket(returnType, returnAction, returnOptions, returnReason, incrementId, productSku, orderId, itemId);
            }
        }).fail(function () {
            showItemRequestTicket(returnType, returnAction, returnOptions, returnReason, incrementId, productSku, orderId, itemId);
        });
    });

    $('.item-cancel-button').click(function () {
        let cancelType = '3',
            itemType = 'itemCancel',
            productSku = $(this).data("item-sku"),
            itemId = $(this).data("item-id"),
            productName = $("#order-item-id-" + itemId + ' .product-name').html(),
            incrementId = $("#order-item-id-" + itemId).data('increment-id'),
            orderId = $("#order-item-id-" + itemId).data('order-id'),
            sku = $("#order-item-id-" + itemId).data('sku'),
            cancelAction = 'Cancel',
            cancelReason = $.mage.__(
                'Item is not eligible for Cancel. Please Create a Support Ticket for further assistance.'
            ),
            cancelOptions = {
                type: 'popup',
                responsive: true,
                title: $.mage.__('Cancel Item Request Ticket'),
                innerScroll: true,
                modalClass: 'detail-rc-modal',
                buttons: [{
                    text: $.mage.__('Cancel Item Request ,'),
                    class: 'ticketmodal1',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };

        isReturnCancelEligible(itemType, productSku, itemId, incrementId, sku, orderId).done(function (result) {
            if (result.erpResponse === true) {
                let canCancel = $('.show-item-' + itemId);
                cancelOptions.title = $.mage.__('Cancel Order Item: ') + productName;
                modal(cancelOptions, $(canCancel));
                $(canCancel).modal('openModal');

                $('#ordercancel-submit-' + incrementId + '-' + itemId).click(function () {
                    const cancelReason = $('#ordercancel-reason-' + incrementId + '-' + itemId).val();
                    $.ajax({
                        url: $('input[name=order-return-sub-url]').val(),
                        type: 'post',
                        data: {
                            customer_email: $('input[name=customer-email-'+incrementId+']').val(),
                            website: websiteId,
                            order_id: incrementId,
                            orderoriginal_id: orderId,
                            item_id: itemId,
                            product_sku: productSku,
                            type: 'cancellation',
                            cancellation_type: 'products',
                            reason: cancelReason,
                            lang_code: langCode
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            $('#ordercancel-submit-' + incrementId + '-' + itemId).attr('disabled', 'disabled');
                            $('#loader-' + sku).show();
                        },
                        success: function (jsonStr) {
                            const status = jsonStr['status'],
                                message = jsonStr['message'],
                                obj = JSON.parse(JSON.stringify(jsonStr)),
                                errors = obj.errors;

                            if (status !== 'failed') {
                                UpdateOrderItem(message, 'Cancel', cancelReason, itemId, orderId);
                            } else {
                                $('#result-' + sku).text(message);
                                $('#loader-' + sku).hide();
                            }

                            if (errors) {
                                $.each(errors, function (key, value) {
                                    $('#result-' + incrementId).text(key + " : " + value);
                                });
                                $('#loader-' + sku).hide();
                                $('#ordercancel-submit-' + incrementId + '-' + itemId).removeAttr('disabled');
                            }
                        }
                    });
                });
            } else {
                showItemRequestTicket(cancelType, cancelAction, cancelOptions, cancelReason, incrementId, productSku, orderId, itemId);
            }
        }).fail(function () {
            showItemRequestTicket(cancelType, cancelAction, cancelOptions, cancelReason, incrementId, productSku, orderId, itemId);
        });
    });

    function isReturnCancelEligible(itemType, productSku, itemId, incrementId, sku, orderId) {

        return $.ajax({
            url: $('input[name=order-return-url]').val(),
            type: 'post',
            data: {
                type: itemType,
                website: websiteId,
                orderId: incrementId,
                productSku: productSku,
            },
            dataType: 'json'
        });
    }

    function showItemRequestTicket(ticketRequestType, action, options, reason, incrementId, productSku, orderId, itemId) {

        $('#order-return-ticket-modal-item #orddercancel_brand_item').val(incrementId);
        $('#order-return-ticket-modal-item #orddercancel_itemskus').val(productSku);
        $('#order-return-ticket-modal-item #orddercancel_keyword_item').val($.mage.__('Item ' + productSku + ' ' + action + ' Request'));
        $('#order-return-ticket-modal-item #orddercancel_order_id').val(orderId);
        $('#order-return-ticket-modal-item #orddercancel_style_item').val(productSku);
        $('#order-return-ticket-modal-item #orddercancel_tickettype').val(ticketRequestType);
        $('#order-return-ticket-modal-item #orddercancelreturn_requesttype').val(ticketRequestType);
        $('#order-return-ticket-modal-item #order-refund-amount_item').html($('#order-refund-amount_item-'+itemId));
        $('#order-return-ticket-modal-item #orddercancel_phone').val($('input[name=customer-phone-'+incrementId+']').val());
        //$('#order-return-ticket-modal-item  #ordercancelreturn_reason_item').html(reason);
        let imageSrc = $('#order-item-id-'+itemId+' .product-image-element').attr('src');
        $('#order-return-ticket-modal-item #orddercancel_image').val(imageSrc);
        modal(options, $(modalId));
        $(modalId).modal('openModal');
    }

    function UpdateOrderItem(preajax_message, updateType, reason, itemId, orderId) {
        $.ajax({
            url: $('input[name=order-ajax-update-item]').val(),
            type: 'get',
            data: {
                orderoriginal_id: orderId,
                item_id: itemId,
                type: updateType,
                reason: reason,
                lang_code: langCode
            },
            dataType: 'json',
            complete: function () {
                $('#loader-' + itemId).hide();
            },
            success: function (jsonStr) {
                var status = jsonStr['status'];
                var message = jsonStr['message'];
                allmessage = preajax_message + "<br />" + message;
                $("#result-" + itemId).html(allmessage);
            }
        });
    }
})
