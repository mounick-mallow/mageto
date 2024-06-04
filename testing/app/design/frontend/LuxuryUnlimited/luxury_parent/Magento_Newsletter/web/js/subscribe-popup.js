define([
    'jquery',
    'jquery/jquery.cookie',
    'fancybox/js/jquery.fancybox',
    'domReady!'
], function ($) {

    var infoBlock = $('#subscribe-popup-enabled-info');
    var enabled = infoBlock.attr('data-info');

    if ((enabled == "1" && $("body").hasClass("cms-index-index")) || enabled == 2) {
        var check_cookie = $.cookie('newsletter_popup');
        if (window.location != window.parent.location) {
            $('#newsletter_popup').remove();
        } else {
            if (check_cookie == null || check_cookie == 'shown') {
                let infoDelay = infoBlock.attr('data-delay') || 0;
                setTimeout(function () {
                    beginNewsletterForm();
                }, infoDelay);
            }
            $('#newsletter_popup_dont_show_again').on('change', function () {
                if ($(this).length) {
                    var check_cookie = $.cookie('newsletter_popup');
                    if (check_cookie == null || check_cookie == 'shown') {
                        $.cookie('newsletter_popup', 'dontshowitagain');
                    }
                    else {
                        $.cookie('newsletter_popup', 'shown');
                        beginNewsletterForm();
                    }
                } else {
                    $.cookie('newsletter_popup', 'shown');
                }
            });
        }
    } else {
        $('#newsletter_popup').hide();
    }

    function beginNewsletterForm() {
        $.fancybox({
            'padding': '0px',
            'autoScale': true,
            'transitionIn': 'fade',
            'transitionOut': 'fade',
            'type': 'inline',
            'href': '#newsletter_popup',
            'onComplete': function () {
                $.cookie('newsletter_popup', 'shown');
            },
            'tpl': {
                closeBtn: '<a title="Close" class="fancybox-item fancybox-close fancybox-newsletter-close" href="javascript:;"></a>'
            },
            'helpers': {
                overlay: {
                    locked: false
                }
            }
        });
        $('#newsletter_popup').trigger('click');
    }
});
