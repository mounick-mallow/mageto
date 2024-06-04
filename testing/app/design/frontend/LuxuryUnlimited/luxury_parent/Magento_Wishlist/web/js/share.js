define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/url'
], function ($, modal, urlBuilder) {
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: 'wishlist-requst-modal',
        title: $.mage.__('Wishlist Sharing'),

    };

    var popup = modal(options, $('#wishlist-share-modal'));

    $(".wishlist-sharing-request-btn").on('click', function () {
        $("#wishlist-success").hide();
        $('#share_email_address').val('');
        $('#share_message').val('');
        $("#wishlist-result-message").removeClass('error');
        $('#wishlist-loader-message').hide();
        $("#wishlist-share-modal").find('.wishlist-data').show();
        $("#wishlist-share-modal").modal("openModal");
        $('.modal-footer').hide();
    });

    $(document).on('click', '.close-wishlist-share-modal', function () {
        $("#wishlist-share-modal").modal("closeModal");
    });

    $('.wishlistsharing-submit').on('click', function () {
        $('#wishlist-loader-message').val('');
        $('#wishlist-loader-message').hide();
        $("#wishlist-result-message").removeClass('error');
        var ajaxCustomerWishlist = urlBuilder.build('ajaxwishlist/index/share/');

        $.ajax({
            url: ajaxCustomerWishlist,
            type: 'POST',
            async: true,
            data: {
                'id': $('#wishlist_id').val(),
                'emails': $('#share_email_address').val(),
                'message': $('#share_message').val()
            },
            cache:false,
            dataType: "json",
            success: function (response) {
                if (response.data.type === 'error') {
                    $('#wishlist-loader-message').hide();
                    $("#wishlist-success").show();
                    $("#wishlist-result-message").addClass('error');
                    $(".wishlist-result-toolbar").hide();
                    $("#wishlist-result-message").text(response.data.message);
                }

                if (response.data.type === 'success') {
                    $("#wishlist-success").show();
                    $('#wishlist-loader-message').hide();
                    $("#wishlist-result-message").removeClass('error');
                    $("#wishlist-share-modal").find('.wishlist-data').hide();
                    $(".wishlist-result-toolbar").show();
                    $(".wishlist-requst-modal .modal-title").text($.mage.__('Thank you!'));
                    $("#wishlist-result-message").text(response.data.message);
                }
            }
        });
    });

    $('.wishlist-requst-modal .action-close').on('click', function () {
        $(".wishlist-requst-modal .modal-title").text('Wishlist Sharing');
        $('.modal-footer').hide();
    });

    function closePopupSpecialRequest() {
        $("#wishlist-share-modal").modal("closeModal");
    }

    window.closePopupspecialrequest = closePopupSpecialRequest;
});
