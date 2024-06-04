define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/url',
    'mage/cookies',
    'domReady!'
], function($, modal, urlBuilder) {

    return function (config) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: $.mage.__('Please choose your shipping country'),
            modalClass: 'custom-window-block',
            buttons: [],
            closed: function () {
                $.cookie('dont-show', 'true');
            }
        };

        if (config.cookieBaseUrl === '') {
            if ($.cookie('dont-show') !==  'true') {
                var popup = modal(options, $('#cookie-popup-window'));
                $("#cookie-popup-window").modal("openModal");
            }
        }

        $(document).on('change',"[name='websites']",function(){

            if (window.hasOwnProperty('triggerActionCurrencyChange') && window.triggerActionCurrencyChange == false) {
                return;
            } 

            var customurl = urlBuilder.build('storeredirect/getstores/index');

            $.ajax({
                url: customurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    websiteCode: $(this).val(),
                },
                complete: function(response) {
                    var $select = $('#store');
                    $select.find('option').remove();
                    var storeData = response.responseJSON;
                    $.each(storeData,function(key, value)
                    {
                        if(typeof value.code !== "undefined") {
                            $select.append('<option value=' + value.code + '>' + value.name + '</option>');
                        }else if(typeof value.currency!== "undefined") {
                            $(".currency-placeholder").find(".currency").html(value.currency);
                        }
                    });
                }
            });
        });

        $(document).on('click',"[name='cancel']",function(){
            $("#cookie-popup-window").modal("closeModal");
        });

        $(document).on('click',"[name='site-switcher']",function(){
            var popup = modal(options, $('#cookie-popup-window'));
            $("#cookie-popup-window").modal("openModal");
        });

        $(document).on('click',"[name='switcher-dropdown']",function(){
            var popup = modal(options, $('#cookie-popup-window'));
            $("#cookie-popup-window").modal("openModal");
        });


        $(document).on('click',"[name='redirect']",function(){
            var url = urlBuilder.build('storeredirect/getbaseurl/index');
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    storeCode: $('#store').val(),
                },
                complete: function(response) {
                    $.cookie('dont-show', 'true');
                    window.location.href = response.responseJSON.base_url;
                }
            });
        });
    }
});
