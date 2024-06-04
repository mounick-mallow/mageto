require(['jquery'],
    function ($) {

            var resizeIdClose,
                searchMod = $("#search-mod"),
                searchInput = $('#search'),
                ua = window.navigator.userAgent,
                msie = ua.indexOf("MSIE ");

            $(window).resize(function() {
                clearTimeout(resizeIdClose);
            });

            if (document.documentMode || /Edge/.test(navigator.userAgent)) {
                $( ".open-modal-search" ).wrap( "<a href='#search-mod'></a>" );
            }

            $(".open-modal-search").on('click', function(){
                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)){
                    searchMod.addClass("isOpenIE");
                }
                searchMod.addClass("isOpen");
                setTimeout(function(){ searchInput.select(); }, 500);
                window.location.hash = 'search-mod';
                if($('.page-header').hasClass('page-header-v4')){
                    $(".nav-sections-4.sticky-header").attr('style', 'z-index: 0 !important');
                    $(".nav-toggle, .logo").attr('style', 'z-index: 0');
                }
                window.history.pushState("", document.title, window.location.pathname);
            });

            $(".block.block-content").on('click', function(){
                onClickSearchBtn(searchMod);
            });

            $(".closebutton").on('click', function(e){
                closeSearchModal(searchMod);
                window.location.hash = 'search-mod';
                setTimeout(function(){
                    window.location.hash= '#';
                    window.history.pushState("", document.title, window.location.pathname);
                    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)){
                        searchMod.removeClass("isOpenIE");
                    }
                }, 500);

                if($('.page-header').hasClass('page-header-v4')) {
                    $(".nav-sections-4.sticky-header").attr('style', 'z-index: 10 !important');
                    setTimeout(function(){
                        $(".nav-toggle, .logo").attr('style', 'z-index: 14');
                    }, 500);
                }
            });

            function onClickSearchBtn(searchMod){
                if(searchMod.hasClass( "isOpen" )){
                    $('body').addClass("hidescroll");
                    $('.actions.wpx-pos-search button').prop("disabled", false); // Search button are now enabled.
                }
            }

            function closeSearchModal(searchMod){
                if (searchMod.length) {
                    searchInput.val('');
                    setTimeout(function(){
                        $('body').removeClass('hidescroll');
                        $('#searchautocomplete').hide();
                    }, 10);
                }
            }
        }
);


