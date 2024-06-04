require([
    'jquery',
    'mage/url',
    'Magento_Ui/js/modal/modal',
    'domReady!'
], function ($, url, thankYouModal, dom) {
    "use strict";

    let searchbtnText = 'Highlight';
    let inputId = 'faqs__search_text_area';
    let btnId = 'faqs__search_text_btn';
    const specialRequestsBtn = $('.clsspecialrequest h3');
    const closeBtn = $('.close');
    const modal = document.getElementById("myModalspec");

    specialRequestsBtn.click(function(){
        modal.style.display = "block";
    });

    closeBtn.click(function() {
        modal.style.display = "none";
    });

    $('#btn_submit').click(function(){
        let dataForm = jQuery('#'+$(this).closest('form').attr('id'));
        let link = url.build('redirectcontactus/index/success');

        if(dataForm.validation('isValid')){
            $("#result-message").text('');
            $('#myModalspec').css({"display":"none"});

            $.ajax({
                url: dataForm.attr('action'),
                type: dataForm.attr('method'),
                data: dataForm.serialize(),
                dataType: 'json',
                async: true,
                beforeSend: function() {
                    $('#loader-message').show();
                },
                complete: function(){
                    $('#loader-message').hide();
                },
                success: function (response) {
                    if(response.errors === false) {
                        $(location).prop('href', link);



                    } else {
                        $('#result-message').html(response.message);
                        dataForm[0].reset();
                    }
                },
                error: function (response) {
                    console.log(JSON.parse(response));
                },
            });
            event.stopImmediatePropagation();

            return false;
        }
    });

    $('#btn_ticket').click(function (e) {
        e.preventDefault();
        var dataForm = $('#' + $(this).closest('form').attr('id'));
        if (dataForm.validation('isValid')) {
            $("#result-message").text('');
            $('#myModalspec').css({"display": "none"});

            const $this = $(this);

            $.ajax({
                url: dataForm.attr('action'),
                type: dataForm.attr('method'),
                data: dataForm.serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $('#loader-message').show();
                },
                complete: function () {
                    $('#loader-message').hide();
                },
                success: function (response) {
                    if (response.errors === false) {
                        $('#result-message').html(response.message);

                        dataForm[0].reset();

                        if ($this.closest('.modal-content').length > 0) {
                            $this.closest('.modal-popup').find('.action-close').click();
                            openThankYouWindow();
                        }

                    } else {
                        $('#result-message').html(response.message);
                        dataForm[0].reset();
                    }
                },
                error: function (response) {
                    console.log(JSON.parse(response));
                }
            });
        }
    });

    const openThankYouWindow = () => {
        let options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: $.mage.__('Thank You'),
            buttons: [{
                text: $.mage.__('Close'),
                class: 'action-secondary action-dismiss',
                click: function () {
                    this.closeModal();
                }
            }]
        };

        const popup = thankYouModal(options, $('#thank-you-modal'));
        popup.openModal();
    }

    $(document).ready(function () {
        let searchInput = '<div class="faqs__search_box" ><input type="text" id="' + inputId + '"/>' +
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
            let searched = $('#' + inputId).val().trim();

            if (searched !== "") {
                let searchSelector = $('.cls_shipping_panelmain .accordion_body .md-paragraph');

                searchSelector.each(function (element, index) {
                    // delete the old marks
                    $(this).find('mark').contents().unwrap();

                    let text = $(this).html();

                    let re = new RegExp(searched, "g"); // search for all instances

                    let newText = text.replace(re, '<mark>' + searched + '</mark>');

                    $(this).html(newText);
                })

                let firstMark = searchSelector.find('mark').first()

                if (firstMark.length > 0) {
                    let parentOfFirstMark = firstMark.closest('.cls_shipping_panelsub').children();

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

        $('body').on('click', '.checkmark', function(){
            let formBlock = $(this).closest('.form-block.checkbox');
            let checkBox = formBlock.find('input[type="checkbox"]');

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
            let spinner = jQuery(this),
                input = spinner.find('input[type="number"]'),
                btnUp = spinner.find('.quantity-up'),
                btnDown = spinner.find('.quantity-down'),
                min = input.attr('min'),
                max = input.attr('max');

            btnUp.click(function() {
                let oldValue = parseFloat(input.val());

                if (oldValue >= max) {
                    let newVal = oldValue;
                } else {
                    let newVal = oldValue + 1;
                }

                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
            });

            btnDown.click(function() {
                let oldValue = parseFloat(input.val());

                if (oldValue <= min) {
                    let newVal = oldValue;
                } else {
                    let newVal = oldValue - 1;
                }

                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
            });

        });
    });

    $(".customer_logged_in > span").on("click", function(){
      $(".customer_logged_in > ul").toggle();
    });


    // show hide password
    if ($('.luxb_password').length > 0 ) {
        $(".toggle-password").click(function() {
            $(this).toggleClass("psw-eye psw-eye-slash");
            var input = $($(this).next("input"));

            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    }

    $('#toggle-bars').click(function(){
		$(this).toggleClass('open');
        $('.cms-index-index .sections.nav-sections').toggleClass('nav-open');
	});

    // custom file upload
    if ($('.file-input-choice').length > 0 ) {
        var inputs = document.querySelectorAll('.file-input-choice')

        for (var i = 0, len = inputs.length; i < len; i++) {
            customInput(inputs[i])
        }

        function customInput (el) {
            const fileInput = el.querySelector('[type="file"]')
            const label = el.querySelector('[data-js-label]')

            fileInput.onchange =
                fileInput.onmouseout = function () {
                    if (!fileInput.value) return

                    var value = fileInput.value.replace(/^.*[\\\/]/, '')
                    el.className += ' -chosen'
                    label.innerText = value
                }
        }
    }

    // country list filter
    if ($('#searchText').length > 0 ) {
        $.extend($.expr[":"], {
            "containsIN": function (elem, i, match, array) {
                return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });
        $('#searchText').keyup(function () {
            var text = $(this).val().toLowerCase();
            if (!text) {
                $('.countrylist li').show();
            } else {
                $('.countrylist li').hide();
                $('.countrylist li:containsIN("' + text + '")').show();
                if ($('.countrylist li:containsIN("' + text + '")').length == 0) {
                    $('#no-result').show();
                } else {
                    $('#no-result').hide();
                }
            }
        });

        $('.letter').click(function (event) {
            var countries = $('.countrylist li');
            countries.show();
            var letter = event.target.dataset.letter;

            countries.each(function () {
                var country = $(this);
                var name = $.trim(country.text());

                if (name && letter !== name.charAt(0)) {
                    country.hide();
                }
            });
        });
    }

    // Wishlist link
    $('.wishlist-redirect').on('click', function () {
        var dataUrl = $(this).attr('data-url');
        window.location.href= dataUrl;
    });

    $('.wishlist-continue').on('click', function () {
        $('.wishlist-requst-modal .action-close').trigger('click');
    });
    function closePopupSpecialRequest() {
        $("#wishlist-modal-addcontent").modal("closeModal");
    }
    window.closePopupspecialrequest = closePopupSpecialRequest;

    // Wishlist link
    if ($('.filter-options-title').length > 0 ) {
        $(".filter-options-title").click(function() {
            // Toggle the class "active" on the title element
            $(this).toggleClass("active");

            // Check if the title element has the class "active"
            if ($(this).hasClass("active")) {
                // If it has the class, show the content
                $(this).next(".filter-options-content").slideDown();
            } else {
                // If it doesn't have the class, hide the content
                $(this).next(".filter-options-content").slideUp();
            }
        });

        $("#layered-filter-block .filter-title").click(function() {
            $(".block-content.filter-content").slideToggle();
        })
    }

    // Sticky Header
    $(window).scroll(function () {  
        var getHeaderHeight = $('.page-header .header.content').innerHeight(); 
        var scroll = $(window).scrollTop();
        if(scroll >= getHeaderHeight + 75) {
            $(".page-header").addClass("sticky active");
        } else {
            $(".page-header").removeClass("sticky active");
        }
    }); 
});
