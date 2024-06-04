
define([
    'jquery', 
    'Magento_Ui/js/modal/modal'
], function($, modal) {
    'use strict';

    return function (config) {
        var attributeId = config.attributeId;
        var $list = $('#config_list_'+ attributeId);
        var $dropdown = $('#attribute'+ attributeId);
        var specialSizeAttrId = $('.special-size-attr-id').val();

        $list.on('click', 'li', function() {

            $list.removeClass('border-style-none');
            var spConfig = config.jsonConfig;
            var selectedValue = $(this).data('value');
            var selectedText = $(this).data('size');
            var stockStatus = $(this).data('stock');
            $dropdown.val(selectedValue);

            if(specialSizeAttrId == attributeId) {
                if(selectedValue == "notfound") {
                    $('#missing-size').modal('openModal');
                    $dropdown.val("");
                    selectedValue = "";
                }
            }
            
            if(stockStatus == 0) {
                $('#selected-notifyme').val(selectedText);
                $dropdown.val("");
                $('#stock-notifyme').modal('openModal');
                if($('#logged-in-notifyme').data("value")) {
                    $('#btn_notifysubmit').trigger('click');
                }
            }
            
    
            if(typeof selectedText!== "undefined"){
                $("ul.list-unstyled li.init").text(selectedText);
                $("ul.list-unstyled li").each(function(){
                    if(!$(this).hasClass("init")){
                        $(this).remove();
                    }
                });
            } else {
                var selectedClass = ' class="selected" ';
                var classAttr = selectedValue == "" ? selectedClass : '';
                var dropdownHtml = '<li class="init" data-value=""' + classAttr + '>'+ $.mage.__("Choose an Option...")+'</li>';
    
                if (spConfig.attributes.hasOwnProperty(attributeId)) {
                    $.each(spConfig.attributes[attributeId].options, function (index, option) {
                        var classAttr = selectedValue == option.id ? selectedClass : '';
                        var originalString = option.label;
                        var searchString = " - Out of stock";
    
                        if (originalString.indexOf(searchString) !== -1) {
                            var remainingData = originalString.replace(searchString, '');
                            var listItemTemplate = `
                                <li class="flex-inline" data-size="${remainingData}" data-value="${option.id}" data-stock="0" ${classAttr}>
                                    <span class="size-vale">${remainingData}</span>
                                    <span class="sold-out">` + $.mage.__('Sold Out') + `</span>
                                    <span class="notify-link">` + $.mage.__('Notify me when in stock') + `</span>
                                </li>
                            `;
                            dropdownHtml += listItemTemplate;
                        } else {
    
                            var listItemTemplate = `
                            <li data-size="${option.label}" data-value="${option.id}" ${classAttr}>
                                <span class="size-vale">${option.label}</span>
                            </li>
                            `;
                            dropdownHtml += listItemTemplate;
                        }
                    });
                }
    
                if (specialSizeAttrId == attributeId) {
                    dropdownHtml += '<li data-size="" class="missing-size-link" data-value="notfound">' + $.mage.__("Missing Size ?") + '</li>';
                }
    
    
                $list.html(dropdownHtml);
    
            }
        });

        $dropdown.on('change', function() {
            $list.toggleClass('border-style-none');
            var selectedValue = $(this).val();
            $list.find('li').removeClass('selected');
            $list.find('li[data-value="' + selectedValue + '"]').addClass('selected');
        });
    }
    
});
