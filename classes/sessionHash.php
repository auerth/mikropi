<?php

class SessionHash{
	

	public function checkHash($hash){
		$jsonResult = array(
			'success' => false,
			'errorCode' => 0,
			'error' => null,
			'info'=> null		
		);
		
		include ("../etc/db.php");
		$hash = $db->real_escape_string($hash);
		
		$sql = "SELECT * FROM hash WHERE hash like '".$hash."';";
		if($result = $db->query($sql)){
			$row_cnt = $result->num_rows;
			if($row_cnt >= 1){
				$jsonResult["success"] = true;
				$jsonResult["info"] = "";
			}else{
				$jsonResult["success"] = false;
				$jsonResult["error"] = "No session found you need to login.";
				$jsonResult["errorCode"] = "3";	
			}
		}else{
			$jsonResult["success"] = false;
			$jsonResult["error"] = "Error by data selecting (Request error).";
			$jsonResult["errorCode"] = "1";	
		}
		return $jsonResult;
	}
	
	public function createHash($userId,$db){
	    
	    $jsonResult = array(
	        'success' => false,
	        'errorCode' => 0,
	        'error' => null,
	        'info'=> null
	    );
	    include("../etc/db.php");
	    $userId = $db->real_escape_string($userId);
	    
	    $hash = base64_encode(bin2hex(openssl_random_pseudo_bytes (16)));
	    $sql = "INSERT INTO hash (userId, hash)
						VALUES ('".$userId."', '".$hash."'); ";
	    if($result = $db->query($sql)){
	        $jsonResult["success"] = true;
	        $jsonResult["info"] = $hash;
	    }else{
	        $jsonResult["success"] = false;
	        $jsonResult["error"] = "Error by creating data (".$db->error.").";
	        $jsonResult["errorCode"] = "1";
	    }
	    return $jsonResult;
	}
	
	public function clearHashs(){
		
		include ("../etc/db.php");
		$sql = "SELECT * FROM hash;";
		if($result = $db->query($sql)){
			while ($row = $result->fetch_array()){
				$date = $row["timestamp"];
				if((time() - strtotime($date)) >= (30240 * 60)){
					$sql = "DELETE FROM hash WHERE id = '".$row["id"]."';";
					
					$db->query($sql);
				}
			}
		}
	}
	
	
}


?>