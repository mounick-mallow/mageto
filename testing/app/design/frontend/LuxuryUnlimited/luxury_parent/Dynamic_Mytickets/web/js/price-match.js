define([
    'jquery',
    'domReady!'
], function ($)
{
    var modalPriceMatch = document.getElementById("priceMatchModal");
    var spanPriceMatch = $(modalPriceMatch).find('.close');
    var btn = document.getElementById("price-match-create-ticket-bth");
    var btnAlreadyBuy = document.getElementById("price-match-already-made-create-ticket-bth");

    if(spanPriceMatch) {
        spanPriceMatch.on("click", (e) => {
            modalPriceMatch.style.display = "none";
            modalPriceMatch.modal('hide');
        });
    }

    if (btn) {
        btn.onclick = function() {
            var is_it_bought = $(this).attr('data-buy');
            $(modalPriceMatch).find('#priceMatchIsItBought').val(is_it_bought);
            modalPriceMatch.style.display = "block";
        }
    }

    if (btnAlreadyBuy) {
        btnAlreadyBuy.onclick = function() {
            var is_it_bought = $(this).attr('data-buy');
            $(modalPriceMatch).find('#priceMatchIsItBought').val(is_it_bought);
            modalPriceMatch.style.display = "block";
        }
    }

    if (modalPriceMatch) {
        modalPriceMatch.addEventListener("click", (e) => {
            if(e.target === modalPriceMatch) {
                modalPriceMatch.style.display = "none";
            }
        });
    }

    window.onclick = function(event) {
        if (event.target === modalPriceMatch) {
            modalPriceMatch.style.display = "none";
        }
    }

});
