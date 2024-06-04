define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'Magento_Ui/js/modal/alert',
    'mage/url',
    'mage/mage',
    'domReady!'
],function($, modal, alert, urlBuilder){

    $(".showticketmessage").click(function(){
        var UrlMessage = urlBuilder.build("mytickets/ajax/message");
        var tcode =  $(this).data("tcode");
        var tid =  $(this).data("tid");
        $.ajax({
            url: UrlMessage,
            type: "GET",
            data: {
                tid   : tid,
                tcode : tcode

            },
            dataType: 'json',
            async: true,
            showLoader: true ,
            beforeSend: function() {
                $("#ticketmesage-modal").html('');
            },
            complete: function() {

            },
            success: function (jsonStr) {
                //console.log(jsonStr);
                var errors = jsonStr['errors'];
                var message = jsonStr['message'];
                if(errors == false) {
                    var ticketMessagesArr = jsonStr['ticketMessages'];
                    showMessagePopup( tid , ticketMessagesArr);
                } else {
                    alert({
                        title: $.mage.__('Error'),
                        content: jsonStr.message,
                        actions: {
                            always: function(){}
                        }
                    });
                }
            },
            error: function (response) {
                alert({
                    title: $.mage.__('Error'),
                    content: response.message,
                    actions: {
                        always: function(){}
                    }
                });
            },
        });
    })

    $(".showticketnomessage").click(function(){
        var UrlMessage = urlBuilder.build("mytickets/ajax/message");
        var tcode =  $(this).data("tcode");
        var tid =  $(this).data("tid");
        $.ajax({
            url: UrlMessage,
            type: "GET",
            data: {
                tid   : tid,
                tcode : tcode

            },
            dataType: 'json',
            async: true,
            showLoader: true ,
            beforeSend: function() {
                $("#ticketmesage-modal").html('');
            },
            complete: function(){

            },
            success: function (jsonStr) {
                var errors = jsonStr['errors'];
                var message = jsonStr['message'];
                if(errors == false) {
                    var ticketMessagesArr = jsonStr['ticketMessages'];
                    showMessagePopup( tid , ticketMessagesArr);
                } else {

                    alert({
                        title: $.mage.__('Error'),
                        content: jsonStr.message,
                        actions: {
                            always: function(){}
                        }
                    });
                }
            },
            error: function (response) {
                alert({
                    title: $.mage.__('Error'),
                    content: response.message,
                    actions: {
                        always: function() {}
                    }
                });
            },
        });
    });


    $(".closepopup").live('click', function(){
        $("#replymessages").css('display','none');
    });

    function showMessagePopup(ticketId, messagesArr){
        if (messagesArr) {
            messageId =  "message" + ticketId;
            var addMessageUrl = urlBuilder.build('mytickets/ajax/addMessage?id=' + messagesArr['ticket_id']);
            var ticketreply = '';
            ticketreply = ticketreply+"<div class='messages_section'>"
                + "<form action='" + addMessageUrl + "' method='post' class='ticketaddmsg'>"
                + "<input type='text' id='message' name='message' placeholder='Type Your Message Here..'>"
                + "<input type='submit' value='Submit' class='add-btn'></form>";
            ticketreply = ticketreply
                + '<div class="ticket_info"><ul><li>' + $.mage.__('Message') + '</li><li>' + $.mage.__('Date Time') + '</li><li>' + $.mage.__('Sent By') + '</li></ul>';
            ticketreply = ticketreply + '<ul><li>'
                +messagesArr['message'] + '</li><li>'+messagesArr['created_at']
                +'</li><li>'+messagesArr['name']+'</li></ul>';

            ticketreply =  ticketreply+"<div class='closepopup'>×</div><div class='message_history'>";

            $.each( messagesArr['messages'], function( key, value ) {
                var date = new Date(value.created_at);
                ticketreply = ticketreply+"<div class='message_body'>";
                if (value.send_by !== 'Admin') {
                    ticketreply = ticketreply+"<div class='message-name'>"+ messagesArr['name'] +": </div>";
                } else {
                    ticketreply = ticketreply+"<div class='message-name'> Merchant: </div>";
                }
                ticketreply = ticketreply+"<div class='updated-at'>"+ date +"</div>";
                ticketreply = ticketreply+"<div class='message'>"+ value.message +"</div></div>";
            });
            if(messagesArr['status'] === 1){
                ticketreply = ticketreply+"</div></div></div>";
            }

            $("#replymessages").html(ticketreply);
            $("#replymessages").css('display','block');
        }
    }


    function showmessage(id){
        var messagesmodal = document.getElementById("messages"+tid);
        if (window.getComputedStyle(messagesmodal).display === "none") {
            messagesmodal.style.display = "block";
        }else{
            messagesmodal.style.display = "none";
        }
    }

    $('.tickets-customer-index .mobile-popup-detials .close').on('click',function(){
        $(this).parents('.modal').hide();
    });


    // ?ticketId

    $('.tickets-customer-index .ticket-content li.porto-icon-eye').on('click',function(){
        var ticketCode = $(this).attr('data-ticket');
        $('.tickets-customer-index .mobile-popup-detials[data-ticketId="'+ticketId+'"]').show();
    });

});
