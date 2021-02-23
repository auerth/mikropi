<?php

/**
 * The Overlay Class - Add, Edit or Delete Overlays for Cuts 
 * @author     Thorben Auer
 * @link       https://softwelop.com
 */
class Overlay
{
    /**
     * Add Overlay to Cut
     *
     * @param int   $userId      User who adds the overlay
     * @param int   $cutId       Cut to which the overlay is added
     * @param string    $title       Name of Overlay
     * @param string    $location    X,Y of start of Overlay
     * @param string    $size        Widt,Height of Overlay
     * 
     * @return array
     */
    public function addOverlay($userId, $cutId, $title, $location, $size)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        include_once("../classes/user.php");

        $userId = $db->real_escape_string($userId);
        $cutId = $db->real_escape_string($cutId);
        $userId = $db->real_escape_string($userId);
        $title = $db->real_escape_string($title);
        $location = $db->real_escape_string($location);
        $size = $db->real_escape_string($size);
        $user = new User();
        $isAdmin = $user->isAdmin($userId);
        $isAdmin = $isAdmin["success"];
        if (!$isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }
        $location = explode(",", $location);
        $size = explode(",", $size);

        $sql = "INSERT INTO overlay (cutId,name,fromX,fromY,sizeX,sizeY) VALUES ('" . $cutId . "','" . $title . "','" . $location[0] . "','" . $location[1] . "','" . $size[0] . "','" . $size[1] . "');";
        if ($result = $db->query($sql)) {
            $jsonResult["success"] = true;
            $jsonResult["info"] = "Overlay added.";
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data INSERTING (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }
        return $jsonResult;
    }

    /**
     * Delete Overlay from Cut
     *
     * @param int   $userId          User who removes the overlay
     * @param int   $overlayId       Id of overlay to remove
     * 
     * @return array
     */
    public function deleteOverlay($userId, $overlayId)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        include_once("../classes/user.php");

        $userId = $db->real_escape_string($userId);
        $overlayId = $db->real_escape_string($overlayId);

        $user = new User();
        $isAdmin = $user->isAdmin($userId);
        $isAdmin = $isAdmin["success"];
        if (!$isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }

        $sql = "DELETE FROM overlay WHERE id='" . $overlayId . "';";
        if ($result = $db->query($sql)) {
            $jsonResult["success"] = true;
            $jsonResult["info"] = "Overlay deleted.";
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data deleting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }
        return $jsonResult;
    }
 /**
     * Edit Overlay Name
     *
     * @param int   $userId          User who edits the overlay
     * @param int   $overlayId       Id of overlay to edit
     * @param string   $title            New name of overlay   
     * 
     * @return array
     */
    public function editOverlay($userId, $overlayId, $title)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        include_once("../classes/user.php");

        $userId = $db->real_escape_string($userId);
        $overlayId = $db->real_escape_string($overlayId);
        $user = new User();
        $isAdmin = $user->isAdmin($userId);
        $isAdmin = $isAdmin["success"];
        if (!$isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }

        $sql = "UPDATE overlay SET name = '" . $title . "' WHERE id = '" . $overlayId . "'; ";
        if ($result = $db->query($sql)) {
            $jsonResult["success"] = true;
            $jsonResult["info"] = "Overlay edited.";
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data updateing (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }
        return $jsonResult;
    }
}
