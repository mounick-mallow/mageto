require([
    'jquery',
    'domReady!'
], function ($) {
    $(document).ready(function () {
        var previousActiveTabIndex = 0;

        $(".tab-switcher").on('click keypress', function (event) {
            // event.which === 13 means the "Enter" key is pressed

            if ((event.type === "keypress" && event.which === 13) || event.type === "click") {

                var tabClicked = $(this).data("tab-index");

                if (tabClicked != previousActiveTabIndex) {
                    $("#allTabsContainer .tab-container").each(function () {
                        if ($(this).data("tab-index") == tabClicked) {
                            $(".tab-container").hide();
                            $(this).show();
                            previousActiveTabIndex = $(this).data("tab-index");
                            return;
                        }
                    });
                }
            }
        });
        var lastpreviousActiveTabIndex = 0;

        $(".last-tab-switcher").on('click keypress', function (event) {
            // event.which === 13 means the "Enter" key is pressed

            if ((event.type === "keypress" && event.which === 13) || event.type === "click") {

                var tabClicked = $(this).data("tab-index");

                if (tabClicked != lastpreviousActiveTabIndex) {
                    $("#lastallTabsContainer .tab-container").each(function () {
                        if ($(this).data("tab-index") == tabClicked) {
                            $(".tab-container").hide();
                            $(this).show();
                            lastpreviousActiveTabIndex = $(this).data("tab-index");
                            return;
                        }
                    });
                }
            }
        });

        $('.app-subscribe .sub-bttn').on('click', function () {
            var customerEmail = $('#newsletter').val();
            var formObj = {
                email: customerEmail
            };
            sendAppLink(formObj);
        });

        function sendAppLink(formObj) {

            $.ajax({
                type: 'GET',
                url: window.location.href + '/notify',
                data: formObj,
                showLoader: true,
                success: function (response) {
                    alert(response);
                }
            });

        }


    });
});

// search faqs
require([
    'jquery',
], function ($) {

    var searchbtnText = 'Highlight';
    var inputId = 'faqs__search_text_area';
    var btnId = 'faqs__search_text_btn';

    $(document).ready(function () {
        console.log("search");
        var searchInput = '<div class="faqs__search_box" ><input type="text" id="' + inputId + '"/>' +
            '<button id="' + btnId + '">' +
            searchbtnText +
            '</button></div>';

        $('.cms-faqs .cls_shipping_panelmain').prepend(searchInput);

        $('#' + inputId).on('keydown', function (e) {
            if (e.key == 'Enter') {
                $('#' + btnId).click()
            }
        });

        $('#' + btnId).on("click", function () {
            var searched = $('#' + inputId).val().trim();

            if (searched !== "") {
                var searchSelector = $('.cls_shipping_panelmain .accordion_body .md-paragraph');

                searchSelector.each(function (element, index) {
                    // delete the old marks
                    $(this).find('mark').contents().unwrap();

                    var text = $(this).html();

                    var re = new RegExp(searched, "g"); // search for all instances

                    var newText = text.replace(re, '<mark>' + searched + '</mark>');

                    $(this).html(newText);
                })

                var firstMark = searchSelector.find('mark').first()

                if (firstMark.length > 0) {
                    var parentOfFirstMark = firstMark.closest('.cls_shipping_panelsub').children();

                    if ($(parentOfFirstMark[1]).css("display") == "none") {
                        parentOfFirstMark[0].click()
                    }

                    $('html, body').animate({
                        scrollTop: firstMark.offset().top
                    }, 500)
                }
            }
        })

    });

    $(document).ready(function($){
        $('body').on('click', 'input[type="checkbox"]', function(){
            var formBlock = $(this).closest('.form-block.checkbox');
            var checkMark = formBlock.find('.checkmark');
            if($(checkMark).hasClass('checked')){
                $(checkMark).removeClass('checked');
                $(this).removeAttr('checked');
            } else {
                $(checkMark).addClass('checked');
                $(this).attr('checked','checked');
            }
        });
        $('body').on('click', '.checkmark', function(){
            var formBlock = $(this).closest('.form-block.checkbox');
            var checkBox = formBlock.find('input[type="checkbox"]');
            if($(this).hasClass('checked')){
                $(this).removeClass('checked');
                $(checkBox).removeAttr('checked');
            } else {
                $(this).addClass('checked');
                $(checkBox).attr('checked','checked');
            }
        });

        $('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter('.quantity input');
        $('.quantity').each(function() {
            var spinner = jQuery(this),
                input = spinner.find('input[type="number"]'),
                btnUp = spinner.find('.quantity-up'),
                btnDown = spinner.find('.quantity-down'),
                min = input.attr('min'),
                max = input.attr('max');

            btnUp.click(function() {
                var oldValue = parseFloat(input.val());
                if (oldValue >= max) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue + 1;
                }
                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
            });

            btnDown.click(function() {
                var oldValue = parseFloat(input.val());
                if (oldValue <= min) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue - 1;
                }
                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
            });

        });
    });

    $(".customer_logged_in > span").on("click", function(){
        $(".customer_logged_in > ul").toggle();
    });

});
