jQuery(document).ready(function() {
    jQuery('.toast__close').click(function(e) {
        e.preventDefault();
        var parent = $(this).parent('.toast');
        $('#reportBug').fadeOut('slow', function() {});

    });
    $('#reportBug').fadeIn('slow', function() {});
});