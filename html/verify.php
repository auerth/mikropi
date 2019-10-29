<?php
    if(isset($_GET["hash"])){
        $hash = $_GET["hash"];
        include("../classes/user.php");
        $user = new User();
        if($hash != "" && $hash != null){
            $result = $user->verifyEmail($hash);
            if($result["success"]){
                header("Location: login.php?msg=email");
            }else{
                echo($result["error"]."<br> Bitte wenden Sie sich an den Support");
            }
        }else{
            
        }
    }
    header("Location: login.php");



?>