<!DOCTYPE html>
<html lang="de">
<?php
include("../classes/user.php");
include("../classes/pagebuilder.php");


$loggedIn = false;
$isAdmin = false;
$emailChangeInfo = null;
$newPasswordInfo = null;
$serverUrl = "https://mikropi.de/webapp/";
$user = new User();
$name = "";
$email = "";
$matrikelnummer = "";
$erstelltAm = "01.01.1970";
$pageBuilder = new PageBuilder();
$msg = null;
$error = null;
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
    setcookie("bugreport", "clicked", time() + 86400); 


    if (isset($_GET["sent"])) {
        $msg = "Nachricht wurde erfolgreicht versendet.";
    }
    if (isset($_GET["failedCaptcha"])) {
        $error = "Captcha ist nicht korrekt gelöst.";
    }
    if (isset($_GET["failed"])) {
        $error = "Fehler beim Senden der Nachricht.";
    }
}
if (!$loggedIn) {
    header("Location: login.php?redirect=report.php");
}
?>
<?php
/**
 * Get Header from PageBuilder Class
 * 
 * @see ../classes/pagebuilder.php
 */

$array = array("../css/login.css");
echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Als Student vom Institut für klinische Pathologie Freiburg kannst du hier Mikroskopschnitte schnell und einfach einsehen.", $array));
?>

<body>

    <!-- Navigation -->
    <?php
    echo ($pageBuilder->getNavBar($loggedIn, $isAdmin));
    ?>

    <!-- Page Content -->
    <main>
        <div class="card text-black">
            <div class="card-body">

                <h1 class="card-text text-center">Bug entdeckt?</h1>
                <p class="text-center text-black" style="color: black; font-size: 20px;">Mithilfe dieses Formulars kannst du uns über Fehler, die du in Mikropi entdeckt hast, benachrichtigen. Die Nachricht, die du hier versendest, geht direkte an den Administrator.</p>
                <?php if ($error != null) {
                    echo ('<div class="alert alert-danger">' . $error . '</div>');
                } else if ($msg != null) {
                    echo ('<div class="alert alert-success">' . $msg . '</div>');
                } ?>
                <div class="col-md-12 mb-md-0 mb-5 text" style="color: black;">

                    <form id="contact-form" name="contact-form" action="mail.php" method="POST">
                        <!--Grid row-->

                        <div class="row">

                            <div class="col-md-12">
                                <div class="md-form mb-0">

                                    <label for="name" class="">Betreff *</label>
                                    <input type="text" id="betreff" name="betreff" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <!--Grid row-->
                        <input type="hidden" id="email" name="email" value="<?php echo ($email) ?>">
                        <input type="hidden" id="name" name="name" value="<?php echo ($name) ?>">


                        <!--Grid row-->
                        <div class="row">

                            <!--Grid column-->
                            <div class="col-md-12">

                                <div class="md-form">

                                    <label for="message">Fehlerbeschreibung *</label>
                                    <textarea type="text" id="message" name="message" rows="2" maxlength="300" class="form-control md-textarea" required></textarea>
                                </div>

                            </div>
                        </div>

                        <center>
                            <br>
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="6LcTf70UAAAAAGGOlOjgmHts4Sr0LbAsdnsnk1wZ"></div>
                            </div>
                        </center>
                        <!--Grid row-->
                        <div class="text-center">
                            <button type="submit" class="btnSubmit" style="font-size: 15px; height: 50px; width: 200px;">Abschicken</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
        <?php

        echo ($pageBuilder->getFooter());

        ?>
    </main>

</body>
<!-- Bootstrap core JavaScript -->
<script src='https://www.google.com/recaptcha/api.js'></script>
<?php
if (isset($_GET["cuts"]) && $_GET["cuts"] > 0) {
    echo ($cut->getCutImage($_GET["cuts"]));
}

$array = array("../js/list.js");
echo ($pageBuilder->getJsTags($array));
?>



</html>