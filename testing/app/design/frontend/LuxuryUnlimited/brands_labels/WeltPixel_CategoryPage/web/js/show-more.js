define([
    'jquery'
], function ($) {
    'use strict';
    return function (config) {
        
        $(document).ready(function(){
            var animationSpeed = config.animationSpeed;
            var categoryViewContainer = $('.category-view'),
                categoryDescription = categoryViewContainer.find('.category-description'),
                showMore = categoryViewContainer.find('.category-show-more'),
                iniHeight = categoryDescription.innerHeight();

            $('.category-description-copy').html(categoryDescription.clone());
            var fullHeight = categoryViewContainer.find('.category-description-copy').innerHeight();

            categoryDescription.addClass('more-less less');
            showMore.on('click', '.action-view', function() {
                if (categoryDescription.hasClass('less')) {
                    categoryDescription
                        .removeClass('less').addClass('more')
                        .animate({'max-height': fullHeight + 'px'}, 
                        animationSpeed);
                } else {
                    categoryDescription
                        .removeClass('more').addClass('less')
                        .animate({'max-height': iniHeight + 'px'}, 
                        animationSpeed);
                }

                showMore.find('.action-view').each(function() {
                    $(this).toggleClass('active');
                });
            });
        });
    }
});