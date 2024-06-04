
define([
    'jquery', 
    'owl_carousel', 
    'owl_config', 
    'owl_config' 
], function ($) {
    'use strict';
    
    return function(config) {
        $(document).ready(function(){
            var randomSort = config.$randomSort;
            var breakpoint1 = config.breakpoint1;
            var breakpoint2 = config.breakpoint2;
            var breakpoint3 = config.breakpoint3;
            var breakpoint4 = config.breakpoint4;

            var products_type = config.productsType;

            var sliderOption = config.sliderConfig;       
            var sliderOptions =  sliderOption.replace(/'/g, '"');
            var slider_config = JSON.parse(sliderOptions);
            
            var productsCount = config.productsCount;
            var carouselElement = $('.owl-carousel-products-' + products_type),
            items = ((slider_config.items >= 0 && slider_config.items != null) ? productsCount > slider_config.items ? slider_config.items : productsCount : 2),
            itemsBrk1 = (slider_config.items_brk1  >= 0 && slider_config.items_brk1 != null) ? parseInt(slider_config.items_brk1) : items,
            itemsBrk2 = (slider_config.items_brk2  >= 0 && slider_config.items_brk2 != null) ? parseInt(slider_config.items_brk2) : items,
            itemsBrk3 = (slider_config.items_brk3  >= 0 && slider_config.items_brk3 != null) ? parseInt(slider_config.items_brk3) : items,
            itemsBrk4 = (slider_config.items_brk4  >= 0 && slider_config.items_brk4 != null) ? parseInt(slider_config.items_brk4) : items,
            stagePadding = slider_config.stagePadding != '' ? parseInt(slider_config.stagePadding) : 0,
            sPBrk_1 = slider_config.stagePadding_brk1 != '' ? parseInt(slider_config.stagePadding_brk1) : 0,
            sPBrk_2 = slider_config.stagePadding_brk2 != '' ? parseInt(slider_config.stagePadding_brk2) : 0,
            sPBrk_3 = slider_config.stagePadding_brk3 != '' ? parseInt(slider_config.stagePadding_brk3) : 0,
            sPBrk_4 = slider_config.stagePadding_brk4 != '' ? parseInt(slider_config.stagePadding_brk4) : 0;

            var stagePadding = slider_config.stagePadding != '' ? parseInt(slider_config.stagePadding) : 0;
            var options = {
                thumbs: true,
                nav                 :parseInt(slider_config.nav) == 1 ? true : false,
                dots                :parseInt(slider_config.dots) == 1 ? true : false,
                center              :parseInt(slider_config.center) == 1 ? true : false,
                items               :items,
                loop                :parseInt(slider_config.loop) == 1 ? true : false,
                margin              :parseInt(slider_config.margin) || 0,
                stagePadding        :parseInt(slider_config.center) == 1 ? 0 : stagePadding,
                lazyLoad            :parseInt(slider_config.lazyLoad) == 1 ? true : false,
                autoplay            :parseInt(slider_config.autoplay) == 1 ? true : false,
                autoplayTimeout     :(slider_config.autoplayTimeout > 0 && slider_config.autoplayTimeout != null) ? parseInt(slider_config.autoplayTimeout) : 3000,
                autoplayHoverPause  :parseInt(slider_config.autoplayHoverPause) == 1 ? true : false,
                autoHeight          :false,
                responsive:{
                    1 :{
                        nav             :parseInt(slider_config.nav_brk1) == 1 ? true : false,
                        dots            :parseInt(slider_config.dots_brk1) == 1 ? true : false,
                        items           :(productsCount > itemsBrk1) ? itemsBrk1 : productsCount,
                        center          :parseInt(slider_config.center_brk1) == 1 ? true : false,
                        stagePadding    :parseInt(slider_config.center) == 1 ? 0 : sPBrk_1
                    },
                    2 :{
                        nav             :parseInt(slider_config.nav_brk2) == 1 ? true : false,
                        dots            :parseInt(slider_config.dots_brk2) == 1 ? true : false,
                        items           :(productsCount > itemsBrk2) ? itemsBrk2 : productsCount,
                        center          :parseInt(slider_config.center_brk2) == 1 ? true : false,
                        stagePadding    :parseInt(slider_config.center) == 1 ? 0 : sPBrk_2
                    },
                    3 :{
                        nav             :parseInt(slider_config.nav_brk3) == 1 ? true : false,
                        dots            :parseInt(slider_config.dots_brk3) == 1 ? true : false,
                        items           :(productsCount > itemsBrk3) ? itemsBrk3 : productsCount,
                        center          :parseInt(slider_config.center_brk3) == 1 ? true : false,
                        stagePadding    :parseInt(slider_config.center) == 1 ? 0 : sPBrk_3
                    },
                    4 :{
                        nav             :parseInt(slider_config.nav_brk4) == 1 ? true : false,
                        dots            :parseInt(slider_config.dots_brk4) == 1 ? true : false,
                        items           :(productsCount > itemsBrk4) ? itemsBrk4 : productsCount,
                        center          :parseInt(slider_config.center_brk4) == 1 ? true : false,
                        stagePadding    :parseInt(slider_config.center) == 1 ? 0 : sPBrk_4,

                    }

                }
            }

            options.responsive[breakpoint1] = options.responsive[1];
            options.responsive[breakpoint2] = options.responsive[2];
            options.responsive[breakpoint3] = options.responsive[3];
            options.responsive[breakpoint4] = options.responsive[4];
            delete options.responsive[1];
            delete options.responsive[2];
            delete options.responsive[3];
            delete options.responsive[4];

            if (randomSort) {
                carouselElement.on('initialize.owl.carousel', function(event) {
                    var $this = $(this);
                    var carouselItems = $this.children();
                    while (carouselItems.length) {
                        $this.append(carouselItems.splice(Math.floor(Math.random() * carouselItems.length), 1)[0]);
                    }
                });
            }

            carouselElement.on('initialized.owl.carousel', function(event) {
                setTimeout(function(){
                    carouselElement.trigger('next.owl.carousel');
                    $('.owl-thumbs').each(function() {
                        if (!$('.owl-thumbs').children().length) {$(this).remove();}
                    });
                    $('.cssload-loader').parent().remove();
                }, 370);
            });
            /** Lazyload bug when fewer items exist in the carousel then the ones displayed */
            carouselElement.on('initialized.owl.carousel', function(event){
                var scopeSize = event.page.count;
                for (var i = 0; i < scopeSize; i++){
                    var imgsrc = $(event.target).find('.owl-item').eq(i).find('img').attr('data-src');
                    $(event.target).find('.owl-item').eq(i).find('img').attr('src', imgsrc);
                    $(event.target).find('.owl-item').eq(i).find('img').attr('style', 'opacity: 1;');
                }
            });
           
            carouselElement.owlCarousel(options);
        });
    }

   
});
