define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/url',
    'owl.carousel/owl.carousel.min'
], function ($, modal, urlBuilder) {

    return function (config) {

        var configConfBuyNow = config.confBuyNow;
        var configCompositeCartViewUrl = config.compositeCartViewUrl;

        $(document).on('change', '.super-attribute-select', function() {
            const attrValue = $(this).val();
            $('#add-configurable-product-form-attr').val(attrValue);
        });

        $(document).on('click', '.add-conf-product-to-cart', function(e) {
            e.preventDefault();
            let data = $('#add-configurable-product-form').serialize();
            data += '&form_key=' + $.mage.cookies.get('form_key');

            $.ajax({
                url: urlBuilder.build('ajaxcart'),
                type: 'POST',
                data
            }).done(function (msg) {
                if (!msg.error) {
                    var ajaxContentBlock = $('.content-ajaxcart');
                    ajaxContentBlock.html(msg.popup);
                    ajaxContentBlock.find('.wp-product-container').css('display', 'none');
                    ajaxContentBlock.find('.actions').css('display', 'flex');
                    ajaxContentBlock.find('h1').text($.mage.__('Product is Added to Cart'));

                    if ($('.add-conf-product-to-cart').is(':visible')) {
                        $('.add-conf-product-to-cart').css('display', 'none');
                    }

                    ajaxContentBlock.find(".products-recommandation .owl-carousel").owlCarousel({
                        margin: 0,
                        nav: true,
                        navText: ["<span class='icon-icon-left'></span>","<span class='icon-icon-right'></span>"],
                        dots: false,
                        autoplay:true,
                        autoplayTimeout: 2000,
                        autoplayHoverPause:true,
                        loop:false,
                        responsive: {
                            0: {
                                items: 1
                            },
                            768: {
                                items:3
                            },
                            992: {
                                items:3
                            },
                            1200: {
                                items:3
                            }
                        }
                    });
                }
            })
        });

        $(document).on('click', '.modals-ajaxcart .action-close, .modals-ajaxcart .continue', function() {
            $("#modals_ajaxcart").modal("closeModal");
        });

        $.ajax({
            url: configCompositeCartViewUrl,
            type: 'POST',
            showLoader: true,
            cache: false,
            success: function (data) {
                var ajaxModal = $('#modals_ajaxcart');
                var modalToCart = ajaxModal.find('.view-cart');
                modalToCart.addClass('modal-tocart');

                productFormHtml = $(data).find('.product-add-form');
                $('.modal-popup #product-options-wrapper').html(productFormHtml);

                if (configConfBuyNow === true) {
                    $('.modals-ajaxcart').find('.right-main-section').addClass('buy-now-container');
                    $('.modal-popup #product-options-wrapper').find('#product-addtocart-button').hide();
                    $('.modal-popup #product-options-wrapper').find('.action.towishlist.primary.cls_wishlist').hide();
                }
                ajaxModal.trigger('contentUpdated');
                $('body').find('.product-option-btn').removeAttr('disabled');

                ajaxModal.on('click', '.modal-tocart', function(e){
                    var configurableToCart = ajaxModal.find('.ajaxcart-wrapper-main .action.tocart');
                    if (!configurableToCart.length) return;

                    e.preventDefault();
                    configurableToCart.trigger('click');
                    modalToCart.removeClass('modal-tocart');
                    return false;
                });
            }
        });



            $('.owl-carousel').find('.owl-nav').removeClass('disabled');
            $('.owl-carousel').on('changed.owl.carousel', function(event) {
                $(this).find('.owl-nav').removeClass('disabled');
            });

            $(".products-recommandation .owl-carousel").owlCarousel({
                margin: 0,
                nav: true,
                navText: ["<span class='icon-icon-left'></span>","<span class='icon-icon-right'></span>"],
                dots: false,
                autoplay:true,
                autoplayTimeout:2000,
                autoplayHoverPause:true,
                loop:false,
                responsive: {
                    0: {
                        items:1
                    },
                    768: {
                        items:3
                    },
                    992: {
                        items:3
                    },
                    1200: {
                        items:3
                    }
                }
            });



        $('.wishlist-redirect').on('click', function () {
            var dataUrl = $(this).attr('data-url');
            window.location.href= dataUrl;
        });
        $('.wishlist-continue').on('click', function () {
            $('.wishlist-requst-modal .action-close').trigger('click');
        });
        // Function to close the modal
        function closePopupSpecialRequest() {
            $("#wishlist-modal-addcontent").modal("closeModal");

        }
        window.closePopupspecialrequest = closePopupSpecialRequest;


    }
});
