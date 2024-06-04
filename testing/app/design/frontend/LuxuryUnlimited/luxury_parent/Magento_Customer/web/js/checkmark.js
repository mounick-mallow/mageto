define([
    "jquery",
], function ($) {
    "use strict";

    return function(config, element) {
        $(element).on('click', function () {
            let formBlock = $(this).closest('.form-block.checkbox');
            let checkMark = formBlock.find('.checkmark');
            
            if($(checkMark).hasClass('checked')){
                $(checkMark).removeClass('checked');
                $(this).removeAttr('checked');
            } else {
                $(checkMark).addClass('checked');
                $(this).attr('checked','checked');
            }
        });

    }
});