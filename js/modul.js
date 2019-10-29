function deleteModul(id) {
    var params = {
        'modulId': id
    };
    post("", params, "POST");
}
$(".moduls .item").click(function(event) {
    var id = event.target.id;

    window.location.href = "moduls.php?id=" + id;
});

var cutList = [];
$("#saveModul").click(function(event) {
    if (cutList.length <= 0) {
        cutList = -1;
    }
    var params = {
        'cutList': cutList
    };
    post("", params, "POST");
});


$(".box").click(function(event) {

    if (isAdmin) {
        var id = event.target.id;
        if (!cutList.includes(id)) {
            cutList.push(id);
            $(this)
                .css({ 'background-color': '#5e2028', 'color': 'white' })

        } else {

            for (var i = 0; i < cutList.length; i++) {
                if (cutList[i] === id) {
                    cutList.splice(i, 1);
                }
            }

            $(this)
                .css({ 'background-color': '#D3D3D3', 'color': 'black' })

        }

    }
});


$(".card").click(function(event) {

    window.open("index.php?cuts=" + event.target.id);

});


function markCut(id) {
    if (!cutList.includes("" + id)) {
        cutList.push("" + id);
        $("#" + id + ".box")
            .css({ 'background-color': '#5e2028', 'color': 'white' })


    } else {
        for (var i = 0; i < cutList.length; i++) {
            if (cutList[i] === "" + id) {
                cutList.splice(i, 1);
            }
        }
        $("#" + id + ".box")
            .css({ 'background-color': '#D3D3D3', 'color': 'black' })


    }
    console.log(cutList);
}

function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not
    // specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }
    document.body.appendChild(form);
    form.submit();
}

function toggleCuts() {
    if ($('.cutcontainer').css('visibility') == "visible") {
        $(".cutcontainer").css("visibility", "hidden");
    } else {
        $(".cutcontainer").css("visibility", "visible");
    }

}