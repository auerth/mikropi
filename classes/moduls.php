<?php

class Modul
{

    public function getModuls()
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );

        include("../etc/db.php");
        $sql = "SELECT id, name FROM moduls ORDER BY name ASC;";
        if ($result = $db->query($sql)) {
            $info = array();
            while ($row = $result->fetch_array()) {
                if ($row != null) {
                    $name = $row["name"];
                    $id = $row["id"];
                    $array = array(
                        "id" => $id,
                        "name" => utf8_decode($name)
                    );
                    array_push($info, $array);
                }
            }
            $jsonResult["success"] = true;
            $jsonResult["info"] = $info;
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
        }
        return $jsonResult;
    }

    public function addModul($sessionHash, $name)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );

        include("../etc/db.php");
        $sessionHash = utf8_encode($db->real_escape_string($sessionHash));
        $name = utf8_encode($db->real_escape_string($name));
        require_once("../classes/user.php");
        $user = new User();
        $isAdmin = $user->isAdmin($sessionHash);
        $isAdmin = $isAdmin["success"];
        if (!$isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }

        $sql = "SELECT * FROM moduls WHERE name like '" . $name . "';";
        if ($result = $db->query($sql)) {
            if ($result->num_rows == 0) {
                $sql = "INSERT INTO moduls (name)
        VALUES ('" . $name . "');";
                if ($result = $db->query($sql)) {

                    $jsonResult["success"] = true;
                    $jsonResult["info"] = "Modul added.";
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Error by data inserting (" . $db->error . ").";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Name already exists.";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
        }
        return $jsonResult;
    }

    public function deleteModul($sessionHash, $modulId)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );

        include("../etc/db.php");
        $sessionHash = utf8_encode($db->real_escape_string($sessionHash));
        $modulId = utf8_encode($db->real_escape_string($modulId));
        require_once("../classes/user.php");
        $user = new User();
        $isAdmin = $user->isAdmin($sessionHash);
        $isAdmin = $isAdmin["success"];
        if (!$isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }

        $sql = "DELETE FROM moduls WHERE id = '" . $modulId . "';";
        if ($result = $db->query($sql)) {
            $sql = "DELETE FROM ttModulCut WHERE modulId = '" . $modulId . "';";
            if ($result = $db->query($sql)) {
                $jsonResult["success"] = true;
                $jsonResult["info"] = "Modul deleted.";
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Error by data deleting (" . $db->error . ").";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data deleting (" . $db->error . ").";
        }
        return $jsonResult;
    }
}
