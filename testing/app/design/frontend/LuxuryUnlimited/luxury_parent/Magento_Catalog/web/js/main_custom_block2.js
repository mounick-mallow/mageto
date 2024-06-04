require([ 'jquery'], function($){
 $(document).ready(function($) {
     $('.dropdown-custom-attributes').click(function(){
         $(this).toggleClass('active');
         $('.custom-prod-attributes-block').slideToggle();
     });
     $('.dropdown-overview').click(function(){
         $(this).toggleClass('active');
         $('.product.attribute.overview').slideToggle();
     });
     
   $('#openSizeModal').click(function(){
     $('#sizeModal').css({"display":"block"});
   });
   $('#close-size-modal').click(function(){
     $('#sizeModal').css({"display":"none"});
   });

 });
});
