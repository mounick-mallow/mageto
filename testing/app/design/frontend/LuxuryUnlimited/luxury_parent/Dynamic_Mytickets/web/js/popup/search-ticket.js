define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: 'search-request-modal',
        title: $.mage.__('Create Search Ticket'),
    };

    var popup = modal(options, $('#modal-search-content'));

    $("#create-search-ticket-btn").on('click', function () {
        var dataForm = $('.cls_popupsearchrequest_form');
        $('.clsmsgsuccessbox').parents('.modal-inner-wrap').find('.modal-title').text($.mage.__('Create Search Ticket'));
        $('.clsmsgsuccessbox').removeClass('success');
        $('.cls_popupsearchrequest_form').removeClass('success_hide');
        $('.clsmsgsuccessbox').removeClass('error-msg');
        $('.create-search-text').show();
        $('form.cls_popupsearchrequest_form').show();
        $('.clsmsgsuccessbox').hide();
        dataForm[0].reset();
        $("#modal-search-content").modal("openModal");

    });

    function closePopupsearchrequest() {
        $("#modal-search-content").modal("closeModal");
    }

    window.closePopupsearchrequest = closePopupsearchrequest;

    $(document).on('click', '#btn-search-ticket', function (e) {
        e.preventDefault();
        var dataForm = $('#' + $(this).closest('form').attr('id'));
        if (dataForm.validation('isValid')) {
            $("#result-message").text('');
            $.ajax({
                url: dataForm.attr('action'),
                type: dataForm.attr('method'),
                data: dataForm.serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $('#loader-message').show();
                },
                complete: function () {
                    $('#loader-message').hide();
                },
                success: function (response) {
                    if (response.errors === false) {
                        var thankYouText = $.mage.__('Thank you for your request!');
                        $('#result-message').html(response.message);
                        $('.clsmsgsuccessbox').addClass('success');
                        $('.cls_popupsearchrequest_form').addClass('success_hide');
                        $('.create-search-text').hide();
                        $('form.cls_popupsearchrequest_form').hide();
                        $('.clsmsgsuccessbox.success').show();
                        $('.clsmsgsuccessbox').parents('.modal-inner-wrap').find('.modal-title').text(thankYouText);
                    } else {
                        $('#result-message').html(response.message);
                        $('.clsmsgsuccessbox').addClass('error-msg');

                        dataForm[0].reset();
                    }
                },
                error: function (response) {
                    console.log(JSON.parse(response));
                }
            });
        }
    });
});
