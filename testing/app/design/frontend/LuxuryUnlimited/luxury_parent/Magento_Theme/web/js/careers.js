define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/url',
    'mage/mage'
], function ($, modal, urlBuilder) {

    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: 'career-request-modal',
        title: $.mage.__('Career Request'),
    };

    var popup = modal(options, $('#modal-search-content'));

    function closePopupsearchrequest() {
        $("#modal-search-content").modal("closeModal");
    }
    window.closePopupsearchrequest = closePopupsearchrequest;

    $('#btn_registers').click(function (event) {
        var url = urlBuilder.build('mytickets/ajax/career');
        event.preventDefault();
        var dataForm = $('.cls_careerfrind_form');
        if (dataForm.validation('isValid')) {
            $("#modal-search-content").modal("openModal");
            var formData = new FormData(dataForm[0]);
            $("#result-message").text('');
            $.ajax({
                url: url,
                type: 'POST',
                contentType: false,
                cache: false,
                processData:false,
                async: true,
                data: formData,
                beforeSend: function () {
                    $('#loader-message').show();
                },
                complete: function () {
                    $('#loader-message').hide();
                },
                success: function (response) {
                    if (response.errors == false) {
                        $('.ticket-created-success').find(".response-container").html(response.message);
                        $('.ticket-created-success').show();
                        $("#uploadcv").val('');
                        $('.resume-label-input').text('No file chosen');
                        dataForm[0].reset();
                    } else {
                        $('.ticket-created-success').find(".response-container").html(response.message);
                        $('.ticket-created-success').show();
                        dataForm[0].reset();
                    }
                },
                error: function (response) {
                    console.log(JSON.parse(response));
                },
            });
        }
    });
});
