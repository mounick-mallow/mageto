define([
    'Magento_Customer/js/customer-data',
    'jquery',
    'underscore',
    'mage/cookies'
], function (customerData, $, _) {
    'use strict';

    return function (config) {
        window.dataLayer = window.dataLayer || [];
        var events = config.listEvent;
        if (events.includes("page_view")) {
            triggerPageView();
        }
        
    }

    function triggerPageView()
    {
        dataLayer.push({
            event: "page_view",
            page_title: document.title,
            page_location: document.URL
        });
    }

});
