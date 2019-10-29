loadLog("worklist.log");

function loadLog(logFile) {
    var client = new XMLHttpRequest();
    client.open('GET', '../logs/' + logFile);
    client.onreadystatechange = function() {
        var element = document.getElementById("log");
        element.value = client.responseText;
        element.scrollTop = element.scrollHeight;
    }
    client.send();
}