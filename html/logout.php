<?php
setcookie("sessionHash", null, time());
setcookie("name", null, time()); 
setcookie("isAdmin", null, time());
setcookie("userId", null, time()); 
setcookie("matrikelnummer", null, time());
setcookie("creationDate", null, time()); 
header('Location: index.php');

?>
