<?php
// Ignoriere Abbruch durch den Benutzer und erlaube dem Skript weiterzulaufen
set_time_limit(0);
$dir = "../files/tmp/";
$logFile = "../logs/worklist.log";
$statusFile = "worklist.meta";

$json = file_get_contents($statusFile);
$json = json_decode($json,true);

if (!$json["working"]) {
    $json["working"] = true;
    $jsonString = json_encode($json);
    file_put_contents($statusFile,$jsonString);


    $files1 = scandir($dir);
    $files1 = array_diff($files1, array('.', '..'));

    foreach ($files1 as $key => $value) {
        $nameNoExtention = $value;
        $nameNoExtention = str_replace(".tiff", "", $nameNoExtention);
        $nameNoExtention = str_replace(".tif", "", $nameNoExtention);
        $nameNoExtention = str_replace(".TIFF", "", $nameNoExtention);
        $nameNoExtention = str_replace(".TIF", "", $nameNoExtention);
        $log = file_get_contents($logFile);
        file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time() ) . ": Converting File '" . $value . "'\n");

        $out = exec('cd ../etc/ && bash prepare_tiff.sh "../files/tmp/' . $value . '" "../files/cuts/' . $nameNoExtention.'"');
        $out = exec('cd ../files/tmp && rm -r "' . $value.'"');
        //$out = exec('cd ../files/cut && rm -r '.$nameNoExtention);
        $log = file_get_contents($logFile);
        file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": Finished Converting '" . $nameNoExtention . "'\n");
        checkForCuts();
  
        break;
    }
    $json["working"] = false;
    $jsonString = json_encode($json);
    file_put_contents($statusFile,$jsonString);
} else {
    echo "Script is running";
}

function checkForCuts()
{
    $logFile = "../logs/worklist.log";

    $jsonResult = array(
        'success' => false,
        'errorCode' => 0,
        'error' => null,
        'info' => null
    );

    include("../etc/db.php");


    $dir = '../files/cuts';
    $files1 = scandir($dir);
    $files = array();
    foreach ($files1 as $file) {
        if (substr($file, -4) == ".dzi") {
            array_push($files, $file);
        }
    }
    $added = 0;
    foreach ($files as $file) {
        $fileS = substr($file, 0, -4);
        $sql = "SELECT * FROM cut WHERE file like '" . $fileS . "';";
        if ($result = $db->query($sql)) {
            $row_cnt = $result->num_rows;
            if ($row_cnt < 1) {
                $sql = "INSERT INTO cut (name, description, uploader,file)
						VALUES ('" . $fileS . "', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','" . $userId . "','" . $fileS . "'); ";
                $log = file_get_contents($logFile);
                file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time() ) . ": Added cut to database\n");
                if ($result = $db->query($sql)) {
                    $added++;
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Error by Inserting Data (Request error).";
                    $jsonResult["errorCode"] = "1";
                }
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting.";
            $jsonResult["errorCode"] = "1";
        }
    }

    return $jsonResult;
}
