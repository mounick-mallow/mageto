define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {

    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: false,
        modalClass: 'modal-track-order',
        title: $.mage.__('Track Order'),
        buttons: []
    };


    $(document).on('click', '#track-order-error-ticket', function() {
        var helpModal = $('#order-track-help');
        var trackOrderModalError = $('#track-order-modal-error');

        var helpModalOptions = {
            type: 'popup',
            responsive: true,
            innerScroll: false,
            modalClass: 'modal-track-help',
            title: $.mage.__('Order Tracking Ticket'),
            buttons: []
        };

        trackOrderModalError.modal('closeModal');
        const referenceHelpModal = modal(helpModalOptions, helpModal);
        $("#order-track-help").modal("openModal");
    });

    var trackOrderModalOptions = {
        type: 'popup',
        responsive: true,
        innerScroll: false,
        modalClass: 'modal-track-help',
        title: $.mage.__('Order Tracking Error'),
        buttons: []
    };

    var trackOrderModalError = $('#track-order-modal-error');
    var trackOrderErrorReferenceModal = modal(trackOrderModalOptions, trackOrderModalError);

    $("#order-track").on('click', function (e) {
        e.preventDefault();
        var popup = modal(options, $('#order-track-modal'));
        var dataForm = $(this).closest('form');

        $.ajax({
            url: dataForm.attr('action'),
            type: dataForm.attr('method'),
            data: dataForm.serialize(),
            dataType: 'json',
            async: true,
            success: function (response) {
                if (response.errors == false) {
                    $(".track-order-content").html(response.html);
                    $(".track-order-content").find(".order-detail-item.price").each(function(){
                        var priceText = $(this).text();
                        $(this).html(priceText);
                    });
                    if ($("#order-track-help").parent(".modal-content").length) {
                        $("#order-track-help").modal("closeModal");
                    }

                    $("#order-track-modal").modal("openModal");

                } else {
                    if ($("#order-track-modal").parent(".modal-content").length){
                        $("#order-track-modal").modal("closeModal");
                    }

                    $("#order-popup-form").get(0).reset();
                    $("#order-popup-form").show();
                    $(".ticket-created-success").hide();

                    trackOrderModalError.find('.track-order-modal-error-result').html(response.html);
                    trackOrderModalError.modal("openModal");
                }
            }
        });
    });

    $(document).on("click", '.need-help', function(event) {
        var orderIncrementId =$(this).parents(".track-order-details").find('.order-inc-no').html();
        var customerEmail =$(this).parents(".track-order-details").find('.order-customer-email').html();
        var customerId =$(this).parents(".track-order-details").find('.order-customer-id').html();

        if($("#order-track-modal").parent(".modal-content").length) {
            $("#order-track-modal").modal("closeModal");
        }

        $("#order-popup-form").get(0).reset();
        $("#order-popup-form").find("#keyword").val(orderIncrementId);
        $("#order-popup-form").find("#order-email").val(customerEmail);
        $("#order-popup-form").find("#order-customer-id").val(customerId);
        $("#order-popup-form").show();
        $(".ticket-created-success").hide();
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: false,
            modalClass: 'modal-track-help',
            title: $.mage.__('Need Help?'),
            buttons: []

        };
        var referenceModal = modal(options, $('#order-track-help'));
        $("#order-track-help").modal("openModal");
    });

    $(".order-reference").on('click', function (e) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: false,
            modalClass: 'modal-track-reference',
            title: $.mage.__('How Do I find my order reference?'),
            buttons: []
        };

        var referenceModal = modal(options, $('#order-track-reference'));
        $("#order-track-reference").modal("openModal");
    });

    $(".btn-create-ticket").on("click",function(e){
        var dataForm = $(this).closest('form');
        if (dataForm.validation('isValid')) {
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


    function closePopupReference() {
        $("#order-track-modal").modal("closeModal");
    }

    window.closePopupReference = closePopupReference;
});
