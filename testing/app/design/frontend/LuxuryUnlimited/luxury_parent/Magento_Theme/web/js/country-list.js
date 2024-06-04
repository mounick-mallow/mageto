define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
        $(".country-switcher").click(function (event) {
            var $el = $(this);
            var storeLanguageWrapperId = $('#website_languageselector_' + $el.data('websiteid'))
            var languageCount = $(storeLanguageWrapperId).data('langcount');
            if (languageCount > 1) {
                var options = {
                    type: 'popup',
                    modalClass: 'country-popup-modal-wraper',
                    title: $.mage.__('Select Language'),
                    responsive: true,
                    innerScroll: true,
                    buttons: [{
                        text: $.mage.__('Close'),
                        class: 'chooseLanguageModal1',
                        click: function () {
                            this.closeModal();
                        }
                    }],
                    opened: function ($Event) {
                        $(".modal-footer").hide();
                    }
                };

                $('#countrypopup-modal').html('');
                $('#countrypopup-modal').html($('#website_languageselector_' + $el.data('websiteid')).html());
                var popup = modal(options, $('#countrypopup-modal'));
                $("#countrypopup-modal").modal("openModal");
                event.preventDefault();
            }
        });
    });
