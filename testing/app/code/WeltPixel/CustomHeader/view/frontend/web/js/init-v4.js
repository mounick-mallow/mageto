require(["jquery"], function ($) {
        $(document).ready(function() {
            var design = 'desktop';
            /* desktop  */
            if (!$('.nav-toggle').is(':visible')) {
                $('.sections.nav-sections').appendTo('.header.content');
                design = 'desktop';
            } else {
                design = 'mobile';
            }

            jQuery(window).resize(function() {
                /* desktop */
                if (!$('.nav-toggle').is(':visible')) {
                    if (design == 'mobile') {
                        $('.sections.nav-sections').appendTo('.header.content');
                    }
                    design = 'desktop';
                } else {
                    if (design == 'desktop') {
                        $('.page-header-v4').after(jQuery('.sections.nav-sections'));
                    }
                    design = 'mobile';
                }
            });
        });
});
