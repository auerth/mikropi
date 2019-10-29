<?php

if(!empty($_FILES)) {
    $files = $_FILES['files'];
    
    for($i = 0; $i < count($files); $i++) {
        move_uploaded_file($files['tmp_name'], "../files/tmp/".$files['name']); //Upload the file
    }
}

?>