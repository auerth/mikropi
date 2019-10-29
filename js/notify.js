jQuery(document).ready(function() {
    jQuery('.toast__close').click(function(e) {
        e.preventDefault();
        var parent = $(this).parent('.toast');
        $("#reportBug").fadeOut("slow", function() {});

        document.cookie = "bugreport=closed; max-age=43200; path=/; domain=mikropi.de"
    });
    $("#reportBug").fadeIn("slow", function() {

    });
});