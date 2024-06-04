define([
	"jquery", 
	"Magento_Swatches/js/swatch-renderer"
], function ($) {
	'use strict';
	
	return function(config) {
		var attributeCode = config.attributeCode;
		$(document).ready(function() {
			$('.swatch-layered.'+attributeCode)
    		.find('[option-type="1"], [option-type="2"], [option-type="0"], [option-type="3"]')
    		.SwatchRendererTooltip();
		})
	}
});