$(".media").click(function(event) {
    console.log("Test");
    window.location.href = "?cuts=" + event.target.id;
});

$(".dropdown-menu li a")
    .click(
        function() {
            var selText = $(this).text();
            $(this).parents('.dropdown').find('.dropdown-toggle').html(
                selText);
            $(this).parents('.dropdown').find('.dropdown-toggle').val(
                $(this).attr("id"));

        });
$("li .dropdown-item").click(function(event) {
    filter($("#search").val());

});

function countChar(val) {
    var len = val.value.length;
    if (len >= 500) {
        val.value = val.value.substring(0, 500);
    } else {
        $('#charNum').text(len + "/500");
    }
};

function filter(text) {
    var semester = $("#semester").val();
    var dozent = $("#lecturer").val();
    var organ = $("#organ").val();
    var organgroup = $("#organgroup").val();
    var schnittquelle = $("#schnittquelle").val();
    var diagnosisgroup = $("#diagnosisgroup").val();
    var icd_0 = $("#icd_0").val();
    var icd_10 = $("#icd_10").val();
    var parameters = {
        'semester': semester,
        'dozent': dozent,
        'organ': organ,
        'organgruppe': organgroup,
        'schnittquelle': schnittquelle,
        'icd_0': icd_0,
        'icd_10': icd_10,
        'diagnosegruppe': diagnosisgroup
    };

    $.post("../classes/index.php", parameters, function(result) {

        var obj = JSON.parse(result.toString());

        document.getElementById("liste").innerHTML = "";
        for (i = 0; i < obj.info.length; i++) {
            var name = obj.info[i].name;
            var html = obj.info[i].html;

            if (name.toLowerCase().includes(text.toLowerCase()))
                document.getElementById("liste").innerHTML = document
                .getElementById("liste").innerHTML +
                html;
        }
        $(".media").click(function(event) {
            window.location.href = "?cuts=" + event.target.id;
        });

    });
}

$('#search').on('input', function(e) {
    filter($("#search").val());
});