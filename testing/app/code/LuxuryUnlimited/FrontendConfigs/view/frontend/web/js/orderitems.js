require(["jquery", 'Magento_Ui/js/modal/modal'],function($,modal) {
    'use strict';
    $('#orddercancel_btn_submit').click(function (event) {
        var dataForm = $(this).closest('form');
        var Url = dataForm.attr("action");
        event.preventDefault();
        if (dataForm.validation('isValid')) {
            var formData = new FormData(dataForm[0]);
            $.ajax({
                url: Url,
                type: dataForm.attr('method'),
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                async: true,
                beforeSend: function () {
                    $('#loader-message').show();
                    //console.log(dataForm.serialize());
                },
                complete: function () {
                    $('#loader-message').hide();
                },
                success: function (response) {
                    if (response.errors == false) {
                        dataForm.hide();
                        $('.ticket-created-success').find(".response-container").html(response.message);
                        $('.ticket-created-success').show();
                        //dataForm[0].reset();
                    } else {
                        dataForm.hide();
                        $('.ticket-created-success').find(".response-container").html(response.message);
                        $('.ticket-created-success').show();
                        dataForm[0].reset();
                    }
                },
                error: function (response) {
                    console.log(JSON.parse(response));
                },
            });
            event.preventDefault();
        }
    });

    $('#image_upload').on('change', function (event) {
        var imgCont = document.getElementById("preview-container");
        imgCont.innerHTML = '';
        for (let i = 0; i < event.target.files.length; i++) {
            var divElm = document.createElement('div');
            divElm.id = "rowdiv" + i;
            var spanElm = document.createElement('span');
            var image = document.createElement('img');
            image.src = URL.createObjectURL(event.target.files[i]);
            image.id = "output" + i;
            image.width = "200";
            spanElm.appendChild(image);
            var deleteImg = document.createElement('p');
            deleteImg.innerHTML = "x";
            deleteImg.dataset.name = event.target.files[i].name;
            deleteImg.onclick = function() {
                var uploadInput = document.getElementById("image_upload");
                var files = Array.from(uploadInput.files);
                var filteredFiles = files.filter(file => {
                        if (file.name == this.dataset.name) {
                    return false;
                }

                return true;
            });
                var dt = new DataTransfer();
                filteredFiles.forEach(elem => dt.items.add(elem));
                uploadInput.files = dt.files;
                this.parentNode.remove()
            };
            divElm.appendChild(spanElm);
            divElm.appendChild(deleteImg);
            imgCont.appendChild(divElm);
        }
    });

    $(".item-status-popup").click(function(){
        var attrId = $(this).attr("item-content-id");
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: false,
            modalClass: 'modal-track-order',
            title: $.mage.__('Item Status'),
            buttons: []
        };
        var orderNo = $(".page-title .base").html();
        $(".item-modal-popup").find(".order-number").html(orderNo);
        var productImage = $(this).parents("#order-item-row-"+attrId).find('td.col.image').html();
        $(".item-modal-popup").find(".product-image").html(productImage);
        var productName = $(this).parents("#order-item-row-"+attrId).find('td.col.name').find('.product-item-name').html();
        $(".item-modal-popup").find(".product-name").html(productName);
        var itemStatus =  $(this).attr("rel");
        $(".item-modal-popup").find('.item-processing').removeClass('active');
        $(".item-modal-popup").find('.item-delivered').removeClass('active');
        $(".item-modal-popup").find('.item-received').removeClass('active');
        if(itemStatus =="processing"){
            $(".item-modal-popup").find('.item-processing').addClass('active');
        }
        if(itemStatus =="delivered"){
            $(".item-modal-popup").find('.item-processing').addClass('active');
            $(".item-modal-popup").find('.item-delivered').addClass('active');
        }
        if(itemStatus =="received"){
            $(".item-modal-popup").find('.item-processing').addClass('active');
            $(".item-modal-popup").find('.item-delivered').addClass('active');
            $(".item-modal-popup").find('.item-received').addClass('active');
        }
        $(".item-modal-popup").find(".product-name").html(productName);
        var popup = modal(options, ".item-modal-popup");
        $(".item-modal-popup").modal("openModal");
    });
});