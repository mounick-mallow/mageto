define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    'use strict';

    return function () {
        $('.wishlist-redirect').on('click', function () {
            window.location.href= $(this).attr('data-url');
        });

        $('.wishlist-continue').on('click', function () {
            $('.wishlist-requst-modal .action-close').trigger('click');
        });

        // Function to close the modal
        function closePopupSpecialRequest() {
            $("#wishlist-modal-addcontent").modal("closeModal");
        }
        window.closePopupspecialrequest = closePopupSpecialRequest;

        $("#wishlist-continue-shopping, #to-wishlist").click(function() {
            $('#success-wishlist').modal('closeModal');
        });
    }
});
