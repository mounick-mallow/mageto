define([
    'jquery',
    'mage/calendar',
], function ($) {
    return function () {
        $("#skypem").calendar({
            showsTime: false,
            controlType: 'select',
            timeFormat: null,
            dateFormat: 'mm/dd/yy',
            changeYear: true,
            changeMonth: true,
            yearRange: '1900:2100',
            onSelect: function(dateText) {
                $("#skype").val(dateText);
            }
        });
    } 
});
