/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

require([
  'jquery',
  'collapsible',
], function ($)
{
$(document).ready(function(){
    $(".cls_shipping_panelsub").collapsible({
      animate: 500,
      header : ".main-title",
      content : ".accordion_dropdown",
      icons: {"header": "arrow-down", "activeHeader": "arrow-up"},
    });
  });
});