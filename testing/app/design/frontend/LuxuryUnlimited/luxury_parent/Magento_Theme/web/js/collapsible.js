define([
    "jquery",
    "Magento_Ui/js/modal/modal"
], function($, modal)  {

    var options = {
        type: 'popup',
        responsive: true,
        title: $.mage.__('Confirm Logout'),
        modalClass: 'modal-confirm-logout',
        buttons: [
            {
                text: $.mage.__('Confirm Logout'),
                class: 'confirm-logout action primary',
                click: function () {
                    if ($(".logout.nav").length > 0) {
                        window.location.href = $("#block-collapsible-nav .logout.nav a").attr('href');
                    }
                }
            },
            {
                text: $.mage.__('Cancel'),
                class: 'action cancel-logout',
                click: function () {
                    this.closeModal();
                }
            }
        ]
    };

    var popup = modal(options, $('#modal-logout'));

    $(".logout.nav").click(function(e) {
        e.preventDefault();
        $('#modal-logout').modal('openModal');
    });
});
