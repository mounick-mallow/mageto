require([
    'jquery',
    'mage/translate',
    'Magento_Ui/js/modal/confirm',
    'mage/template'
], function ($, $t, confirm, mageTemplate) {
        $('.action-delete').click(function (e) {
            e.stopPropagation();
            const productName =  $(this).attr('data-name');
            const imageUrl = $(this).attr('data-image');

            let confirmContent = $t("Are you sure you would like to remove this item from the shopping cart?");
            confirmContent += "<div style='margin: 15px 0 ; display: flex; justify-content: space-around; align-items: center'>";
            confirmContent += "<img src=" + imageUrl + " />";
            confirmContent += productName;
            confirmContent += "</div>";

            confirm({
                title: $t("Remove item cart"),
                content: confirmContent,
                modalClass: "cart-item-remove",
                actions: {
                    confirm: function () {
                        var params = $(e.currentTarget).data('post');
                        var formTemplate = '<form action="<%- data.action %>" method="post">'
                            + '<% _.each(data.data, function(value, index) { %>'
                            + '<input name="<%- index %>" value="<%- value %>">'
                            + '<% }) %></form>';

                        var formKeyInputSelector = 'input[name="form_key"]';

                        var formKey = $(formKeyInputSelector).val();
                        if (formKey) {
                            params.data.form_key = formKey;
                        }
                        $(mageTemplate(formTemplate, {
                            data: params
                        })).appendTo('body').hide().submit();
                    }
                }
            });
        })
});
