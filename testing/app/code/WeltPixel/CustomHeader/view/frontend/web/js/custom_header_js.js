require(['jquery', 'WeltPixel_CustomHeader/js/header_js'], function ($, Header) {
        $(document).ready(function () {
            //console.log('hii');
            Header.action();
        });

        $(window).on("load", function () {
            //console.log('hiii');
            Header.action();
        });

        var reinitTimer;
        $(window).on('resize', function () {
           // console.log('hiiii');
            clearTimeout(reinitTimer);
            reinitTimer = setTimeout(function() {Header.action();}, 100);
        });
});
