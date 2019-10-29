<?php

class Script
{
    public function getModulScript($id)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'scriptHtml' => null,
            'cutList' => null
        );
        include ("../etc/db.php");
        $id = $db->real_escape_string($id);
        $sql = "SELECT * FROM moduls WHERE id = '".$id."';";
        if($result = $db->query($sql)){
            $num_row = $result->num_rows;
            if($num_row == 1){
                $row = $result->fetch_array();
                $jsonResult["scriptPDF"] =utf8_decode($row["path"]);   
                $sql = "SELECT tt.cutId FROM ttModulCut tt, moduls m WHERE m.id = tt.modulId AND m.id = ".$id.";";
                
                if($result = $db->query($sql)){
                    
                    $cutList = array();
                    while($row = $result->fetch_array()){
                        array_push($cutList,$row["cutId"]);
                    }
                    $jsonResult["cutList"] = $cutList;
                    $jsonResult["success"] = true;
                    
                }else{
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                }
            }
        }else{
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            
        }
        
        return $jsonResult;
    }
    
    
    public function addCutToModul($sessionHash,$cutList,$modulId){
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include ("../etc/db.php");
        $modulId = $db->real_escape_string($modulId);
        $seesionHash = $db->real_escape_string($sessionHash);
        $cutList = $db->real_escape_string($cutList);
        if($cutList != -1){
        $cutList = explode(",",$cutList);
        }else{
            $cutList = array();
        }
        require_once ("../classes/user.php");
        $user = new User();

        $isAdmin = $user->isAdmin($sessionHash);
        $isAdmin = $isAdmin["success"];
        if (! $isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }
        $sql = "DELETE FROM ttModulCut WHERE modulId = '".$modulId."';";
        if($result = $db->query($sql)){
            foreach($cutList as $cutId){
                
                        $sql = "INSERT INTO ttModulCut (cutId,modulId) VALUES ('".$cutId."','".$modulId."')";
                        if($result = $db->query($sql)){
                            $jsonResult["success"] = true;
                            $jsonResult["error"] = "Schnitt wurde hinzugefügt.";
                        }else{
                            $jsonResult["success"] = false;
                            $jsonResult["error"] = "Error by data inserting (" . $db->error . ").";
                        }
                    
            }
        }else{
            $jsonResult["success"] = false;
                $jsonResult["error"] = "Error by data deleting (" . $db->error . ").";
        }
    }

    public function editModulPDF($sessionHash, $modulId, $pdf)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        
        include ("../etc/db.php");
        $sessionHash = utf8_encode($db->real_escape_string($sessionHash));
        $modulId = utf8_encode($db->real_escape_string($modulId));

        require_once ("../classes/user.php");
        $user = new User();
        $isAdmin = $user->isAdmin($sessionHash);
        $isAdmin = $isAdmin["success"];
        if (! $isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }
 

        $uploaddir = '/var/www/web0/html/files/moduls/';
        $uploadfile = $uploaddir . "modul".$modulId.".pdf";
        $modulPDF = "files/moduls/modul".$modulId.".pdf";
        if (move_uploaded_file($pdf['tmp_name'], $uploadfile)) {
            $sql = "UPDATE moduls SET path = '" . $modulPDF . "' WHERE id = '" . $modulId . "';";
            if ($result = $db->query($sql)) {
                
                $jsonResult["success"] = true;
                $jsonResult["info"] = "Modul Updated.";
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Error by data updateing (" . $db->error . ").";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Upload der PDF fehlgeschlagen!";
            echo "Upload der PDF fehlgeschlagen!";
        }
        
       
        return $jsonResult;
    }
}

?>
