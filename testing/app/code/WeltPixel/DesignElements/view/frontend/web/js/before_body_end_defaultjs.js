require(['jquery', 'designelements_default' ],
function   ($, SEMICOLONDEFAULT) {
    $(document).ready( SEMICOLONDEFAULT.widget.init() );
    $(window).on( 'resize', function() {
        var t = setTimeout( function(){
           // console.log(6);
            SEMICOLONDEFAULT.widget.dataResponsiveClasses();
            SEMICOLONDEFAULT.widget.dataResponsiveHeights();
            SEMICOLONDEFAULT.widget.verticalMiddle();
            SEMICOLONDEFAULT.widget.fullScreen();
        }, 500 );
    });
});
