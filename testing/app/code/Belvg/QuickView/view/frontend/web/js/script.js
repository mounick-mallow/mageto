require([
    "jquery",
    "Magento_Ui/js/modal/modal",
    'mage/url',
    'slickslider'
], function($, modal, urlBuilder, slick) {

    const quickViewModalOptions = {
        type: 'popup',
        responsive: true,
        modalClass: 'quick-view-modal',
        innerScroll: true,
        title: $.mage.__('Quick View'),
        buttons: [{
            text: $.mage.__('Close'),
            class: 'modal-close',
            click: function () {
                this.closeModal();
            }
        }]
    };

    const quickViewModal = $('#quick-view-modal');
    modal(quickViewModalOptions, quickViewModal);

    const initSlickCarousel = () => {
        quickViewModal.find('.belvg-item-carousel').slick({
            slidesToShow: 3,
            slidesToScroll: 3,
            arrows: false,
            dots: false,
            infinite: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }

    $(document).on('click', '.belvg-quick-view-link', function() {
        const productId = $(this).attr('data-id');
        $.ajax({
            url: urlBuilder.build('quick_view/index/index'),
            type: 'get',
            data: 'id=' + productId,
            showLoader: true
        }).done(msg => {
            if (msg === null) {
                return null;
            }
            quickViewModal.find('.quick-view-content').html(msg);
            quickViewModal.modal('openModal');

            initSlickCarousel();
        });
    });
})
