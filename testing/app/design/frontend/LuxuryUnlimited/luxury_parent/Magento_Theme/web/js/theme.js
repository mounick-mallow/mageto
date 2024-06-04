/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
//require([
//    'jquery',
//    'bootstrap/js/bootstrap.min'
//], function ($) {

//});
require([
    'jquery',
    'mage/smart-keyboard-handler',
    'mage/mage',
    'domReady!'
], function ($, keyboardHandler) {
    'use strict';
    $(document).ready(function(){
        $('.cart-summary').mage('sticky', {
            container: '#maincontent'
        });

        $('.panel.header .top-links-icon').clone().appendTo('#store\\.links');
    });
    keyboardHandler.apply();
});
/******************** Init Parallax ***********************/

require([
    'jquery'
], function ($) {
    (function() {
        var ev = new $.Event('classadded'),
            orig = $.fn.addClass;
        $.fn.addClass = function() {
            $(this).trigger(ev, arguments);
            return orig.apply(this, arguments);
        }
    })();
    $.fn.extend({
        scrollToMe: function(){
            if($(this).length){
                var top = $(this).offset().top - 100;
                $('html,body').animate({scrollTop: top}, 300);
            }
        },
        scrollToJustMe: function(){
            if($(this).length){
                var top = jQuery(this).offset().top;
                $('html,body').animate({scrollTop: top}, 300);
            }
        }
    });
    $(document).ready(function(){
        var windowScroll_t;
        $(window).scroll(function(){
            clearTimeout(windowScroll_t);
            windowScroll_t = setTimeout(function(){
                if(jQuery(this).scrollTop() > 100){
                    $('#totop').fadeIn();
                }else{
                    $('#totop').fadeOut();
                }
            }, 500);
        });
        $('#totop').off("click").on("click",function(){
            $('html, body').animate({scrollTop: 0}, 600);
        });
        if ($('body').hasClass('checkout-cart-index')) {
            if ($('#co-shipping-method-form .fieldset.rates').length > 0 && $('#co-shipping-method-form .fieldset.rates :checked').length === 0) {
                $('#block-shipping').on('collapsiblecreate', function () {
                    $('#block-shipping').collapsible('forceActivate');
                });
            }
        }
        $(".products-grid .weltpixel-quickview").each(function(){
            $(this).appendTo($(this).parent().parent().children(".product-item-photo"));
        });
        $(".word-rotate").each(function() {

            var $this = $(this),
                itemsWrapper = $(this).find(".word-rotate-items"),
                items = itemsWrapper.find("> span"),
                firstItem = items.eq(0),
                firstItemClone = firstItem.clone(),
                itemHeight = 0,
                currentItem = 1,
                currentTop = 0;

            itemHeight = firstItem.height();

            itemsWrapper.append(firstItemClone);

            $this
                .height(itemHeight)
                .addClass("active");

            setInterval(function() {
                currentTop = (currentItem * itemHeight);

                itemsWrapper.animate({
                    top: -(currentTop) + "px"
                }, 300, function() {
                    currentItem++;
                    if(currentItem > items.length) {
                        itemsWrapper.css("top", 0);
                        currentItem = 1;
                    }
                });

            }, 2000);

        });
        $(".top-links-icon").off("click").on("click", function(e){
            if($(this).parent().children("ul.links").hasClass("show")) {
                $(this).parent().children("ul.links").removeClass("show");
            } else {
                $(this).parent().children("ul.links").addClass("show");
            }
            e.stopPropagation();
        });
        $(".top-links-icon").parent().click(function(e){
            e.stopPropagation();
        });
        $(".search-toggle-icon").click(function(e){
            if($(this).parent().children(".block-search").hasClass("show")) {
                $(this).parent().children(".block-search").removeClass("show");
            } else {
                $(this).parent().children(".block-search").addClass("show");
            }
            e.stopPropagation();
        });
        $(".search-toggle-icon").parent().click(function(e){
            e.stopPropagation();
        });
        $("html,body").click(function(){
            $(".search-toggle-icon").parent().children(".block-search").removeClass("show");
            $(".top-links-icon").parent().children("ul.links").removeClass("show");
        });

        /********************* Qty Holder **************************/
        $(document).on("click", ".qtyplus", function(e) {
            // Stop acting like a button
            e.preventDefault();
            // Get its current value
            var currentVal = parseInt($(this).parents('form').find('input[name="qty"]').val());
            // If is not undefined
            if (!isNaN(currentVal)) {
                // Increment
                $(this).parents('form').find('input[name="qty"]').val(currentVal + 1);
            } else {
                // Otherwise put a 0 there
                $(this).parents('form').find('input[name="qty"]').val(0);
            }
        });
        // This button will decrement the value till 0
        $(document).on("click", ".qtyminus", function(e) {
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            fieldName = $(this).attr('field');
            // Get its current value
            var currentVal = parseInt($(this).parents('form').find('input[name="qty"]').val());
            // If it isn't undefined or its greater than 0
            if (!isNaN(currentVal) && currentVal > 0) {
                // Decrement one
                $(this).parents('form').find('input[name="qty"]').val(currentVal - 1);
            } else {
                // Otherwise put a 0 there
                $(this).parents('form').find('input[name="qty"]').val(0);
            }
        });
        $(".qty-inc").unbind('click').click(function(){
            if($(this).parents('.field.qty').find("input.input-text.qty").is(':enabled')){
                $(this).parents('.field.qty').find("input.input-text.qty").val((+$(this).parents('.field.qty').find("input.input-text.qty").val() + 1) || 0);
                $(this).parents('.field.qty').find("input.input-text.qty").trigger('change');
                $(this).focus();
            }
        });
        $(".qty-dec").unbind('click').click(function(){
            if($(this).parents('.field.qty').find("input.input-text.qty").is(':enabled')){
                $(this).parents('.field.qty').find("input.input-text.qty").val(($(this).parents('.field.qty').find("input.input-text.qty").val() - 1 > 0) ? ($(this).parents('.field.qty').find("input.input-text.qty").val() - 1) : 0);
                $(this).parents('.field.qty').find("input.input-text.qty").trigger('change');
                $(this).focus();
            }
        });

        /********** Fullscreen Slider ************/
        var s_width = $(window).innerWidth();
        var s_height = $(window).innerHeight();
        var s_ratio = s_width/s_height;
        var v_width=320;
        var v_height=240;
        var v_ratio = v_width/v_height;
        $(".full-screen-slider div.item").css("position","relative");
        $(".full-screen-slider div.item").css("overflow","hidden");
        $(".full-screen-slider div.item").width(s_width);
        $(".full-screen-slider div.item").height(s_height);
        $(".full-screen-slider div.item > video").css("position","absolute");
        $(".full-screen-slider div.item > video").bind("loadedmetadata",function(){
            v_width = this.videoWidth;
            v_height = this.videoHeight;
            v_ratio = v_width/v_height;
            if(s_ratio>=v_ratio){
                $(this).width(s_width);
                $(this).height("");
                $(this).css("left","0px");
                $(this).css("top",(s_height-s_width/v_width*v_height)/2+"px");
            }else{
                $(this).width("");
                $(this).height(s_height);
                $(this).css("left",(s_width-s_height/v_height*v_width)/2+"px");
                $(this).css("top","0px");
            }
            $(this).get(0).play();
        });
        if($(".page-header").hasClass("type10")) {
            if(s_width >= 992){
                $(".navigation").addClass("side-megamenu")
            } else {
                $(".navigation").removeClass("side-megamenu")
            }
        }

        $(window).resize(function(){
            s_width = $(window).innerWidth();
            s_height = $(window).innerHeight();
            s_ratio = s_width/s_height;
            $(".full-screen-slider div.item").width(s_width);
            $(".full-screen-slider div.item").height(s_height);
            $(".full-screen-slider div.item > video").each(function(){
                if(s_ratio>=v_ratio){
                    $(this).width(s_width);
                    $(this).height("");
                    $(this).css("left","0px");
                    $(this).css("top",(s_height-s_width/v_width*v_height)/2+"px");
                }else{
                    $(this).width("");
                    $(this).height(s_height);
                    $(this).css("left",(s_width-s_height/v_height*v_width)/2+"px");
                    $(this).css("top","0px");
                }
            });
            if($(".page-header").hasClass("type10")) {
                if(s_width >= 992){
                    $(".navigation").addClass("side-megamenu")
                } else {
                    $(".navigation").removeClass("side-megamenu")
                }
            }
        });
        var breadcrumb_pos_top = 0;
        $(window).scroll(function(){
            if(!$("body").hasClass("cms-index-index")){
                var side_header_height = $(".page-header.type10, .page-header.type22").innerHeight();
                var window_height = $(window).height();
                if(side_header_height-window_height<$(window).scrollTop()){
                    if(!$(".page-header.type10, .page-header.type22").hasClass("fixed-bottom"))
                        $(".page-header.type10, .page-header.type22").addClass("fixed-bottom");
                }
                if(side_header_height-window_height>=$(window).scrollTop()){
                    if($(".page-header.type10, .page-header.type22").hasClass("fixed-bottom"))
                        $(".page-header.type10, .page-header.type22").removeClass("fixed-bottom");
                }
            }
            if($("body.side-header .page-wrapper > .breadcrumbs").length){
                if(!$("body.side-header .page-wrapper > .breadcrumbs").hasClass("fixed-position")){
                    breadcrumb_pos_top = $("body.side-header .page-wrapper > .breadcrumbs").offset().top;
                    if($("body.side-header .page-wrapper > .breadcrumbs").offset().top<$(window).scrollTop()){
                        $("body.side-header .page-wrapper > .breadcrumbs").addClass("fixed-position");
                    }
                }else{
                    if($(window).scrollTop()<=1){
                        $("body.side-header .page-wrapper > .breadcrumbs").removeClass("fixed-position");
                    }
                }
            }
        });
    });
});
require([
    'jquery',
    'js/jquery.lazyload'
], function ($) {
    $(document).ready(function(){
        $("img.porto-lazyload:not(.porto-lazyload-loaded)").lazyload({effect:"fadeIn"});
        if ($('.porto-lazyload:not(.porto-lazyload-loaded)').closest('.owl-carousel').length) {
            $('.porto-lazyload:not(.porto-lazyload-loaded)').closest('.owl-carousel').on('changed.owl.carousel', function() {
                $(this).find('.porto-lazyload:not(.porto-lazyload-loaded)').trigger('appear');
            });
            $('.porto-lazyload:not(.porto-lazyload-loaded)').closest('.owl-carousel').on('initialized.owl.carousel', function() {
                $(this).find('.porto-lazyload:not(.porto-lazyload-loaded)').trigger('appear');
            });
        }
        window.setTimeout(function(){
            $('.sidebar-filterproducts').find('.porto-lazyload:not(.porto-lazyload-loaded)').trigger('appear');
        },500);
    });
});

