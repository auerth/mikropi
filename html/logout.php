<?php
setcookie("sessionHash", null, time()-3600);
setcookie("name", null, time()-3600); 
setcookie("isAdmin", null, time()-3600);
setcookie("userId", null, time()-3600); 
setcookie("matrikelnummer", null, time()-3600);
setcookie("creationDate", null, time()-3600); 
setcookie("loggedin_salt", null, time()-3600); 

header('Location: index.php');

?>
