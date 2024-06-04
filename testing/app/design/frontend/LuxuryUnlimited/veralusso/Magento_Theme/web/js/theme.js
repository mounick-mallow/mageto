// Product information

require([ 'jquery'], function($){
 $(document).ready(function($) {
   $('#openSizeModal').click(function(){
     $('#sizeModal').css({"display":"block"});
   });
   $('#close-size-modal').click(function(){
     $('#sizeModal').css({"display":"none"});
   });

 });
});


// Recommendation -recent

require([
      'jquery',
      'owl_carousel'
], function ($) {
      /* START CODE FOR TAB */
    $(".custtabs .tab_content").hide();
    $(".custtabs .tab_content:first").show();

    $(".custtabs ul li").click(function() {
      $(".custtabs ul li").removeClass("active");
      $(this).addClass("active");
      $(".custtabs .tab_content").hide();
      var activeTab = $(this).attr("rel");
      $("#"+activeTab).fadeIn();
    });
    /* END CODE FOR TAB */


    $('#myspecialreqprod').click(function(){
      $("#myModalspec").css("display","block");
    });




      // $(".products-recommandation .owl-carousel").owlCarousel({
        // margin: 0,
        // nav: false,
        // navText: ["<em class='porto-icon-left-open-huge'></em>","<em class='porto-icon-right-open-huge'></em>"],
        // dots: false,
        // autoplay:true,
      // autoplayTimeout:2000,
      // autoplayHoverPause:true,
      // loop:true,
        // responsive: {
          // 0: {
            // items:2
          // },
          // 768: {
            // items:2
          // },
          // 992: {
            // items:3
          // },
          // 1200: {
            // items:4
          // }
        // }
      // });
});


require(['jquery'],
 function($) {
    'use strict';
    return  {
        closePopupspecialrequest: function () {
          var modal = document.getElementById("myModalspec");
          modal.style.display = "none";
        }
    }

});


// contact -us

require([
    'jquery',
], function ($)
{
    $(document).ready(function(){
     $('#myModal .close').click(function(){
      $('#myModal').hide();
    });

    });
});


// Influencer

require([
    'jquery',
], function ($)
{
    jQuery(document).ready(function(){
      jQuery("#close-message").click(function() {
        jQuery(".message-container").fadeOut("slow");
      });
    });

});







