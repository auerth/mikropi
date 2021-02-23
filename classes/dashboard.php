<?php

/**
 * The Dashboard Class - Add, get and remove dashboardentries from database
 * @author     Thorben Auer
 * @link       https://softwelop.com
 */
class Dashboard
{

     /**
     * Get dashboard entries
     *
     * 
     * @return array
     */
    public function getDashboardEntries()
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => "null",
            'info' => null
        );
        include ("../etc/db.php");
        
        $sql = "SELECT * FROM dashboard ORDER BY timestamp DESC;";
        if ($result = $db->query($sql)) {
            $info = array();
            while ($row = $result->fetch_array()) {
                $text = $row["text"];
                $id = $row["id"];
                $title = $row["title"];
                $uploader = "";
                $sql = "SELECT * FROM user WHERE id = '" . $row["userId"] . "';";
                if ($resultUser = $db->query($sql)) {
                    $array = $resultUser->fetch_array();
                    $uploader = $array["forename"] . " " . $array["name"];
                }
                $timestamp = strtotime($row["timestamp"]);
                $item = array(
                    "id" => $id,
                    "text" => utf8_encode($text),
                    "title" => utf8_encode($title),
                    "uploader" => utf8_encode($uploader),
                    "timestamp" => $timestamp
                );
                array_push($info, $item);
            }
            $jsonResult["success"] = true;
            $jsonResult["info"] = $info;
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting.";
        }
        return $jsonResult;
    }

     /**
     * Add dashboard entrie
     *
     * @param string    $sessionHash   Hash of user
     * @param string    $title         Title of entry
     * @param string    $text          Text of entry
     * 
     * @return array
     */
    public function addDashboardEntry($sessionHash, $title, $text)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include ("../etc/db.php");
        require_once ("../classes/user.php");
        $sessionHash = utf8_encode($db->real_escape_string($sessionHash));
        $text = utf8_decode($db->real_escape_string($text));
        $title = utf8_decode($db->real_escape_string($title));
        $user = new User();
        $userId = $user->getUserId($sessionHash);
        $isAdmin = $user->isAdmin($sessionHash);
        $isAdmin = $isAdmin["success"];
        if (! $isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }
        $sql = "INSERT INTO dashboard (text, userId,title)
        VALUES ('" . $text . "', '" . $userId . "', '" . $title . "'); ";
        if ($result = $db->query($sql)) {
            $jsonResult["success"] = true;
            $jsonResult["info"] = "Dashboard Updated.";
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data inserting (" . $db->error . ").";
        }
        return $jsonResult;
    }


    
     /**
     * Delete dashboard entrie
     *
     * @param string    $sessionHash   Hash of user
     * @param int   $dashId        Id of entry
     * 
     * @return array
     */
    public function deleteDashboardEntry($sessionHash, $dashId)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include ("../etc/db.php");
        require_once ("../classes/user.php");
        $sessionHash = utf8_encode($db->real_escape_string($sessionHash));
        $dashId = utf8_decode($db->real_escape_string($dashId));
        
        $user = new User();
        $isAdmin = $user->isAdmin($sessionHash);
        $isAdmin = $isAdmin["success"];
        if (! $isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }
        $sql = "DELETE FROM dashboard WHERE id = " . $dashId . "; ";
        if ($result = $db->query($sql)) {
            $jsonResult["success"] = true;
            $jsonResult["info"] = "Dashboard Entry deleted.";
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data inserting (" . $db->error . ").";
        }
        return $jsonResult;
    }
}
?>
