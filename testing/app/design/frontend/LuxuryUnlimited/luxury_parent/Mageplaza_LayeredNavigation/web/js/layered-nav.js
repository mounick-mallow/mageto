define([
    'jquery'
], function ($) {
	'use strict';
	return function(config) {
		var elementJs = config.elementJs;
		if (elementJs == 'wrap_option_is_true') {
			$(".show").on('click',function(){
				$(".cls_filter_data").toggleClass("active");
			});		

			const maxTotalFilter = 8;
			var totalFilter = $('.cls_filter_data .items .item').length;
			if(totalFilter > maxTotalFilter){
				$('.filter-options-content .show').show();
			} else {
				$('.filter-options-content .show').hide();
			}

		} else if (elementJs == 'wrap_option_is_false') {
			$('#layered-filter-block').addClass('filter-no-options');
		} else if (elementJs == 'category_filter_nav') {
			$("#layered-filter-block").before($(".block.block-category-list"));
		}
		
	}
});