define([
    'jquery',
    'Smartwave_Megamenu/js/sw_megamenu'
], function ($) {
    $(".sw-megamenu").swMegamenu();

    $(document).on('click', '.nested-link', function (e) {
        e.preventDefault();
        var closestNestedMenuBlock = $(this).closest('.nested-menu-block');
        $('.nested-menu-block').find('.subchildmenu').css('display', 'none');

        closestNestedMenuBlock.find('.subchildmenu:first').css('display', 'flex');
        closestNestedMenuBlock.find('.level2').find('.subchildmenu').css('display', 'block');
    });
});
