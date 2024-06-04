var config = {
    map: {
        '*': {
            luxuryUnlimitedGtmDatalayer: 'LuxuryUnlimited_GoogleAnalytics/js/datalayer'
        }
    },
    shim: {
        'LuxuryUnlimited_GoogleAnalytics/js/datalayer': ['Magento_Customer/js/customer-data']
    }
};
