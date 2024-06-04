require([
    'jquery',
    'domReady!'
], function($) {
    $("span:contains('Email To')").each(function(index){
        $(this).parent().next('div').
        find('input').attr(
            'data-validate',"{required:true, 'validate-email':true}"
        );
    });
});
