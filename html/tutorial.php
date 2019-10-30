<!DOCTYPE html>
<html lang="de">
<?php


$classFiles = "../etc/classfiles.php";
include($classFiles);

include($file_pagebuilder);
$loggedIn = false;
$isAdmin = false;
$pageBuilder = new PageBuilder();

if (isset($_COOKIE["sessionHash"]) && $_COOKIE["sessionHash"] != -1) {
    $loggedIn = true;
    if (isset($_COOKIE["isAdmin"])) {
        $isAdmin = $_COOKIE["isAdmin"];
    }
    $userId = $_COOKIE["sessionHash"];
    $name = $_COOKIE["name"];
    $email = $_COOKIE["email"];
    $matrikelnummer = $_COOKIE["matrikelnummer"];
    $erstelltAm = $_COOKIE["creationDate"];
}
if (!$isAdmin ||!$loggedIn) {
    header("Location: login.php?redirect=tutorial.php");
}
?>

<?php
/**
 * Get Header from PageBuilder Class
 * 
 * @see ../classes/pagebuilder.php
 */
echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Als Student vom Institut für klinische Pathologie Freiburg kannst du hier Mikroskopschnitte schnell und einfach einsehen."));
?>

<body>

    <!-- Navigation -->
    <?php
    echo ($pageBuilder->getNavBar($loggedIn, $isAdmin));
    ?>

    <!-- Page Content -->
    <main style="align-content: center; text-align: center;">
        
        <h1>Name und Beschreibung von Schnitten ändern.</h1>
        <video width="70%" controls>
            <source src="files/tutorial/Schnittname_Beschreibung_x264.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <br>
        <br>
        <h1>Makierungen auf Schnitten.</h1>
        <video width="70%" controls>
            <source src="files/tutorial/Neue_Makierung_x264.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <br>
        <br>
        <h1>Einem Schnitt Filter zuweisen.</h1>
        <video width="70%" controls>
            <source src="files/tutorial/Schnitte_Filter_zuweisen_x264.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <br>
        <br>
        <h1>Neues Modul hinzufügen.</h1>
        <video width="70%" controls>
            <source src="files/tutorial/Modul_hinzufuegen_x264.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <br>
        <br>
        <h1>PDF für Modul ändern.</h1>
        <video width="70%" controls>
            <source src="files/tutorial/Modul_PDF_aendern_x264.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <br>
        <br>
        <h1>Schnitte einem Modul zuweisen.</h1>
        <video width="70%" controls>
            <source src="files/tutorial/Modul_Schnitte_zuweisen_x264.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <br>
        <br>
        <h1>Immatrikulationsnummern in Datenbank laden. <a href="files/tutorial/Matrikelnummern_Beispiel.csv">Beispiel Datei</a></h1>
        <video width="70%" controls>
            <source src="files/tutorial/Matrikelnummern_x264.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <br>
        <br>
        <h1>Filter hinzufügen.</h1>
        <video width="70%" controls>
            <source src="files/tutorial/Filter_hinzufuegen_x264.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <br>
        <br>
        <h1>Benuzter aktivieren/deaktiveren und Admin Rechte.</h1>
        <video width="70%" controls>
            <source src="files/tutorial/Benutzer_Administrieren_x264.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <br>
        <br>
        <?php

        echo ($pageBuilder->getFooter());

        ?>
    </main>

</body>
<!-- Bootstrap core JavaScript -->
<?php

echo ($pageBuilder->getJsTags());
?>




</html>