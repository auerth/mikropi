<?php
$logFile = "../logs/worklist.log";

if (!empty($_FILES)) {
    $files = $_FILES['files'];
    if (!file_exists("../files/tmp")) {
        mkdir("../files/tmp");
    }
    move_uploaded_file($files['tmp_name'], "../files/tmp/" . $files['name']); //Upload the file
    $log = file_get_contents($logFile);
    file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": " . $files["name"] . " in worklist verschoben\n");
}
