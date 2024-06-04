define([
    "jquery",
    "Magento_Ui/js/modal/modal",
    "mage/url"
],function($, modal, urlBuilder) {

    var options = {
        type: 'popup',
        responsive: true,
        title: 'Main title',
        modalClass:'ticket-chat-modal',
        buttons: []
    };

    var popup = modal(options, $('#ticket-chat'));
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
            var merchantTitle = `<div class="name"><span>${$.mage.__('Admin')}</span></div>`;
            var messageData = '';
            var options = {
                year: "numeric",
                month: "long",
                day: "numeric"
            };
            var formattedDate = timestamp.toLocaleDateString('en-US', options);

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
