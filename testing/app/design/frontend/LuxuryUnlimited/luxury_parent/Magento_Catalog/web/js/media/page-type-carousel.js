define([
    'jquery',
    'domReady!',
    'owl.carousel/owl.carousel.min'
], function ($) {
    $("#gallery_images").owlCarousel({
        autoplay: false,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        loop: true,
        navRewind: true,
        margin: 0,
        nav: true,
        navText: ["<em class='porto-icon-left-open-huge'></em>","<em class='porto-icon-right-open-huge'></em>"],
        dots: false,
        responsive: {
            0: {
                items:1
            },
            768: {
                items:1
            },
            992: {
                items:2
            },
            1200: {
                items:3
            }
        }
    });
    $(".product-info-main > .product-info-price").before($(".short-custom-block").show().detach());
    $(".page-main").after($(".fullwidth-custom-block").show().detach());
    $(".product-info-main > .prev-next-products").after($(".product-social-links").detach());
});
