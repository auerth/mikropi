var password = false

$('#password')
    .on(
        'input',
        function(e) {
            var rep = $('#password_repeat').val();
            var pass = $('#password').val();

            if (pass != "" && rep != "") {
                if (pass == rep) {
                    document.getElementById('password').style.borderColor = "green";
                    document.getElementById('password_repeat').style.borderColor = "green";
                    password = true;
                } else {
                    document.getElementById('password').style.borderColor = "red";
                    document.getElementById('password_repeat').style.borderColor = "red";
                    password = false;
                }
            }

        });


function check(id) {
    var checkBox = document.getElementById(id);
    checkBox.checked = !checkBox.checked;
}

$('#password_repeat')
    .on(
        'input',
        function(e) {
            var rep = $('#password_repeat').val();
            var pass = $('#password').val();
            if (pass != "" && rep != "") {
                if (pass == rep) {
                    document.getElementById('password').style.borderColor = "green";
                    document.getElementById('password').style.border = "green";
                    document.getElementById('password_repeat').style.borderColor = "green";
                    password = true;
                } else {
                    document.getElementById('password').style.borderColor = "red";
                    document.getElementById('password_repeat').style.borderColor = "red";
                    password = false;

                }
            }

        });

function validPassword(password) {
    var re = new RegExp(/^(?=.*\d).{4,15}$/);
    return re.test(String(password));
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function filled() {
    if ($('#name').val() != "" && $('#forename').val() != "" &&
        $('#matrikelnummer').val() != "" && document.getElementById("checkbox-agb").checked)
        return true;
    else
        return false;
}

function postForm() {
    if (password) {
        if (filled()) {
            if (validateEmail($('#email').val())) {
                if (validPassword($('#password').val())) {
                    if (!isNaN($('#matrikelnummer').val()) || $('#matrikelnummer').val().toLowerCase() == "n/a") {
                        document.getElementById("register").submit();
                    } else {
                        alert("Deine Matrikelnummer darf nur aus Zahlen bestehen");
                    }
                } else {
                    alert("Dein Passwort muss muss aus 4 bis 15 Ziffern bestehen und mindestens eine Ziffer enthalten.");
                }
            } else {
                alert("Email Format stimmt nicht.");
            }
        } else {
            alert("Bitte fülle alles im Formular aus.");

        }
    } else {
        alert("Passwort stimmt nicht überein.");
    }
}