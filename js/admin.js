$(document).ready(function() {

    $('#upload').click(function(e) {
        e.preventDefault(); //Prevent a page load
        prepare();
    });
});
var allowUpload = true;
$("#files").change(function(e) {
    var uploadButton = document.getElementById("upload");
    var errormsg = document.getElementById("errormsg");

    uploadButton.disabled = false;
    errormsg.innerHTML = "";
    for (var i = 0; i < this.files.length; i++) {
        var count = this.files.item(i).name.count(".");
        if (count != 1) {
            allowUpload = false;
            uploadButton.disabled = true;
            errormsg.innerHTML = " " + this.files.item(i).name + " hat mehr als einen . im Namen";

            break;
        }
    }
});
String.prototype.count = function(c) {
    var result = 0,
        i = 0;
    for (i; i < this.length; i++)
        if (this[i] == c) result++;
    return result;
};

async function prepare() {
    var uploader = document.getElementById('files');
    document.getElementById('uploadlist').innerHTML = "";
    var data = new FormData(); //New with HTML5

    for (var i = 0; i < uploader.files.length; i++) {
        $('.progress').append('<p id="' + i + '" class="percentage">' + uploader.files[i].name + ' <span class="waiting">Eingereiht</span></p>');

    }

    upload(uploader.files, 0, data);
}

$('#searchUser').on('input', function(e) {
    searchUser();
});



function searchUser() {
    var input, filter, ul, li, a, i, matrikel, name, email;
    input = document.getElementById("searchUser");
    filter = input.value.toUpperCase();
    ul = document.getElementById("userListe");
    li = ul.getElementsByClassName("userMedia");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("summary")[0];
        name = a.textContent || a.innerText;
        a = li[i].getElementsByTagName("a")[0];
        email = a.textContent || a.innerText;
        a = li[i].getElementsByClassName("filterMatrikel")[0];
        matrikel = a.textContent || a.innerText;
        if (name.toUpperCase().indexOf(filter) > -1 || email.toUpperCase().indexOf(filter) > -1 || matrikel.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";

        } else {
            li[i].style.display = "none";
        }


    }
}
var tid = setInterval(isWorking, 1000);

function isWorking() {
    $.ajax({
        url: "../etc/worklist.meta",
        dataType: "json",
        success: function(response) {
            var el = document.getElementById("working");
            if (response.working) {
                el.style.visibility = "visible";
            } else {
                el.style.visibility = "hidden";

            }
        }
    });
}

async function upload(files, i, data) {
    var xhr = new XMLHttpRequest();
    var startTime = new Date().getTime();
    var file = files[i];
    data.append('files', file); //Appending the File object

    xhr.timeout = -1;
    //Upload progress listener     

    xhr.upload.addEventListener('progress', function(e) {

        if (e.lengthComputable) {
            var percentage = Math.ceil(((e.loaded / e.total) * 100));


            var elapsedTime = new Date().getTime() - startTime;
            var bytesPerSec = e.loaded /
                ((elapsedTime) / 1000);
            var mbPerSec = bytesPerSec / 1000 / 1000;
            mbPerSec = mbPerSec.toFixed(2)
            var allTimeForDownloading = (elapsedTime * e.total / e.loaded);
            var remainingTime = allTimeForDownloading - elapsedTime;
            var date = new Date(remainingTime);

            if (date.getUTCHours() != "0") {
                $('#' + i + ' span').text(percentage + '% ' + mbPerSec + "MB/s " + " Verbleibende Zeit: " + date.getUTCHours() + " Stunden " + date.getUTCMinutes() + " Minuten " + date.getUTCSeconds() + " Sekunden"); //Updating the percentage <span> at i

            } else if (date.getUTCMinutes() != "0") {
                $('#' + i + ' span').text(percentage + '% ' + mbPerSec + "MB/s " + " Verbleibende Zeit: " + date.getUTCMinutes() + " Minuten " + date.getUTCSeconds() + " Sekunden"); //Updating the percentage <span> at i

            } else if (date.getUTCSeconds() != "0") {
                $('#' + i + ' span').text(percentage + '% ' + mbPerSec + "MB/s " + " Verbleibende Zeit: " * date.getUTCSeconds() + " Sekunden"); //Updating the percentage <span> at i

            } else {
                $('#' + i + ' span').text(percentage + '% ' + mbPerSec + "MB/s"); //Updating the percentage <span> at i

            }
            $('#' + i + ' span').removeClass('waiting');

        } else {
            console.log("Error: Length not computable");
        }
    });
    xhr.upload.addEventListener('timeout', function(e) {


        $('#' + i + ' span').text("Timeout"); //Updating the percentage <span> at i
        $('#' + i + ' span').addClass('error');


    });

    xhr.addEventListener('readystatechange', function(e) {
        if (this.readyState == 4) {
            if (this.status == 200) {
                $('#' + i + ' span').text('Upload abgeschlossen');
                $('#' + i + ' span').addClass('success');

            } else {
                $('#' + i + ' span').text('Error: ' + this.status + " " + this.statusText);
                $('#' + i + ' span').addClass('error');
            }
            upload(files, i + 1, data);

        }
    });



    xhr.open('POST', '../classes/upload.php');
    xhr.setRequestHeader('Cache-control', 'no-cache');
    xhr.send(data);
}