// Affiliate Registration

require(['jquery'], function($){
  jQuery(document).ready( function() {
    jQuery("#close-message").click(function() {
      jQuery(".message-container").fadeOut("slow");
    });


  });
});



// Notification firebase

require([
    'jquery',
    'jquery-ui-modules/tabs',
    'jquery-ui-modules/accordion'
],
    function($){
    if($("#tabs")) {
        $("#tabs").tabs();
    }
    $("#cookie-accordion").accordion();

    $('.clsbtnyes').on('click',function(){
        $.cookie("popupShowbl", "disable");
    });

    $('.clsbtnno').on('click',function(){
        $.cookie("popupShowbl", "disable");
    });
});


require([
    'jquery','mage/url','Magento_Ui/js/modal/modal'
], function($, url, modal){
    $(document).ready(function() {
        $("#get_started_refer").on("click", function() {
            $("#form_referbox").show();
            $("#clsreferleft").hide();
            $("#result_referbox").hide();
            return false;
        });

        var priceMatchoptions = {
            type: 'popup',
            responsive: true,
            title:  $.mage.__('Price match ticket'),
            innerScroll: true,
            modalClass:'priceMatchModal',
            buttons: []
        };

        if ($('#priceMatchModal').length) {
            var popup = modal(priceMatchoptions, $('#priceMatchModal'));
        }

        $("#price-match-create-ticket-bth").on('click',function(){
            $('.form-container-match').show();
            $(".ticket-created-success").hide();
            $("#priceMatchModal").modal("openModal");
        });
        
        $(document).on("click", "#price-match-btn_submit", function(){


            var dataForm = $(this).closest('form');
            var buttonRef = this;
            if(dataForm.validation('isValid')) {
                $.ajax({
                    url: dataForm.attr('action'),
                    type: dataForm.attr('method'),
                    data: dataForm.serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (response) {
                        $(buttonRef).parents('.form-container-match').hide();
                        $(".ticket-created-success").find(".response-container").html(response.message);
                        $(".ticket-created-success").show();
                    },
                    error: function () {
                        alert('error');
                    }
                });
            }
            event.stopImmediatePropagation();
            return false;
        });
    });
});


