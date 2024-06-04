define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/url'
], function ($, modal, urlBuilder) {

    return function (config) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            modalClass: 'wishlist-requst-modal',
            title: $.mage.__('Wishlist Sharing'),
        };

        var loginPopupOptions = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            modalClass: 'special-requst-modal',
            title: $.mage.__('Login'),
            buttons: [{
                text: $.mage.__('Close'),
                class: '',
                click: function () {
                    this.closeModal();
                }
            }]
        }

        var popup = modal(options, $('#wishlist-modal-content'));

        var wishlistLoginPopup = $('#wishlist-login-popup');
        var loginPopupModal = modal(loginPopupOptions, wishlistLoginPopup);

        $(document).on('click', '.wishlist-sign-in-button', function() {
            wishlistLoginPopup.modal('openModal');
        });

        $(document).on('click', '#wishlist-ajax-login-button', function (e) {
            e.preventDefault();
            const wishlistLoginForm = $('#wishlist-login-popup-form');
            const isValid = wishlistLoginForm.validation('isValid');

            if (isValid === true) {
                var data = $(this).closest('form').serialize();
                $.ajax({
                    url: urlBuilder.build('belvg_login/index/post'),
                    datType: 'json',
                    data,
                    type: 'POST'
                }).done(function (msg) {
                    if (msg.success === true) {
                        location.reload();
                        return null;
                    }

                    $('#wishlist-login-popup-form-errors').text(msg.error)
                })
            }
        });

        $(".wishlist-sharing-request-btn").on('click', function () {
            $("#wishlist-success").hide();
            $('#share_email_address').val('');
            $('#share_message').val('');
            $("#wishlist-result-message").removeClass('error');
            $('#wishlist-loader-message').hide();
            $("#wishlist-modal-content").find('.wishlist-data').show();
            $("#wishlist-modal-content").modal("openModal");
            $('.modal-footer').hide();
        });

        $('.wishlistsharing-submit').on('click', function () {
            $('#wishlist-loader-message').val('');
            $('#wishlist-loader-message').hide();
            $("#wishlist-result-message").removeClass('error');
            var ajaxCustomerWishlist = config.shareLink;   /// link is here
            $.ajax({
                url: ajaxCustomerWishlist,
                type: 'POST',
                async: true,
                data: {
                    'username': $('#user_name').val(),
                    'email': $('#user_email_address').val(),
                    'emails': $('#share_email_address').val(),
                    'message': $('#share_message').val()
                },
                cache:false,
                dataType: "json",
                success: function (response) {

                    if (response.data.type == 'error') {
                        $('#wishlist-loader-message').hide();
                        $("#wishlist-success").show();
                        $("#wishlist-result-message").addClass('error');
                        $(".wishlist-result-toolbar").hide();
                        $("#wishlist-result-message").text(response.data.message);
                    }
                    if (response.data.type == 'success') {
                        $("#wishlist-success").show();
                        $('#wishlist-loader-message').hide();
                        $("#wishlist-result-message").removeClass('error');
                        $("#wishlist-modal-content").find('.wishlist-data').hide();
                        $(".wishlist-result-toolbar").show();
                        $("#wishlist-result-message").text(response.data.message);
                    }
                }
            });
        });

        // Function to close the modal
        function closePopupSpecialRequest() {
            $("#wishlist-modal-content").modal("closeModal");
            $('.modal-footer').hide();
        }
        window.closePopupspecialrequest = closePopupSpecialRequest;
    }
});
