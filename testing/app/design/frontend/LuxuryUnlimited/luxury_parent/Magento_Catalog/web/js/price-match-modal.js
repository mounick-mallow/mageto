define([
        "jquery", "Magento_Ui/js/modal/modal"
    ], function($) {
        var PriceMatchModal = {
            initModal: function(config, element) {
                $target = $(config.target);
                var options = {
                    type: 'popup',
                    responsive: true,
                    modalClass: 'price-promise-modal',
                    title: 'Best Price Promise',
                    buttons: []
                };
                $target.modal(options);
                $element = $(element);
                $element.click(function() {
                    $target.modal('openModal');
                });
            }
        };
        return {
            'price-match': PriceMatchModal.initModal
        };
    }
);
