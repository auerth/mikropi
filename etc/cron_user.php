<?php
//deactivate USER which older than 4 Years AND delete USER which older than 4 Years + 6 Month AND
include("db.php");
$logFile = "../logs/user.log";
$sql = "SELECT  id, UNIX_TIMESTAMP(created) as timestamp FROM user;";
if ($result = $db->query($sql)) {
    while ($row = $result->fetch_array()) {
        $time = time();
        $hashTime = $row["timestamp"];
        $dif = $time - $hashTime;
        if ($dif >= 126100000) {
            $sql = "UPDATE user SET activated = 0 WHERE id = '" . $row["id"] . "';";
            if ($result2 = $db->query($sql)) {
                $log = file_get_contents($logFile);
                file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": Deactivated User " . $row["id"] . "\n");
        
            }
        }

    
    }
} else {
    echo ($db->error);
}
$sql = "SELECT  id, email, UNIX_TIMESTAMP(created) as timestamp FROM user WHERE activated = 0;";
if ($result = $db->query($sql)) {
    while ($row = $result->fetch_array()) {
        $time = time();
        $hashTime = $row["timestamp"];
        $dif = $time - $hashTime;
        if ($dif >= 126100000 + (2628000 * 6)) {
            $sql = "DELETE FROM user WHERE id = '" . $row["id"] . "';";
            if ($result2 = $db->query($sql)) {
                $log = file_get_contents($logFile);
                file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": Deleted User " . $row["email"] . "\n");
            }
        }
    }
} else {
    echo ($db->error);
}
