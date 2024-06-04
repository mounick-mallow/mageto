
define([
    'jquery', 
    'Magento_Ui/js/modal/modal'
], 
function ($, modal) {

    return function config(config) {
        let orderId = config.orderId,
        itemId = config.itemId,
        originalOrderId = config.originalOrderId,
        productSku = config.productSku,
        customerEmail = config.customerEmail,
        langCode = config.langCode,
        refundNote = config.refundNote,
        productName = config.productName,
        sku = config.sku,
        exchangeNote = config.exchangeNote;
        sendReturnUrl = config.sendReturnUrl,
        updateItemUrl = config.updateItemUrl,
        checkReturnUrl = config.checkReturnUrl,
        websiteId = 'www.brands-labels.com',
        modalId = '#order-return-ticket-modal';

        $("#item-return-" + orderId + '-' + itemId).click(function () {

            let returnType = '2',
                itemType = 'itemReturn',
                returnAction = 'Return',
                returnReason = $.mage.__(refundNote),
                returnOptions = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    modalClass: 'detail-rc-modal',
                    title: $.mage.__('Return Item Request Ticket'),
                    buttons: []
                };
    
            isReturnCancelEligible(itemType).done(function (result) {
    
                if (result.erpResponse === 1 || result.erpResponse === true) {
                    returnOptions.title = $.mage.__('Return Item: '+ productName);
                    let canReturn = $('#item-can-return-' + itemId);
                    modal(returnOptions, $(canReturn));
                    $(canReturn).modal('openModal');
    
                    $('#order-return-submit-' + orderId + '-' + itemId).click(function (e) {
                        e.stopImmediatePropagation();
                        const returnReason = $('#order-return-reason-' + orderId + '-' + itemId).val();
                        $('#result-'+sku).text('').css({'display': 'block'});
                        $.ajax({
                            url: config.sendReturnUrl,
                            type: 'post',
                            data: {
                                customer_email: customerEmail,
                                website: websiteId,
                                order_id: orderId,
                                orderoriginal_id: originalOrderId,
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
                                    UpdateOrderItem(message, 'Return', returnReason);
                                    $('#result-'+sku).text(response.message);
                                    $('#result-'+sku).delay(500).fadeOut(8000);
                                } else {
                                    $('#result-'+sku).text(response.message);
                                    $('#result-'+sku).delay(3000).fadeOut(800);
                                }
                            }
                        });
                    });
                } else {
                    returnReason = $.mage.__(
                        'Item is not eligible for Refund. Please Create a Support Ticket for further assistance.'
                    );
                    showItemRequestTicket(returnType, returnAction, returnOptions, returnReason);
                }
            }).fail(function () {
                returnReason = $.mage.__(
                    'Item is not eligible for Refund. Please Create a Support Ticket for further assistance.'
                );
                showItemRequestTicket(returnType, returnAction, returnOptions, returnReason);
            });
        });

        $("#item-exchange-" + orderId + '-' + itemId).click(function () {
            $('.result-exchange-'+itemId).show();
            $('#result-exchange-'+itemId).text('');
            let returnType = '6',
                itemType = 'itemExchange',
                returnAction = 'Exchange',
                returnReason = $.mage.__(exchangeNote),
                returnOptions = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    modalClass: 'detail-rc-modal',
                    title: $.mage.__('Exchange Item Request Ticket'),
                    buttons: [{
                        text: $.mage.__('Close'),
                        class: 'ticketmodal1',
                        click: function () {
                            this.closeModal();
                        }
                    }]
                };
    
                isReturnCancelEligible(itemType).done(function (result) {
    
                if (result.erpResponse === true) {
                    returnOptions.title = $.mage.__('Exchange Item: '+ productName);
                    let canReturn = $('#item-can-exchange-' + itemId);
                    modal(returnOptions, $(canReturn));
                    $(canReturn).modal('openModal');
    
                    $('#order-exchange-submit-' + orderId + '-' + itemId).click(function () {
                        const returnReason = $('#order-exchange-reason-' + itemId).val();
                        $('.result-exchange-'+itemId).show();
                        $('#result-'+sku).text('').css({'display': 'block'});
                        $.ajax({
                            url: sendReturnUrl,
                            type: 'post',
                            data: {
                                customer_email: customerEmail,
                                website: websiteId,
                                order_id: orderId,
                                orderoriginal_id: originalOrderId,
                                item_id: itemId,
                                product_sku: productSku,
                                type: 'exchange',
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
                                    UpdateOrderItem(message, 'Exchange', returnReason);
    
                                    $('#result-exchange-'+itemId).text(response.message);
                                    $('.result-exchange-'+itemId).hide();
                                } else {
                                    $('#result-'+sku).text(response.message);
                                    $('#result-'+sku).delay(3000).fadeOut(800);
                                }
                            }
                        });
                    });
    
                } else {
                    returnReason = $.mage.__(
                        'Item is not eligible for exchange. Please create a ticket for further assistance.'
                    );
                    showItemRequestTicket(returnType, returnAction, returnOptions, returnReason);
                }
            }).fail(function () {
                returnReason = $.mage.__(
                    'Item is not eligible for exchange. Please create a ticket for further assistance.'
                );
                showItemRequestTicket(returnType, returnAction, returnOptions, returnReason);
            });
        });

        $('#item-cancel-' + orderId + '-' + itemId).click(function () {
            var objRef = this;
            let cancelType = '3',
            itemType = 'itemCancel',
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
                    text: $.mage.__('Cancel Item Request'),
                    class: 'ticketmodal1',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };
            var itemStatus = $(objRef).parents('tr').find('.item-status-popup').attr('rel');

            if (itemStatus =="ordered" || itemStatus=="processing") {
                let canCancel = $('.show-' + orderId);
                cancelOptions.title = $.mage.__('Cancel Order #' + orderId);
                modal(cancelOptions, $(canCancel));
                $(canCancel).modal('openModal');

                $('#ordercancel-submit-' + orderId + '-' + itemId).click(function () {
                    const cancelReason = $('#ordercancel-reason-' + orderId + '-' + itemId).val();
                    $.ajax({
                        url: sendReturnUrl,
                        type: 'post',
                        data: {
                            customer_email: customerEmail,
                            website: websiteId,
                            order_id: orderId,
                            orderoriginal_id: originalOrderId,
                            item_id: itemId,
                            product_sku: productSku,
                            type: 'cancellation',
                            cancellation_type: 'products',
                            reason: cancelReason,
                            lang_code: langCode
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            $('#ordercancel-submit-' + orderId + '-' + itemId).attr('disabled', 'disabled');
                            $('#loader-' + orderId).show();
                        },
                        success: function (jsonStr) {
                            const status = jsonStr['status'],
                                message = jsonStr['message'],
                                obj = JSON.parse(JSON.stringify(jsonStr)),
                                errors = obj.errors;

                            if (status !== 'failed') {
                                UpdateOrderItem(message, 'Cancel', cancelReason);
                            } else {
                                $('#result-' + orderId).text(message);
                                $('#loader-' + orderId).hide();
                            }

                            if (errors) {
                                $.each(errors, function (key, value) {
                                    $('#result-' + orderId).text(key + " : " + value);
                                });
                                $('#loader-' + orderId).hide();
                                $('#ordercancel-submit-' + orderId + '-' + itemId).removeAttr('disabled');
                            }
                        }
                    });
                });
            } else {
                showItemRequestTicket(cancelType, cancelAction, cancelOptions, cancelReason);
            }
        });

        function isReturnCancelEligible(itemType) {
            return $.ajax({
                url: checkReturnUrl,
                type: 'post',
                data: {
                    type: itemType,
                    website: websiteId,
                    orderId: orderId,
                    productSku: productSku,
                },
                dataType: 'json'
            });
        }

        function showItemRequestTicket(ticketRequestType, action, options, reason) {
            $('#orddercancel_brand').val(orderId);
            $('#orddercancel_itemskus').val(productSku);
            $('#orddercancel_keyword').val($.mage.__('Item ' + productSku + ' ' + action + ' Request'));
            $('#orddercancel_order_id').val(originalOrderId);
            $('#orddercancel_style').val(productSku);
            $('#orddercancel_tickettype').val(ticketRequestType);
            $('#orddercancelreturn_requesttype').val(ticketRequestType);
            $('#order-refund-amount').html(refundNote);
            $('#ordercancelreturn_reason').html(reason);
            $(".image-upload").hide();
            modal(options, $(modalId));
            $(modalId).modal('openModal');
        }

        function UpdateOrderItem(preajax_message, updateType, reason) {
            $.ajax({
                url: updateItemUrl,
                type: 'post',
                data: {
                    orderoriginal_id: originalOrderId,
                    item_id: itemId,
                    type: updateType,
                    reason: reason,
                    lang_code: langCode
                },
                dataType: 'json',
                complete: function () {
                    $('#loader-' + orderId).hide();
                },
                success: function (jsonStr) {
                    var status = jsonStr['status'];
                    var message = jsonStr['message'];
                    allmessage = preajax_message + "<br />" + message;
                    $("#result-" + orderId).html(allmessage);
                }
            });
        }
    }
    
})
