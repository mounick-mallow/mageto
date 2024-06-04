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
	function closePopupPriceMatch()
	{
		var modal = document.getElementById("myModal");
		modal.style.display = "none";
	}
    $(document).ready(function(){
    	var modal = document.getElementById("myModal");
    	var myModalPriceSuccess = document.getElementById("myModalPriceSuccess");
		var btn = document.getElementById("myBtn");
		var span = document.getElementsByClassName("close")[0];
		var pricesucessClose = document.getElementById("pricesucessClose");

		pricesucessClose.onclick = function() {
		  	myModalPriceSuccess.style.display = "none";
		}

		if (btn) {
			btn.onclick = function() {
				modal.style.display = "block";
			}
		}

		span.onclick = function() {
		  modal.style.display = "none";
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
