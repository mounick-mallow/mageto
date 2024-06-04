require(['jquery', 'toggles_accordions_tabs'],
function   ($, SEMICOLONTABS) {
    $(document).ready( SEMICOLONTABS.widget.init() );
    $(window).on( 'resize', function() {
       // console.log(1);
        var t = setTimeout( function(){
                SEMICOLONTABS.widget.tabsJustify();
            }, 500 );
        });
});
