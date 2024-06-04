require([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function($, modal){
    'use strict';
    var options = {
        type: 'popup',
        responsive: true,
        title:  $.mage.__('Return/Cancel Order Request Ticket'),
        innerScroll: true,
        modalClass:'detail-rc-modal',
        buttons: [{
            text: $.mage.__('Return/Cancel Order Request Ticket'),
            class: 'ticketmodal1',
            click: function () {
                this.closeModal();
            }
        }]
    };

    modal(options, $('#order-return-ticket-modal'));

    var trackHelpOptions = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: 'modal-track-help',
        title: $.mage.__('Need Help?'),
        buttons: [{
            text: $.mage.__('Close'),
            class: 'modal-close',
            click: function (){
                this.closeModal();
            }
        }]
    };

    modal(trackHelpOptions, $('#order-track-help'));

    $(".order-status-popup").click(function(){
        var attrId = $(this).attr("order-content-id");

        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            modalClass: 'modal-track-order',
            title: $.mage.__('Track Order'),
            buttons: [{
                text: $.mage.__('Close'),
                class: 'modal-close',
                click: function (){
                    this.closeModal();
                }
            }]
        };

        modal(options, "#order-status-model-"+ attrId);
        $("#order-status-model-"+ attrId).find('.order-info-customer').show();
        $("#order-status-model-"+ attrId).modal("openModal");
    });

    $(".order-status-modal .close").click(function(){
        var attrId = $(this).attr("order-content-id");
        $("#order-status-model-"+ attrId).modal("closeModal");
    });

    $(document).on("click", '.need-help', function(event) {
        var parentFormId = $(this).parents(".order-status-modal").attr("id");
        var orderIncrementId =$(this).parents(".order-status-modal").find('.order-inc-no').html();
        var customerEmail =$(this).parents(".order-status-modal").find('.order-customer-email').html();
        var customerId =$(this).parents(".order-status-modal").find('.order-customer-id').html();
        if($("#"+parentFormId).parent(".modal-content").length) {
            $("#"+parentFormId).modal("closeModal");
        }
        $("#order-popup-form").get(0).reset();
        $("#order-popup-form").find("#keyword").val(orderIncrementId);
        $("#order-popup-form").find("#order-email").val(customerEmail);
        $("#order-popup-form").find("#order-customer-id").val(customerId);
        $("#order-popup-form").show();
        $(".ticket-created-success").hide();

        $("#order-track-help").modal("openModal");
    });

    $(".btn-create-ticket").on("click",function(e){

        var dataForm = $(this).closest('form');

        if(dataForm.validation('isValid')) {
            $.ajax({
                url: dataForm.attr('action'),
                type: dataForm.attr('method'),
                data: dataForm.serialize(),
                dataType: 'json',
                async: true,
                beforeSend: function () {
                },
                complete: function () {
                },
                success: function (response) {
                    // console.log(response);
                    $("#order-popup-form").hide();
                    $(".ticket-created-success").find(".response-container").html(response.message);
                    $(".ticket-created-success").show();

                    if (response.errors === false) {
                        $(".modal-track-help").find(".modal-title").html($.mage.__('Thank you!'));
                    }
                },
                error: function () {
                    alert('error');
                }
            });
        }
        event.stopImmediatePropagation();
        return false;
    });

    $('.return-order-button').click(function() {
        var realOrderId = this.id;
        $(this).closest('form').show();
        $("#order-return-ticket-modal").find("form").show();
        $("#order-return-ticket-modal").find(".ticket-created-success").hide();
        var ordId = $('input[name=recent-order-id-'+this.id+']').val();
        var ordIncId = $('input[name=recent-order-incid-'+this.id+']').val();
        var ordCanCancel = $('input[name=order-cancel-'+this.id+']').val();
        var ordCanBuyBack = 0;
        if($('input[name=order-buyback-'+this.id+']').length) {
            ordCanBuyBack = $('input[name=order-buyback-' + this.id + ']').val();
        }
        var orderReturnUrl = $('input[name=order-return-url]').val();
        $(".image-upload").hide();
        $(".input-file").val(null);
        $('#order-return-ticket-modal').parents(".modal-inner-wrap").find(".modal-title").text( $.mage.__('Return/Cancel Order Request Ticket'));
        if (ordCanBuyBack == 'true' && $(this).hasClass('order-buy-back')) {
            $(".image-upload").show();
            createBuyBackTicket(options, ordId, ordIncId);
        } else if (ordCanCancel == 'true') {
            createReturnTicket(options, ordId, ordIncId);
        } else {
            $.ajax({
                url: orderReturnUrl,
                type: "POST",
                data: {
                    type: 'orderReturn',
                    website: "www.brands-labels.com",
                    orderId: this.id
                },
                dataType: 'json',
                success: function (response) {
                    if(response.erpResponse == 1){
                        $('#order-return-popup' + realOrderId).modal(options).modal('openModal');
                    } else {
                        createReturnTicket(options, ordId, ordIncId);
                    }
                }
            });
        }
    });

    $('.cancel-order-button').click(function(){
        var orderNo = this.id.split("-").pop();
        $("#result-"+orderNo).text('');
        var ordId = $('input[name=recent-order-id-'+orderNo+']').val();
        createCancelTicket(options, ordId, orderNo);
    });
    $('.close-cancel-popup').click(function(){
        var orderNo = this.id.split("-").pop();
        $('.show-'+orderNo).fadeOut(200);
    });

    $('#orddercancel_btn_submit').click(function(event){
        var dataForm = $(this).closest('form');
        var Url = dataForm.attr("action");
        event.preventDefault();
        if(dataForm.validation('isValid')){
            var formData = new FormData(dataForm[0]);
            $.ajax({
                url: Url,
                type: dataForm.attr('method'),
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                async: true,
                beforeSend: function() {
                    $('#loader-message').show();
                    //console.log(dataForm.serialize());
                },
                complete: function(){
                    $('#loader-message').hide();
                },
                success: function (response) {
                    if(response.errors == false) {
                        dataForm.hide();
                        $('.ticket-created-success').find(".response-container").html(response.message);
                        $('.ticket-created-success').show();
                        //dataForm[0].reset();
                    } else {
                        dataForm.hide();
                        $('.ticket-created-success').find(".response-container").html(response.message);
                        $('.ticket-created-success').show();
                        dataForm[0].reset();
                    }
                },
                error: function (response) {
                    console.log(JSON.parse(response));
                },
            });
            event.preventDefault();
        }
    })

    $('input[name=order-return-submit]').click(function() {
        var orderNo = this.id.split("-").pop();
        var ordId = $('input[name=recent-order-id-'+orderNo+']').val();
        var ordIncId = $('input[name=recent-order-incid-'+orderNo+']').val();

        $.ajax({
            url: $('input[name=order-return-sub-url]').val(),
            type: "POST",
            data: {
                customer_email: $('input[name=customer-email-'+orderNo+']').val(),
                website: "www.brands-labels.com",
                order_id: orderNo,
                type: "return",
                reason:$("#order-return-reason-" + orderNo).val(),
                lang_code : $('input[name=orddercancel_lang_code]').val()
            },
            dataType: "JSON",
            beforeSend: function() {
                $('#orderreturnsubmit-' + orderNo).attr('disabled','disabled');
                $('#return-loader-' + orderNo).show();
            },
            success: function (response) {
                let status = response.erpResponse['status'],
                    message = response.erpResponse['message']
                    ;

                if (response.errors !== true && status !== 'failed') {
                    UpdateOrder(message, 'Return', ordIncId, ordId);
                } else {
                    $('#return-result-' + orderNo).html(message);
                }
            }
        });
    });

    $('.ordercancel-submit').click(function() {
        var orderNo = this.id.split("-").pop();
        var ordId = $('input[name=recent-order-id-'+orderNo+']').val();
        var ordIncId = $('input[name=recent-order-incid-'+orderNo+']').val();
        $.ajax({
            url: $('input[name=order-return-url]').val(),
            type: "POST",
            data: {
                type: 'orderCancel',
                website: "www.brands-labels.com",
                orderId: orderNo,
            },
            dataType: 'json',
            success: function (response) {
                if (response.erpResponse == true) {
                    $.ajax({
                        url: $('input[name=order-return-sub-url]').val(),
                        type: "POST",
                        data: {
                            customer_email: $('input[name=customer-email-'+orderNo+']').val(),
                            website:"www.brands-labels.com",
                            order_id: orderNo,
                            order_cancel_id: ordId,
                            type:"cancellation",
                            cancellation_type: "order",
                            reason:$("#ordercancel-reason-" + orderNo).val(),
                            lang_code : $('input[name=orddercancel_lang_code]').val()
                        },
                        dataType: "JSON",
                        beforeSend: function() {
                            $("#ordercancelsubmit-" + orderNo).attr('disabled','disabled');
                            $('#loader-'+orderNo).show();
                        },
                        success: function (jsonStr) {
                            var status = jsonStr['status'];
                            var message = jsonStr['message'];
                            if(status !== 'failed') {
                                UpdateOrder( message, 'Cancel', ordIncId, ordId);
                            } else if(status == 'failed') {
                                $("#result-"+orderNo).html(message);
                                $('#result-'+orderNo).delay(3000).fadeOut(800);
                            }

                            var data = JSON.stringify(jsonStr);
                            var obj = JSON.parse(data);
                            var errors = obj.errors;
                            console.log(jsonStr['status']);
                            if (errors) {
                                $.each( errors, function( key, value ) {
                                    $("#result-"+orderNo).html(key + " : " + value);
                                });
                                $("#ordercancel-submit-" + orderNo).removeAttr('disabled');
                            } else {
                                $("#result-"+orderNo).html(message);
                                $("#ordercancel-submit-"+orderNo).hide();
                            }
                        }
                    });
                } else {
                    $('.show-'+orderNo).fadeOut(200);
                    createCancelTicket(options, ordId, ordIncId);
                }
            }
        });
    });
    function createReturnTicket(options, ordId, ordIncId) {
        var allitemSkus = $('input[name=order-items-'+ordId+']').val();
        $('#order-return-ticket-modal').modal('openModal');

        $("#orddercancel_item").hide();
        $('#orddercancel_item').empty().append(
            '<option selected="selected" value="">Select Item</option>'
        );
        $("#orddercancel_brand").val(ordIncId);
        $("#orddercancelreturn_requesttype").val(2);
        $("#orddercancel_style").val(allitemSkus);
        $("#orddercancel_itemskus").val(allitemSkus);
        $("#orddercancel_tickettype").val(2);
        $("#orddercancel_keyword").val( $.mage.__('Order Return request'));
        $("#order-return-ticket-modal").modal("openModal");
        $("#orddercancel_order_id").val(ordId);
        $("#ordercancelreturn_reason").html($.mage.__(
            'Order is not eligible for Return. Please Create a Support Ticket for further assistance.'
        ))
    }

    function createBuyBackTicket(options, ordId, ordIncId) {
        options.title =  $.mage.__('Buy Back Request');
        var allitemSkus = $('input[name=order-items-'+ordId+']').val();
        $('#order-return-ticket-modal').modal('openModal');
        $('#order-return-ticket-modal').parents(".modal-inner-wrap").find(".modal-title").text( $.mage.__('Buy Back Request'));
        $("#orddercancel_item").hide();
        $('#orddercancel_item').empty().append(
            '<option selected="selected" value="">Select Item</option>'
        );
        $("#orddercancel_brand").val(ordIncId);
        $("#orddercancelreturn_requesttype").val(4);
        $("#orddercancel_style").val(allitemSkus);
        $("#orddercancel_itemskus").val(allitemSkus);
        $("#orddercancel_tickettype").val(4);
        $("#orddercancel_keyword").val( $.mage.__('Order Buy Back Request'));
        $("#order-return-ticket-modal").modal("openModal");
        $("#orddercancel_order_id").val(ordId);
        $("#ordercancelreturn_reason").html(
            $.mage.__('Order is not eligible for Buy Back. Please Create a Support Ticket for further assistance.'
        ))
    }

    function createCancelTicket(options, ordId, ordIncId) {
        var allitemSkus = $('input[name=order-items-'+ordId+']').val();
        $('#order-return-ticket-modal').modal('openModal');

        $("#orddercancel_item").hide();
        $("#orddercancel_brand").val(ordIncId);
        $("#orddercancelreturn_requesttype").val(3);
        $("#orddercancel_order_id").val(ordId);
        $("#orddercancel_keyword").val( $.mage.__('Order Cancel request'));
        $('#orddercancel_item').empty().append(
            '<option selected="selected" value="">Select Item</option>'
        );
        $("#orddercancel_style").val(allitemSkus);
        $("#orddercancel_itemskus").val(allitemSkus);
        $("#orddercancel_tickettype").val(3);
        $("#order-return-ticket-modal").modal("openModal");
        $("#ordercancelreturn_reason").html($.mage.__(
            'Order is not eligible for Cancel. Please Create a Support Ticket for further assistance.'
        ));
    }

    function UpdateOrder(preajax_message, updateType, orderNo, orderoriginal_id){
        $.ajax({
            url: $('input[name=order-update-order]').val(),
            type: "GET",
            data: {
                orderoriginal_id: orderoriginal_id,
                type: updateType,
                reason:$("#ordercancel-reason-" + orderNo).val(),
                lang_code : $('input[name=orddercancel_lang_code]').val()
            },
            dataType: "JSON",
            beforeSend: function() {

            },
            complete: function(){
                $('#loader-'+orderNo).hide();
            },
            success: function (jsonStr) {
                var status = jsonStr['status'];
                var message = jsonStr['message'];
                var allmessage;
                allmessage = message;
                $("#return-result-"+orderNo).html(allmessage);
            }
        });
    }

    $('#image_upload').on('change', function (event) {
        var imgCont = document.getElementById("preview-container");
        imgCont.innerHTML = '';
        for (let i = 0; i < event.target.files.length; i++) {
            var divElm = document.createElement('div');
            divElm.id = "rowdiv" + i;
            var spanElm = document.createElement('span');
            var image = document.createElement('img');
            image.src = URL.createObjectURL(event.target.files[i]);
            image.id = "output" + i;
            image.width = "200";
            spanElm.appendChild(image);
            var deleteImg = document.createElement('p');
            deleteImg.innerHTML = "x";
            deleteImg.dataset.name = event.target.files[i].name;
            deleteImg.onclick = function() {
                var uploadInput = document.getElementById("image_upload");
                var files = Array.from(uploadInput.files);
                var filteredFiles = files.filter(file => {
                    if (file.name == this.dataset.name) {
                        return false;
                    }

                    return true;
                });
                var dt = new DataTransfer();
                filteredFiles.forEach(elem => dt.items.add(elem));
                uploadInput.files = dt.files;
                this.parentNode.remove()
            };
            divElm.appendChild(spanElm);
            divElm.appendChild(deleteImg);
            imgCont.appendChild(divElm);
        }
    });
});
