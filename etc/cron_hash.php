<?php
//DELTE HASHS which older than 6 Months
include("db.php");
$logFile = "../logs/hash.log";
$sql = "SELECT  id, hash, UNIX_TIMESTAMP(timestamp) as timestamp FROM hash;";
if ($result = $db->query($sql)) {
    while ($row = $result->fetch_array()) {
        $time = time();
        $hashTime = $row["timestamp"];
        $dif = $time - $hashTime;
        if ($dif >= (15770000 * 2)) {
            $sql = "DELETE  FROM hash WHERE id = '" . $row["id"] . "';";
            if ($result2 = $db->query($sql)) {
                $log = file_get_contents($logFile);
                file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": Deleted Hash " . $row["hash"] . "\n");
            }else{
                echo ($db->error);
            }
        }
    }
} else {
    echo ($db->error);
}
