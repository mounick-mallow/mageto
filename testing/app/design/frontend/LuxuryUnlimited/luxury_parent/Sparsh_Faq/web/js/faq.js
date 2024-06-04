define([
    'jquery',
    'mage/url',
    'domReady!'
], function ($, urlBuilder) {

    $(".sparsh-block-content").appendTo(".sidebar.sidebar-additional");

    $(".sparsh-block-item").click(function ()  {
        let id = jQuery(this).attr('id');
        let link = 'faq/index/faqcategory/id/' + id;
        let url = urlBuilder.build(link);
        sparshLoadFaq(url);
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
    });

    function sparshLoadFaq(url) {
        var data = '';
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            showLoader: true,
            success: function (data) {
                $('.sparsh-question-answer').html(data.output);
                return true;
            },
            error: function (data) {}
        });
    }
});
