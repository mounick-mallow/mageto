require([
    'jquery'
], function ($) {
    $(document).ready(function(){
        $(".drop-menu > a").off("click").on("click", function(){
            if($(this).parent().children(".nav-sections").hasClass("visible")) {
                $(this).parent().children(".nav-sections").removeClass("visible");
                $(this).removeClass("active");
            }
            else {
                $(this).parent().children(".nav-sections").addClass("visible");
                $(this).addClass("active");
            }
        });
    });
    });
    
    if (document.readyState == 'complete') {
    highlighter();
} else {
    document.onreadystatechange = function () {
        if (document.readyState === "complete") {
            highlighter();
        }
    }
}
function highlighter() {
    setTimeout(function() {
        window.__lc = window.__lc || {};
        window.__lc.license = 11434003;
        window.__lc.chat_between_groups = false;
        (function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can't use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};!n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
    }, 2000);
}

 require(['jquery', ], function($) {

    var searchbtnText = 'Highlight'
    var inputId = 'faqs__search_text_area'
    var btnId = 'faqs__search_text_btn'

    $(document).ready(function() {

        var items = [];
        var searchInput = '<div class="faqs__search_box" ><input type="text" id="' + inputId + '"/>' +
            '<button id="' + btnId + '">' +
            searchbtnText +
            '</button></div>';

        $('.cms-faqs .cls_shipping_panelmain').prepend(searchInput);

        $('#' + inputId).on('keydown', function(e) {
            if (e.key == 'Enter') {
                $('#' + btnId).click()
            }
        });

        $('#' + btnId).on("click", function() {
            var searched = $('#' + inputId).val().trim();

            if (searched !== "") {
                var searchSelector = $('.cls_shipping_panelmain .accordion_body .md-paragraph, .cls_shipping_panelmain h4');

                searchSelector.each(function(element, index) {
                    // delete the old marks
                    $(this).find('mark').contents().unwrap();

                    var text = $(this).html();

                    var re = new RegExp(searched, "gi"); // search for all instances

                    var newText = $(this).text().replace(re, ($1) => '<mark>' + $1 + '</mark>');

                    $(this).html(newText);
                })

                var firstMark = searchSelector.find('mark').first()

                if (firstMark.length > 0) {
                    var parentOfFirstMark = firstMark.closest('.cls_shipping_panelsub').children();

                    if ($(parentOfFirstMark[1]).css("display") == "none") {
                        parentOfFirstMark[0].click()
                    }

                    $('html, body').animate({
                        scrollTop: firstMark.offset().top
                    }, 500)
                }
            }
        })

        $(".clsbtnsearch").on("click", function() {
            $(".minisearch #search").toggleClass("showsearch");
        });

        $("#get_started_refer").on("click", function() {
            $("#form_referbox").show();
            $("#clsreferleft").hide();
            $("#result_referbox").hide();
            return false;
        });
        $("#btn_register").on("click", function() {
            $("#result_referbox").show();
            $("#form_referbox").hide();
            $("#clsreferleft").hide();
            return false;
        });
    });
});

require(['jquery'], function ($) {
  $(".faqspart .faqbox .question").click(function () {
    var trig = $(this);

    if (trig.hasClass("active")) {
      trig
        .next(".faqspart .faqbox .answer")
        .slideToggle("slow");
      trig.removeClass("active");
    } else {
      $(".active")
        .next(".faqspart .faqbox .answer")
        .slideToggle("slow");
      $(".active").removeClass("active");
      trig
        .next(".faqspart .faqbox .answer")
        .slideToggle("slow");
      trig.addClass("active");
    }
    return false;
  });
});

require(['jquery'], function($){
  $(document).ready(function(){
    $(".block-category-list .block-content").hide();
    $(".block-category-list .block-title").click(function(){
      $(".block-category-list .block-content").slideToggle(1000);
      $(this).toggleClass("open");
    });

    $(".fas.fa-search").click(function () {
      $(".search-overlay").fadeIn();
    });

    $(".block.block-search .block.block-content").append('<div class="close-search">X</div>');
    $(".close-search").click(function () {
      $(".block-search").removeClass('show');
      $(".search-overlay").fadeOut();
    });

    $(function($){
      $(document).mouseup( function(e){
        let div = $( ".block-search.show" );
        if ( !div.is(e.target) && div.has(e.target).length === 0 ) {
            $(".search-overlay").fadeOut();
        }
      });
    });

    $('.firas-modal-popup .action-close').click(function() {
      alert('test');
        location.reload();
    });
    $('.checkout-cart-index .modals-overlay').click(function() {
        location.reload();
    });

    var wd = $(window).width();
    if(wd < 1025){
        if($('body').hasClass('couponlist-coupon-index')){
            $('.page-title-wrapper').appendTo('.page-main .columns');
        }
        if($('body').hasClass('tickets-customer-index')){
            $('.page-title-wrapper').appendTo('.page-main .columns');
        }
        if($('body').hasClass('orderreturn-customer-tickets')){
            $('.page-title-wrapper').appendTo('.page-main .columns');
        }
        if($('body').hasClass('customer-address-form')){
            $('.page-title-wrapper').appendTo('.page-main .columns');
        }
        if($('body').hasClass('wishlist-index-index')){
            $('.page-title-wrapper').appendTo('.page-main .columns');
        }
        if($('body').hasClass('customer-address-index')){
            $('.page-title-wrapper').appendTo('.page-main .columns');
        }
        if($('body').hasClass('sales-order-history')){
            $('.page-title-wrapper').appendTo('.page-main .columns');
        }
    }

    $('.footer-menu-toggle').click(function(){        
        if($(this).hasClass('open')){
            $(this).removeClass('open');
            $(this).closest('.block-content').find('.links').slideUp();
        } else {
            $('.footer-middle').find('.links').slideUp();
            $('.footer-middle').find('.footer-menu-toggle.open').removeClass('open');
            $(this).addClass('open');
            $(this).closest('.block-content').find('.links').slideDown();
        }
    });
  });
});

require(['jquery','mage/url'], function ($, url) {
    'use strict';

    $(document).ready(function(){
      var modalPrice = document.getElementById("myModal-price-match");
      var btnPrice = document.getElementById("myspecialricematch");
      var spanPrice = $(modalPrice).find('.close');
      var modalTittle;
      var modalDataName;
      var modal = document.getElementById("myModalspec");
      var myModalPriceSuccess = document.getElementById("myModalPriceSuccess");
      var btn = document.getElementById("myspecialreq");
      var TicketBtn = document.getElementById("create-ticket-btn");
      var span = document.getElementsByClassName("close")[0];
      var pricesucessClose = document.getElementById("pricesucessClose");

      url.setBaseUrl(BASE_URL);

      if(spanPrice) {
          spanPrice.on("click", () => {
              modalPrice.style.display = "none";
          });
      }
      if(modalPrice) {
          modalPrice.addEventListener("click", (e) => {
              if (e.target === modalPrice) {
                modalPrice.style.display = "none";
              }
          });
      }

      if(btnPrice) {
          btnPrice.onclick = function () {
            modalPrice.style.display = "block";
          };
      }

      if(pricesucessClose) {
        pricesucessClose.onclick = function() {
          myModalPriceSuccess.style.display = "none";
        };
      }
      if(btn) {
        btn.onclick = function() {
            modalTittle = $(this).attr('data-title');
            if (modalTittle) {
                $(modal).find('.clsspecialpopupheading').text(modalTittle);
            }
          modal.style.display = "block";
        };
      }
      if(TicketBtn) {
        TicketBtn.onclick = function () {
            modalTittle = $(this).attr('data-title');
            modalDataName = $(this).attr('data-page-name');
            if (modalTittle) {
                $(modal).find('.clsspecialpopupheading').text(modalTittle);
            }
            if (modalDataName) {
                $(modal).find('#brand').val(modalDataName);
            }
            modal.style.display = "block";
        };
      }
      if(span){
        span.onclick = function() {
          modal.style.display = "none";
        };
      }
      window.onclick = function(event) {
        if (event.target === modal) {
          modal.style.display = "none";
        }
      };

      $('#btn_submit').click(function(){
          var dataForm = jQuery('#'+$(this).closest('form').attr('id'));
          var link = url.build('redirectcontactus/index/success');

          if(dataForm.validation('isValid')){
              $("#result-message").text('');
              $('#myModalspec').css({"display":"none"});
              $.ajax({
                  url: dataForm.attr('action'),
                  type: dataForm.attr('method'),
                  data: dataForm.serialize(),
                  dataType: 'json',
                  async: true,
                  beforeSend: function() {
                    $('#loader-message').show();
                  },
                  complete: function(){
                    $('#loader-message').hide();
                  },
                  success: function (response) {
                      if(response.errors === false) {
                        $(location).prop('href', link);
                      } else {
                        $('#result-message').html(response.message);
                        dataForm[0].reset();
                      }
                  },
                  error: function (response) {
                      console.log(JSON.parse(response));
                  },
              });
              event.stopImmediatePropagation();
              return false;
          }
      });
    });

    // Sticky header
    $(window).scroll(function () {  
      var getHeaderHeight = $('.page-header .header.content').innerHeight(); 
      var scroll = $(window).scrollTop();
      if(scroll >= getHeaderHeight + 75) {
          $(".page-header").addClass("sticky active");
      } else {
          $(".page-header").removeClass("sticky active");
      }
    });
});