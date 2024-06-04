define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'domReady!'
], function($, modal) {
    'use strict';
    
    return function(config) {
        const outOfStockModal = $("#myModalspecsizeOutofStock");
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: $.mage.__('Notify Me When Available'),
            buttons: []
        };

        modal(options, outOfStockModal);

        $(".notifyme").click(function() {
            outOfStockModal.modal("openModal");
        });

        $(document).on('click', '#notifyme-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const loaderBlock = $('.result-loader-block');
            const dataForm = $('#simple-out-of-stock-form');

            $.ajax({
                url: dataForm.attr('action'),
                type: dataForm.attr('method'),
                data: dataForm.serialize(),
                dataType: 'json',
                async: true,
                beforeSend: function() {
                    $('body').trigger('processStart');
                },
                complete: function() {
                    $('body').trigger('processStop');
                },
                success: function (response) {
                    loaderBlock.show();
                    dataForm.hide();
                    loaderBlock.html(response.message);

                    dataForm[0].reset();
                },
                error: function (response) {
                    outOfStockModal.modal("closeModal");
                    console.log(JSON.parse(response));
                },
            });
        });
    }

});