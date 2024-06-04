define([
    'jquery',
    'mage/url',
    'Magento_Ui/js/modal/modal',
    'mage/mage'
], function ($,urlBuilder, modal) {
    "use strict";
    return function (config, element) {
        let id = $(config.id);
        var item_id = config.id;
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            modalClass: 'wishlist-search-content',
            title: $.mage.__('Move to Wishlist')
        };
        var popup = modal(options, $('#modal-wishlist-content-'+item_id));

        // Function to close the modal
        function closePopupsearchrequest() {
            $("#modal-wishlist-content-"+item_id).modal("closeModal");

        }
        window.closePopupsearchrequest = closePopupsearchrequest;

        $('.action-close').click(function(){
            window.location.reload();
        });

        $('.move-to-wishlist-'+item_id).click(function (event) {
            event.preventDefault();
            var itemId = $(this).attr("id");
            var url = config.url;

            $.ajax({
                url: url,
                type: "POST",
                cache: false,
                dataType: 'json',
                data: { item: itemId },
                beforeSend: function () {
                    $('#loader-message').show();
                },
                complete: function () {
                    $('#loader-message').hide();
                },
                success: function (response) {
                    $("#modal-wishlist-content-"+item_id).modal("openModal");
                    if (response.type == 'success') {
                        $('.ticket-created-success').find(".product-message").html(response.message);
                        $('.ticket-created-success').find(".prod-image").attr("src", response.image);
                        $('.ticket-created-success').find(".image").show();
                        $('.moved-view-wishlist').attr('href',response.redirect);
                        $('.ticket-created-success').show();
                        $('.modal-footer').hide();
                    } else {
                        $('.ticket-created-success').find(".product-message").html(response.message);
                        $('.ticket-created-success').find(".image").hide();
                        $('.ticket-created-success').show();
                        $('.modal-footer').hide();
                    }
                },
                error: function (response) {
                    console.log(response);
                },
            });

        });
    };
});
