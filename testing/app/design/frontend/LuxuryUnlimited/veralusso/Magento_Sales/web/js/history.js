// Order

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
          
      
          $(".order-canel-disabled").click(function(){
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



