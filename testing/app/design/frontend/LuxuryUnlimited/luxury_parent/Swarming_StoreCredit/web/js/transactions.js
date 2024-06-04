define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: 'special-requst-modal help-modal',
        title: $.mage.__('Need Help?'),
    };

    var referenceModal = modal(options, $('#order-track-help'));

    $("#help-btn").on('click', function () {
        $("#order-popup-form").get(0).reset();
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
                        $(".help-modal").find(".modal-title").html($.mage.__('Thank you!'));
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
});