// Logo phtml file

require([
    'jquery',
], function ($)
{
    $(document).ready(function(){
        // Accordion
        $('.acc__title').click(function(e) {
            let $this = $(this);
            if ($this.next().hasClass('show')) {
                $this.next().removeClass('show');
                $this.removeClass('active');
                $this.next().slideUp(350);
            } else {
                $this.parent().parent().find('.acc__card .acc__title').removeClass('active');
                $this.parent().parent().find('.acc__card .acc__panel').removeClass('show');
                $this.parent().parent().find('.acc__card .acc__panel').slideUp(350);
                $this.next().toggleClass('show');
                $this.toggleClass('active');
                $this.next().slideToggle(350);
            }
            e.preventDefault();
        });

        $("#get_started_refer").on("click", function(){
          $("#form_referbox").show();
          $("#clsreferleft").hide();
          $("#result_referbox").hide();
          return false;
        });
        $("#btn_register").on("click", function(){
          $("#result_referbox").show();
          $("#form_referbox").hide();
          $("#clsreferleft").hide();
          return false;
        });
        $('.footer .block .block-title').click( function() {
        var trig = $(this);
        if ( trig.hasClass('active') ) {
          trig.next('.footer .block .block-content').slideToggle('slow');
          trig.removeClass('active');
        } else {
          $('.active').next('.footer .block .block-content').slideToggle('slow');
          $('.active').removeClass('active');
          trig.next('.footer .block .block-content').slideToggle('slow');
          trig.addClass('active');
        };
      return false;
      });

         $(document).on('click', '.clslogin', function() {
          $(this).toggleClass("cls_hide");
          var input_pass = $("#pass");
          input_pass.attr('type') === 'password' ? input_pass.attr('type','text') : input_pass.attr('type','password')
        });

        $(document).on('click', '.clspassowrd', function() {
          $(this).toggleClass("cls_hide");
          var input_password = $("#password");
          input_password.attr('type') === 'password' ? input_password.attr('type','text') : input_password.attr('type','password')
        });

        $(document).on('click', '.clsconfirmpassword', function() {
          $(this).toggleClass("cls_hide");
          var input_password_confirmation = $("#password-confirmation");
          input_password_confirmation.attr('type') === 'password' ? input_password_confirmation.attr('type','text') : input_password_confirmation.attr('type','password')
        });

        $(document).on('click', '.clscurrentpassword', function() {
          $(this).toggleClass("cls_hide");
          var input_clscurrentpassword = $("#current-password");
          input_clscurrentpassword.attr('type') === 'password' ? input_clscurrentpassword.attr('type','text') : input_clscurrentpassword.attr('type','password')
        });

        $(document).on('click', '.clsnewpassword', function() {
          $(this).toggleClass("cls_hide");
          var input_clsnewpassword = $(".new-password");
          input_clsnewpassword.attr('type') === 'password' ? input_clsnewpassword.attr('type','text') : input_clsnewpassword.attr('type','password')
        });

        $(document).on('click', '.clsnewconformpassword', function() {
          $(this).toggleClass("cls_hide");
          var input_clsnewconformpassword = $(".password_confirmation");
          input_clsnewconformpassword.attr('type') === 'password' ? input_clsnewconformpassword.attr('type','text') : input_clsnewconformpassword.attr('type','password')
        });
    });

  jQuery(document).on('click','#switcher-website-trigger_old',function(){
    jQuery('#switcher-website-trigger_old + .switcher-dropdown').toggle();
  });


});


