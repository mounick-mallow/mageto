define([
    'jquery'
], function ($) {
    
    return function (config) {
        $(document).ready(function() {
            $('.wishlist-redirect').on('click', function () {
                var dataUrl = $(this).attr('data-url');
                window.location.href= dataUrl;
            });

            $('.wishlist-continue').on('click', function () {
                $('.wishlist-requst-modal-pular .action-close').trigger('click');
            });

            // Function to close the modal
            function closePopupSpecialRequest() {
                $("#wishlist-modal-addcontent").modal("closeModal");
            }
            window.closePopupspecialrequest = closePopupSpecialRequest;
        });
    }
    
});