$(function() {
    $('span.message-button').each(function() {
        $(this).on('click', function() {
            $(this).parent().hide();
        });
    });
});
