define([
    "jquery",
    "Magento_Ui/js/modal/modal",
    "mage/url"
],function($, modal, urlBuilder) {
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: 'special-requst-modal help-modal',
        title: $.mage.__('Need Help with Returns?'),

    };

    var popup = modal(options, $('#help-content'));

    $("#help-btn").on('click', function () {
        var dataForm = $('.cls_popupspecialrequest_form');
        $('.clsmsgsuccessbox').parents('.modal-inner-wrap').find('.modal-title').text($.mage.__('Need Help with Returns?'));
        $('.clsmsgsuccessbox').removeClass('success');
        $('.cls_popupspecialrequest_form').removeClass('success_hide');
        $('.clsmsgsuccessbox').removeClass('error-msg');
        dataForm[0].reset();
        $("#help-content").modal("openModal");

    });


    function closePopupSpecialRequest() {
        $("#help-content").modal("closeModal");
        $("#ordercancel-popup-form").show();
        $(".clsmsgsuccessbox").hide();
    }


    window.closePopupspecialrequest = closePopupSpecialRequest;

    $(".done-btn").click(function () {
        closePopupSpecialRequest();
    });


    $(document).on('click', '#orddercancel_btn_submit', function(){
        var orderId = $(this).closest('form').find("#orddercancel_brand").val();
        $(this).closest('form').find("#orddercancel_order_id").val(orderId);
        var dataForm = $(this).parents("form");

        if (dataForm.validation('isValid')) {

            $.ajax({
                url: dataForm.attr('action'),
                type: dataForm.attr('method'),
                data: dataForm.serialize(),
                dataType: 'json',
                async: true,
                success: function (response) {
                    if(response.errors === false) {
                        dataForm.hide();
                        $(".clsmsgsuccessbox").show();
                        $('.clsmsgsuccessbox').addClass("success").removeClass("error-msg");
                        $(".clsmsgsuccessbox").find("#result-message").html(response.message);
                    } else {
                        dataForm.hide();
                        $(".clsmsgsuccessbox").show();
                        $('.clsmsgsuccessbox').addClass("error-msg").removeClass('success');
                        $(".clsmsgsuccessbox").find("#result-message").html(response.message);
                    }
                },
                error: function (response) {
                    console.log(JSON.parse(response));
                },
            });
            event.stopImmediatePropagation();

            return false;
        }
    });

    var chatOptions = {
        type: 'popup',
        responsive: true,
        title: $.mage.__('Messages'),
        modalClass:'ticket-chat-modal',
        buttons: []
    };

    var chatPopup = modal(chatOptions, $('#ticket-chat'));
    $(".icomoon-icon-chat").click(function() {
        var ticketCode =  $(this).data("tcode");
        var ticketId =  $(this).data("tid");
        $('<input>').attr({
            type: 'hidden',
            id: 'ticket-id',
            name: 'id',
            value: ticketId,
        }).appendTo('#ticket-chat-form');
        loadMessages(ticketId, ticketCode);
        $('#ticket-chat').modal('openModal');
    });

    function loadMessages(ticketId, ticketCode) {
        var url = urlBuilder.build('mytickets/ajax/message');
        $.ajax({
            url: url,
            type: "GET",
            data: {
                tid   : ticketId,
                tcode : ticketCode
            },
            dataType: 'json',
            async: true,
            showLoader: true,
            success: function (response) {
                if (response['errors']) {
                    alert({
                        title: $.mage.__('Error'),
                        content: response['message']
                    });
                } else {
                    var msgCountUnread = parseInt($(".unread-msg-cnt").text()) - parseInt(response['unread_count']);
                    $('.unread-msg-cnt').html(msgCountUnread);
                    buildChat(response);
                }
            },
            error: function (response) {
                alert({
                    title: $.mage.__('Error'),
                    content: response['message']
                });
            }
        });
    }

    function buildChat(response) {
        var ticketMessages = response['ticketMessages'];
        var messages = ticketMessages['messages'];
        var chatDate = null;
        var html = '';

        $.each(messages, function (key, message) {
            var timestamp = new Date(message['timestamp'] * 1000);
            var merchantClass = 'merchant'
            var merchantTitle = `<div class="name"><span>${$.mage.__('You')}</span></div>`;
            var messageData = '';
            var chatOptions = {
                year: "numeric",
                month: "long",
                day: "numeric"
            };
            var formattedDate = timestamp.toLocaleDateString('en-US', chatOptions);

            if (chatDate !== formattedDate) {
                chatDate = formattedDate;
                messageData = `
                        <div class="chat-date-container">
                            <span class="chat-date">${formattedDate}</span>
                        </div>`;
            }

            if (!message['is_merchant']) {
                merchantClass = '';
                merchantTitle = '';
            }

            html += `
                     ${messageData}
                     <div class="message-container ${merchantClass}">
                        <div class="message">
                            ${merchantTitle}
                            <div class="content">
                                <p>${message['message']}</p>
                            </div>
                        </div>
                        <div class="message-date">
                            <span>${timestamp.getHours()}:${timestamp.getMinutes()}</span>
                        </div>
                    </div>
                `;
        });

        if (!messages) {
            $('.chat-messages-wrapper').html(`<span>${$.mage.__('Let\'s start conversation')}</span>`);
        } else {
            $('.chat-messages-wrapper').html(html);
        }
    }
});
