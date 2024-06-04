require([
    'jquery',
], function ($)
{
    $(document).ready(function(){

    	/* START SEARCH BOX */
    	//header .block-search .actions button
    	//.minisearch #search
    	$(".clsbtnsearch").on("click",function() {
    		//alert("click");
			$(".minisearch #search").toggleClass("showsearch");
			$("header.page-header").toggleClass("search-open");
		});
    	/* END SEARCH BOX */
    	$(".accordion_head").click(function() {
    		if ($('.accordion_body').is(':visible')) {
			  $(".accordion_body").slideUp(300);
			  $(".plusminus").text('+');
			}
			if ($(this).next(".accordion_body").is(':visible')) {
			  $(this).next(".accordion_body").slideUp(300);
			  $(this).find(".panel-title").children(".plusminus").text('+');
			} else {
			  $(this).next(".accordion_body").slideDown(300);
			  $(this).find(".panel-title").children(".plusminus").text('-');
			}
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
    });
});

require([
	    'jquery',
	], function($){
		$('.page-footer .mobile-toggle .no-padding-mob').click( function() {
			var trig = $(this);
			if ( trig.hasClass('active') ) {
				trig.next('.page-footer .mobile-toggle .mobiletoggledata').slideToggle('slow');
				trig.removeClass('active');
			} else {
				$('.active').next('.page-footer .mobile-toggle .mobiletoggledata').slideToggle('slow');
				$('.active').removeClass('active');
				trig.next('.page-footer .mobile-toggle .mobiletoggledata').slideToggle('slow');
				trig.addClass('active');
			};
		return false;
		});
	});

	function closePopupPriceMatch()
	{
		var modal = document.getElementById("myModal");
		modal.style.display = "none";
	}
require([
    'jquery',
], function ($)
{
	function validateEmail(emailField){
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		if (reg.test(emailField.value) == false)
		{
		    return false;
		}
		return true;
	}
    $(document).ready(function(){
    	var modal = document.getElementById("myModal");
    	var myModalPriceSuccess = document.getElementById("myModalPriceSuccess");
		var btn = document.getElementById("myBtn");
		var span = document.getElementsByClassName("close")[0];
		var pricesucessClose = document.getElementById("pricesucessClose");

		if (pricesucessClose != null) {
			pricesucessClose.onclick = function() {
				myModalPriceSuccess.style.display = "none";
			}
		}
		if (btn != null) {
			btn.onclick = function() {
			  modal.style.display = "block";
			}
		}
		if (span != null) {
			span.onclick = function() {
			  modal.style.display = "none";
			}
		}
		window.onclick = function(event) {
		  if (event.target == modal) {
		    modal.style.display = "none";
		  }
		}

		$("#btn_register").click(function(){
			var customurl = "https://magento-491806-1552731.cloudwaysapps.com/pricematch.php";
			var price = $("input[name='price']:checked").val();
			var notification = $("input[name='notification']:checked").val();
			var yourfullname = $("#yourfullname").val();
			var mobile_number = $("#mobile_number").val();
			var youremailaddress = $("#youremailaddress").val();
			var websitename = $("#websitename").val();
			var urlofproduct = $("#urlofproduct").val();
			$(".clsmage-error").hide();
			if(yourfullname == "")
			{
				$("#yourfullname-error").show();
				return false;
			}
			else if(mobile_number == "")
			{
				$("#mobile_number-error").show();
				return false;
			}
			else if(youremailaddress == "")
			{
				$("#youremailaddress-error").show();
				return false;
			}
			else if(websitename == "")
			{
				$("#websitename-error").show();
				return false;
			}
			else if(urlofproduct == "")
			{
				$("#urlofproduct-error").show();
				return false;
			}
	        $.ajax({
	            url: customurl,
	            type: 'POST',
	            dataType: 'json',
	            data: {
	            	price: price,
	            	notification : notification,
	                yourfullname: yourfullname,
	                mobile_number: mobile_number,
	                youremailaddress: youremailaddress,
	                websitename: websitename,
	                urlofproduct: urlofproduct
	            },
		        complete: function(response) {
		            console.log("response:::::"+response.responseJSON.status);
		            modal.style.display = "none";
	            	myModalPriceSuccess.style.display = "block";
	            	$("#msgpricebox").html(response.responseJSON.msg);
		        },
		        error: function (xhr, status, errorThrown) {
		            console.log('Error happens. Try again.');
		        }
	        });
		});
    });
}); 
require([
    'jquery',
], function ($)
{
	jQuery('li.quickcart-product-item .product-item-details .product.actions a.action.delete').on('click', function(){
		setTimeout(function(){
			jQuery(function(){
			var text = jQuery('.modal-popup.confirm.remove-item .modal-content div').text();
			var text = text.replace(/^\s+|\s+$/gm,'');
				text = text.replace('remove','remove<br>');
				text = text.replace('item','<br>item');
				
			jQuery('.modal-popup.confirm.remove-item .modal-content div').html(text.toLowerCase());
			
			});
		}, 400);
	});
	jQuery('.modal-popup.confirm.remove-item .modal-inner-wrap .modal-footer button').on('click', function(){
		setTimeout(function(){
			jQuery(function(){
			var text = jQuery('.modal-popup.confirm.remove-item .modal-content div').text();
			var text = text.replace(/^\s+|\s+$/gm,'');
				text = text.replace('has been removed','<br>has been removed');
				
			jQuery('.modal-popup.confirm.remove-item .modal-content div').html(text.toLowerCase());
			
			});
		}, 400);
	});

	
	if($('#mini-cart li').length == 0){
		$('.header .quickcart-wrapper .block-quickcart').addClass('cart-is-empty');
	}else{
		$('.header .quickcart-wrapper .block-quickcart').removeClass('cart-is-empty');
	}
	$('.minicart-wrapper a.action.showcart').on('click', function(){
		if($('#mini-cart li').length > 0){
			$('.header .quickcart-wrapper .block-quickcart').removeClass('cart-is-empty');
		}else{
			$('.header .quickcart-wrapper .block-quickcart').addClass('cart-is-empty');
		}
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
      var span = modal.getElementsByClassName("close")[0];
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

	$('#btn_ticket').click(function (e) {
		e.preventDefault();
		var dataForm = $('#' + $(this).closest('form').attr('id'));
		if (dataForm.validation('isValid')) {
			$("#result-message").text('');
			$('#myModalspec').css({"display": "none"});
			$.ajax({
				url: dataForm.attr('action'),
				type: dataForm.attr('method'),
				data: dataForm.serialize(),
				dataType: 'json',
				beforeSend: function () {
					$('#loader-message').show();
				},
				complete: function () {
					$('#loader-message').hide();
				},
				success: function (response) {
					if (response.errors === false) {
						$('#result-message').html(response.message);
						$('.clsmsgsuccessbox').addClass('success');
						$('.cls_popupspecialrequest_form').addClass('success_hide');
						$('.clsmsgsuccessbox').parents('.modal-inner-wrap').find('.modal-title').text('Thank you for your request!');						
					} else {
						$('#result-message').html(response.message);
						$('.clsmsgsuccessbox').addClass('error-msg');
							
						dataForm[0].reset();
					}
				},
				error: function (response) {
					console.log(JSON.parse(response));
				}
			});
		}
	});
});

require(['jquery', 'slickslider', 'domReady!'], function ($, slick, dom) {
	/**
	 * RTL and LTR Slick true/false
	 */
	function rtl_slick(){
		if ($('body').hasClass("rtl")) {
		   return true;
		} else {
		   return false;
		}
	}

	// Slick Slider
    $(document).ready(function($){
        $('.message-slider').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: true,
          dots: false,
          speed: 300,
          autoplaySpeed: 5000,
          autoplay: true,
          infinite: true,
		  rtl: rtl_slick()
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