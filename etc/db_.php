<?php
// Create connection remove "_" from name
$db = new mysqli("host", "username", "passwort","database");
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
