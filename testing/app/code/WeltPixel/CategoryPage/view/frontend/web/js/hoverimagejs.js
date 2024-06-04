requirejs(['jquery' ],
    function   ($) {
        $(window).bind("pageshow", function(event) {
            if (event.originalEvent.persisted) {
                $('img[data-origsrc]').each(function() {
                    if ($(this).attr('data-tmp')) {
                            $(this).attr('src', ($(this).attr('data-origsrc')));
                    }
                });
            }
    });
});
