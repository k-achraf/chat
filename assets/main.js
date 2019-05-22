$(document).ready(function () {

    $('input').focus(function () {
        $(this).parent().addClass('span');
    }).blur(function () {
        if ($(this).val() === ''){
            $(this).parent().removeClass('span');
        }
    });
});