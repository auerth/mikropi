<?php
//DELTE HASHS which older than 6 Months
include("db.php");
$logFile = "../logs/worklist.log";
$sql = "SELECT  id, name,file  FROM cut WHERE toDelete = 1;";
if ($result = $db->query($sql)) {
    while ($row = $result->fetch_array()) {
        $out = exec('cd ../files/cuts && rm -r ' . $row["file"] . "_files/");
        $out = exec('cd ../files/cuts && rm -r ' . $row["file"] . ".dzi");
        $sql = "DELETE FROM cut WHERE id = '" . $row["id"] . "';";
        if ($result2 = $db->query($sql)) {
            $sql = "DELETE FROM ttCutCategory WHERE cutId = '" . $row["id"] . "';";
            if ($result2 = $db->query($sql)) {
                $sql = "DELETE FROM ttModulCut WHERE cutId = '" . $row["id"] . "';";
                if ($result2 = $db->query($sql)) {
                    $log = file_get_contents($logFile);
                    file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": Deleted Cut " . $row["name"] . "\n");
                } else {
                    echo ($db->error);
                }
            } else {
                echo ($db->error);
            }
        } else {
            echo ($db->error);
        }
    }
} else {
    echo ($db->error);
}
