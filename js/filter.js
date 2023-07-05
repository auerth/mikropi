var selectedDropDown = null
$(".dropdown-menu li a").click(function() {
    var selText = $(this).text();
    $(this).parents('.dropdown').find('.dropdown-toggle').html(selText);
    $(this).parents('.dropdown').find('.dropdown-toggle').val($(this).attr("id"));
    selectedDropDown = $(this).parents('.dropdown').find('.dropdown-toggle').attr("id");


});

function check(id) {
    var checkBox = document.getElementById(id);
    checkBox.checked = !checkBox.checked;
}
$(".dropdown-item").click(function(event) {
    if (event.target.id == -2) {
        var modal = document.getElementById('modalCategory');
        var categoryInput = document.getElementById('newCategory');
        var category = document.getElementById('categoryName');
        category.value = selectedDropDown;



        var span = document.getElementsByClassName("close")[0];
        modal.style.display = "block";
        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    } else {
        var id = event.target.id;
        console.log(id);
        if (id != null && id != "") {
            var params = {
                'categoryId': id,
                'categoryName': selectedDropDown
            };
            post("admin.php", params, "POST");
        }
    }
});



function deleteModul(id) {
    var params = {
        'modulId': id
    };
    post("admin.php", params, "POST");
}

function deleteUser(id) {
    var check = confirm('Wollen Sie diesen Benutzer wirklich löschen?');
    if (check == true) {
        var params = {
            'userId': id,
            'deleteUser': ""
        };
        post("admin.php", params, "POST");
    }
}


function sort(by) {
    var params = {
        'sortBy': by
    };
    post("admin.php", params, "POST");
}


function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

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