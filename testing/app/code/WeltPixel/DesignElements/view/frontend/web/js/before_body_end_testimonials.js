require(['jquery', 'designelements_default', 'testimonialsGrid' ],
function   ($, SEMICOLONDEFAULT, SEMICOLONSTESTIMONIALSGRID) {
    $(document).ready(function() {
      // console.log(11);
        SEMICOLONDEFAULT.widget.init();
        SEMICOLONSTESTIMONIALSGRID.widget.init();
    });
    $(window).on( 'resize', function() {
      //  console.log(12);
        var t = setTimeout( function(){
            SEMICOLONSTESTIMONIALSGRID.widget.testimonialsGrid();
        }, 500 );
    });
});
