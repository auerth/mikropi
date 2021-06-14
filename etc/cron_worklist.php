<?php
// Ignoriere Abbruch durch den Benutzer und erlaube dem Skript weiterzulaufen
set_time_limit(0);
$dir = "../files/tmp/";
$logFile = "../logs/worklist.log";
$statusFile = "worklist.meta";

$json = file_get_contents($statusFile);
$json = json_decode($json, true);

if (!$json["working"]) {
    $json["working"] = true;
    $jsonString = json_encode($json);
    file_put_contents($statusFile, $jsonString);


    $files1 = scandir($dir);
    $files1 = array_diff($files1, array('.', '..'));

    foreach ($files1 as $key => $value) {
        $nameNoExtention = $value;
        $nameNoExtention = str_replace(".tiff", "", $nameNoExtention);
        $nameNoExtention = str_replace(".tif", "", $nameNoExtention);
        $nameNoExtention = str_replace(".TIFF", "", $nameNoExtention);
        $nameNoExtention = str_replace(".TIF", "", $nameNoExtention);
        if (file_exists("../files/cuts/" . $nameNoExtention)) {
            $log = file_get_contents($logFile);
            file_put_contents($logFile, $log . "ERROR-" . date('d/m/Y H:i:s', time()) . ": Converting Folder '" . $nameNoExtention . "' already exists\n");
            echo "Converting Folder already exsists " . $nameNoExtention;
            rename("../files/tmp/" . $value, "../files/converting/failed/" . $value);
        } else {
            if (!file_exists("../files/cuts/" . $nameNoExtention . "_files") && !file_exists("../files/cuts/" . $nameNoExtention . ".dzi")) {
                $log = file_get_contents($logFile);
                file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": Converting File '" . $value . "'\n");
                $out = exec('../etc/prepare_tiff.sh "../files/tmp/' . $value . '" "../files/cuts/' . $nameNoExtention . '"');
                if (strlen($out) > 0) {
                    $log = file_get_contents($logFile);
                    file_put_contents($logFile, $log . "WARNING-" . date('d/m/Y H:i:s', time()) . ": Output for '" . $nameNoExtention . "': '" . $out . "'\n");
                }
                if (file_exists("../files/cuts/" . $nameNoExtention . "_files") && file_exists("../files/cuts/" . $nameNoExtention . ".dzi")) {
                    rename("../files/tmp/" . $value, "../files/converting/successed/" . $value);
                    $log = file_get_contents($logFile);
                    file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": Finished Converting '" . $nameNoExtention . "'\n");
                    checkForCuts();
                    echo "Done " . $nameNoExtention;
                } else {
                    $log = file_get_contents($logFile);
                    file_put_contents($logFile, $log . "ERROR-" . date('d/m/Y H:i:s', time()) . ": DZI and Files Folder for'" . $nameNoExtention . "' does not exists. Converting Failed\n");
                    rename("../files/tmp/" . $value, "../files/converting/failed/" . $value);
                    $out = exec('cd ../files/cuts && rm -r "' . $nameNoExtention . '/"');
                    $log = file_get_contents($logFile);
                    file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": DELETED KONVERTING FOLDER'" . $nameNoExtention . "'\n");
                }
                //$out = exec('cd ../files/cut && rm -r '.$nameNoExtention);
            } else {
                $log = file_get_contents($logFile);
                file_put_contents($logFile, $log . "ERROR-" . date('d/m/Y H:i:s', time()) . ": DZI and Files Folder for'" . $nameNoExtention . "' already exists\n");
                rename("../files/tmp/" . $value, "../files/converting/failed/" . $value);
                echo "Already exsists " . $nameNoExtention;
            }
        }
        break;
    }
    $json["working"] = false;
    $jsonString = json_encode($json);
    file_put_contents($statusFile, $jsonString);
} else {
    echo "Script is running";
}



function sleepUntilWritten($filename)
{
    while (true) {
        $filesize_old = filesize($filename);
        sleep(4);
        $filesize_new = filesize($filename);
        if ($filesize_old == $filesize_new) {
            echo ("file is written");
            return true;
        }
    }
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
                file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": Added cut to database\n");
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
