define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'Magento_Ui/js/modal/confirm'
], function  ($, customerData, modal, $t, confirm) {
    'use strict';

    $.widget('belvg.guestwishlist', {
        options: {
            wishlistUrl: '',
            customerData: customerData,
            modal: modal,
            translate: $t,
            wishlist: [],
            addWish: '[data-action=add-to-wishlist]',
            linkWish: '#wishlist-link',
            modalWish: '#mini-wishlist-modal'
        },

        /** @inheritdoc */
        _create: function () {
            this.init();
        },

        init: function () {
            var guestObj = this;

            $(window).ready(
                function () {
                    guestObj.initControls();
                    guestObj.build(false, guestObj);
                }
            );
        },

        initControls: function () {
            var guestObj = this;
            $(document.body)
                .on('click', guestObj.options.addWish, function (event) {
                    if (!$(this).hasClass('updated')) {
                        event.stopImmediatePropagation();
                        event.preventDefault();
                        event.stopPropagation();

                        if (!$(this).hasClass('active')) {
                            $(this).addClass('active');
                            guestObj.addProduct($(this).data('post'), $(this));
                        } else {
                            $(this).removeClass('active');
                            guestObj.removeProduct($(this).data('post'), $(this));
                        }
                        return false;
                    }
                });

            if ($(guestObj.options.modalWish).length) {
                $(guestObj.options.modalWish).removeClass('no-display');

                var popupWishlist = guestObj.options.modal({
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    title: guestObj.options.translate('My Wish List'),
                    buttons: [{
                        text: guestObj.options.translate('Go to Wish List'),
                        class: 'action primary',
                        click: function () {
                            window.location.href = guestObj.options.wishlistUrl;
                        }
                    }]
                }, $(guestObj.options.modalWish));
            }

            $(document.body).on('click', guestObj.options.linkWish, function () {
                $(guestObj.options.modalWish).modal('openModal');

                return false;
            });
        },

        build: function (response, guestObj) {
            if (response != false) {
                var sections = ['wishlist'];

                guestObj.options.customerData.invalidate(sections);
                guestObj.options.customerData.reload(sections, true);
            }
        },

        addProduct: function (postData, element = null) {
            var $this = this;
            if (typeof postData != "undefined") {
                postData.data.form_key = $.cookie('form_key');
                let customer = customerData.get('customer')();

                if (customer.firstname) {
                    postData.action = postData.action.replace('/guestwishlist', '/wishlist');
                }

                $.post(postData.action, postData.data, function (response) {
                    $this.build(response, $this);
                });
            } else {
                this.addToWishlistListing(element, 0);
            }
        },

        addToWishlistListing: function (obj, remove) {
            var $this = this;

            let options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                modalClass: 'wishlist-requst-modal',
                title: $.mage.__('Product Added to Wishlist'),
            };

            let popup = modal(options, $('#wishlist-modal-addcontent'));

            $("#wishlist-success").show();
            $("#wishlist-modal-addcontent").find('.wishlist-data').show();
            let ajaxCustomerWishlist = obj.attr('data-url');
            let result = $.parseJSON(obj.attr('data-product'));
            $.ajax({
                url: ajaxCustomerWishlist,
                type: 'POST',
                async: true,
                data: {
                    'product': result.data.product,
                    'remove': remove,
                    'uenc': result.data.uenc
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
                        $('#wishlist-modal-addcontent').modal(options).modal('openModal');
                    }
                    if (response.data.type == 'success') {
                        let message = response.data.message;
                        if (response.data.image && response.data.product_name) {
                            message += "<div style='display: flex; justify-content: space-around; align-items: center'>";
                            message += '<img src="' + response.data.image + '" />';
                            message += response.data.product_name;
                        }
                        if (response.data.show_confirm) {
                            confirm({
                                title: $.mage.__('Remove Product From Wishlist'),
                                modalClass: 'remove-wishlist modal-slide _inner-scroll',
                                content: message,
                                actions: {
                                    confirm: function(){
                                        $this.addToWishlistListing(
                                            $(".wishlist-listing-"+response.data.product),
                                            1
                                        );
                                    }
                                }
                            });
                        } else {
                            $("#wishlist-success").show();
                            $('#wishlist-loader-message').hide();
                            $(".wishlist-redirect").attr("data-url", response.data.redirect);
                            $("#wishlist-result-message").removeClass('error');
                            $("#wishlist-modal-addcontent").find('.wishlist-data').hide();
                            $(".wishlist-result-toolbar").show();
                            //$(".wishlist-requst-modal .modal-title").text('Thank you!');
                            $("#wishlist-result-message").html(message);
                            //$("#wishlist-modal-addcontent").modal("openModal");
                            $('#wishlist-modal-addcontent').modal(options).modal('openModal');
                        }
                    }
                }
            });
        },

        removeProduct: function (postData, element = null) {
            var $this = this;

            if (typeof postData != "undefined") {
                postData.data.form_key = $.cookie('form_key');

                var removeUrl = postData.action;

                removeUrl = removeUrl.replace('/add', '/removeProduct');
                removeUrl = removeUrl.replace('/wishlist', '/guestwishlist');

                $.post(removeUrl, postData.data, function (response) {
                    $this.build(response, $this);
                });
            } else {
                this.addToWishlistListing(element, 0);
            }
        }
    });

    return $.belvg.guestwishlist;
});
