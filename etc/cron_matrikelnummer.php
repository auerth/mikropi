<?php
//DELTE MATRIKELNUMBER which older than 4 Years
include("db.php");
$sql = "SELECT  id, UNIX_TIMESTAMP(timestamp) as timestamp FROM matrikelnumber;";
if ($result = $db->query($sql)) {
    while ($row = $result->fetch_array()) {
        $time = time();
        $hashTime = $row["timestamp"];
        $dif = $time - $hashTime;
        if ($dif >= 126100000) {
            $sql = "DELETE  FROM matrikelnumber WHERE id = '" . $row["id"] . "';";
            if ($result2 = $db->query($sql)) {
            }else{
                echo ($db->error);
            }
        }
    }
} else {
    echo ($db->error);
}