require([ 'jquery'], function($){ $(document).ready(function($) {
   $('.account.sales-order-view .column.main #authenticationPopup').next().addClass('delivery_boy');
}); });


// START CODE FOR BRAND SEARCH JQUERY

require(['jquery'],function($){
      $(document).ready(function(){
        $('#txt_serach_Brand').on('input', function() {
          //alert('hello');
            var searchTerm = $.trim(this.value);
                searchTerm = searchTerm.toLowerCase();
            //alert(searchTerm);
            $('.cls_filter_data').each(function() {
                if (searchTerm.length < 1) {
                  $(this).show();
                } else {
                  $(this).toggle($(this).filter('[data-label*="' + searchTerm + '"]').length > 0);
                }
            });
        });
      });
  });


// Order history

require([
       'jquery',
       'Magento_Ui/js/modal/modal'
       ],
       function($,modal){
            var options = {
                type: 'popup',
                responsive: true,
                title:  $.mage.__('Return/Cacnel Order Request Ticket'),
                innerScroll: true,
                modalClass:'detail-rc-modal',
                buttons: [{
                    text: $.mage.__('Return/Cacnel Order Request Ticket'),
                    class: 'ticketmodal1',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };

          $(document).ready(function() {

              $(".order-status-popup").click(function(){
                var attrId = $(this).attr("order-content-id");
                $("#order-status-model-"+ attrId).fadeIn(200);
              });
              $(".order-status-modal .close").click(function(){
                var attrId = $(this).attr("order-content-id");
                $("#order-status-model-"+ attrId).fadeOut(200);
              });

          });


          $(".order-cancel-disabled").click(function(){
              $("#orddercancel_item").hide();
              var ordId =  $(this).data("id");
              var ordIncId =  $(this).data("incid");
              var popup = modal(options, $('#order-return-ticket-modal'));
              $("#orddercancel_brand").val(ordIncId);
              $("#orddercancelreturn_requesttype").val(1);
              $("#orddercancel_order_id").val(ordId);
              $("#orddercancel_keyword").val( $.mage.__('Order Cancel request'));

              var itemSkus =  $(this).data("itemskus");
              var allitemSkus = '';
              $('#orddercancel_item').empty().append('<option selected="selected" value="">Select Item</option>');
               for (var i = 0; i < itemSkus.length; i++) {
                   var sku = itemSkus[i].sku;
                   allitemSkus = allitemSkus+','+sku;
               }
              $("#orddercancel_style").val(allitemSkus);
              $("#orddercancel_itemskus").val(allitemSkus);

              $("#orddercancel_tickettype").val(3);

              $("#order-return-ticket-modal").modal("openModal");
              $("#ordercancelreturn_reason").html($.mage.__('Order is not eligible for Cancel. Please Create a Support Ticket for further assistance.'))

          });

          /*$('#orddercancel_btn_submit').click(function(){
              alert("orddercancel_btn_submit click");
              var Url = '<?php echo $this->getUrl('ordercancelreturnticket/ajax/create'); ?>';
              var dataForm = $('#ordercancel-popup-form');
              if(dataForm.validation('isValid')){
                  $.ajax({
                    url: Url,
                    type: dataForm.attr('method'),
                    data: dataForm.serialize(),
                    dataType: 'json',
                    async: true,
                    beforeSend: function() {
                        $('#loader-message').show();
                        //console.log(dataForm.serialize());
                    },
                    complete: function(){
                        $('#loader-message').hide();
                    },
                    success: function (response) {
                        if(response.errors == false) {
                            $('#ticketresult-message').html(response.message);
                            dataForm[0].reset();
                        } else {
                            $('#ticketresult-message').html(response.message);
                            dataForm[0].reset();
                        }
                    },
                    error: function (response) {
                        console.log(JSON.parse(response));
                    },
                });

              }
          })*/

           $(".orderitem-return-disabled").click(function(){

              $("#orddercancel_item").show();
              var ordId =  $(this).data("id");
              var ordIncId =  $(this).data("incid");
              var itemSkus =  $(this).data("itemskus");
              $('#orddercancel_item').empty().append('<option selected="selected" value="">Select Item</option>');
               for (var i = 0; i < itemSkus.length; i++) {
                   var sku = itemSkus[i].sku;
                   var name = itemSkus[i].name;
                   $("#orddercancel_item").append(new Option(name, sku));
               }
              var popup = modal(options, $('#order-return-ticket-modal'));
              $("#orddercancel_brand").val(ordIncId);
              $("#orddercancel_style").val('');
              $("#orddercancelreturn_requesttype").val(2);
              $("#orddercancel_tickettype").val(2);
              $("#orddercancel_itemskus").val('');
              $("#orddercancel_keyword").val( $.mage.__('Item Return request'));
              $("#order-return-ticket-modal").modal("openModal");
              $("#orddercancel_order_id").val(ordId);
              $("#ordercancelreturn_reason").html($.mage.__('Item in the Order is not eligible for return. Please Create a Support Ticket for further assistance.'))

          });

          $("#orddercancel_item").change(function(){
              var item_style =  $(this).val(); // SKU
              $("#orddercancel_style").val(item_style);
          })

});


//Size phtml

require(
[
    'jquery',
    'Magento_Ui/js/modal/modal'
],
        function($, modal) {
            $(document).ready(function(){
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                content: 'gallery.phtml',
                buttons: [{
                    text: $.mage.__('Continue'),
                    class: '',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };
            if ($("#popup-modal").length != 0) {
                var popup = modal(options, $('#popup-mpdal'));
                $("#click-me").on('click',function(){
                    $("#popup-mpdal").modal("openModal");
                });
            }
        });

        }
);


//Recommendation recent


require([
      'jquery',
      'owl.carousel/owl.carousel.min'
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
      $(".recently-items-carousel ol.product-items").addClass("owl-carousel");
      $(".recently-items-carousel .owl-carousel").owlCarousel({
        margin: 0,
        nav: false,
        navText: ["<span class='icon-icon-left'></span>","<span class='icon-icon-right'></span>"],
        dots: false,
        autoplay:true,
        autoplayTimeout:2000,
        autoplayHoverPause:true,
        loop:true,
        responsive: {
          0: {
            items:2
          },
          768: {
            items:2
          },
          992: {
            items:3
          },
          1200: {
            items:4
          }
        }
       });
      $(".products-recommandation .owl-carousel").owlCarousel({
        margin: 0,
        navText: ["<span class='icon-icon-left'></span>","<span class='icon-icon-right'></span>"],
        nav: true,
        dots: false,
        autoplay:true,
        autoplayTimeout:2000,
        autoplayHoverPause:true,
        loop:true,
        responsive: {
          0: {
            items:2
          },
          768: {
            items:2
          },
          1024: {
            items: 3
          },
          1200: {
            items: 4
          }
        }
      });

      $(".best_seller_brand .owl-carousel").owlCarousel({
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            loop: true,
            navRewind: true,
            margin: 10,
            nav: false,
            navText: ["<span class='icon-icon-left'></span>","<span class='icon-icon-right'></span>"],
            dots: false,
            lazyLoad: true,
            itemElement: 'UL', //DEVTASK-21844 : change item element tag using ul instead div, since childs using li
            responsive:{
                0:{
                    items:2,
                },
                767:{
                    items:2,
                },
                992:{
                    items:4,
                },
                1200:{
                    items:4,

                }
            }
      });

      $('.home_most_popular_products').owlCarousel({
        loop: true,
        nav: true,
        navText: ["<span class='icon-icon-left'></span>","<span class='icon-icon-right'></span>"],
        dots: false,
        margin: 15,
        autoplay: false,
        responsive:{
            0:{
                items:2,
            },
            767:{
                items:3,
            },
            992:{
                items:3,
            },
            1200:{
                items:4,

            }
        }
    });

});


//Main custom block2

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

// Code sidebar

require(['jquery'],function($){
  $(".category_new").on('click',function(){
    $(this).toggleClass("category_open");
 $(".o-list").toggleClass("active");

});
var searchCont = jQuery('#layer-product-list div div').html();
if($.trim(searchCont) == 'Your search returned no results.')
{
    $('.c-sidebar.c-sidebar--categories').hide();
}
});


/* menu close button */

require([ 'jquery'], function($){
    $(document).ready(function($) {
        $(".close-menu-btn").on("click", function(){
            var html = $('html');
            if (html.hasClass('nav-open')) {
                html.removeClass('nav-open');
                setTimeout(function () {
                    html.removeClass('nav-before-open');
                }, 3000);
            }
        });
        $(".subchildmenu li.ui-menu-item.parent > .open-children-toggle").off("click").on("click", function(){
            if(!$(this).parent().children(".subchildmenu").hasClass("opened")) {
                $(this).parent().children(".subchildmenu").addClass("opened");
                $(this).parent().children("a").addClass("ui-state-active");
            }
            else {
                $(this).parent().children(".subchildmenu").removeClass("opened");
                $(this).parent().children("a").removeClass("ui-state-active");
            }
        });
    });
});




