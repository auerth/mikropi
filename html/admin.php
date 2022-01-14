<!DOCTYPE html>
<html lang="de">
<?php


$classFiles = "../etc/classfiles.php";
include($classFiles);
include_once($file_user);
include($file_cut);
include($file_category);
include($file_modul);
include($file_pagebuilder);


$loggedIn = false;
$isAdmin = false;
$serverUrl = "https://mikropi.de/";
$userInfo = null;
$msg = null;
$categorys = null;
$alertType = null;
$user = new User();
$cut = new Cut();
$userList = null;
$category = new Category();
$modul = new Modul();
$pageBuilder = new PageBuilder();

$sessionHash = null;
if (isset($_COOKIE["sessionHash"]) && $_COOKIE["sessionHash"] != -1) {
    $loggedIn = true;
    if (isset($_COOKIE["isAdmin"])) {
        $isAdmin = $_COOKIE["isAdmin"];
    }
    if (!$isAdmin) {
        header("Location: index.php?dash");
    }
    $userId = $_COOKIE["sessionHash"];
    $sessionHash = $userId;
    $name = $_COOKIE["name"];
    $email = $_COOKIE["email"];
    $matrikelnummer = $_COOKIE["matrikelnummer"];
    $erstelltAm = $_COOKIE["creationDate"];

    if (isset($_POST["userId"])) {
        $active = false;
        $admin = false;
        if (isset($_POST["active"])) {
            $active = true;
        }
        if (isset($_POST["admin"])) {
            $admin = true;
        }
        $result = $user->updateUser($userId, $active, $_POST["userId"], $admin);
        if (!$result["success"]) {
            $msg = $result["error"];
            $alertType = "alert-danger";
        } else {
            $msg = $result["info"];
            $alertType = "alert-success";
        }
    }

    if (isset($_POST["deleteUser"]) && isset($_POST["userId"])) {


        $result = $user->deleteUserByAdmin($_COOKIE["sessionHash"], $_POST["userId"]);
        if (!$result["success"]) {
            $msg = $result["error"];
            $alertType = "alert-danger";
        } else {
            $msg = $result["info"];
            $alertType = "alert-success";
        }
    }
    if (isset($_FILES['csvFile'])) {
        $file = file_get_contents($_FILES['csvFile']["tmp_name"]);
        $matrikelnumbers = explode("\n", $file);
        foreach ($matrikelnumbers as $matrikelnumber) {
            $numbers = explode(";", $matrikelnumber);
            foreach ($numbers as $number) {
                $result = $user->addMatrikelnumber($_COOKIE["sessionHash"], $number);
                if (!$result["success"]) {
                    $alertType = "alert-danger";
                    $msg = $result["error"];
                    break;
                }
                $alertType = "alert-success";
                $msg = "Immatrikulationsnummern hinzugefügt";
            }
        }
    }
    if (isset($_POST["category"]) && isset($_POST["newCategory"])) {

        $result = $category->addCategory($sessionHash, $_POST["category"], $_POST["newCategory"]);
        if (!$result["success"]) {
            $msg = $result["error"];
            $alertType = "alert-danger";
        } else {
            $msg = $result["info"];
            $alertType = "alert-success";
        }
    }
    if (isset($_POST["categoryName"]) && isset($_POST["categoryId"])) {
        $result = $category->deleteCategory($sessionHash, $_POST["categoryName"], $_POST["categoryId"]);
        if (!$result["success"]) {
            $msg = $result["error"];
            $alertType = "alert-danger";
        } else {
            $msg = $result["info"];
            $alertType = "alert-success";
        }
    }
    if (isset($_POST["modulId"])) {
        $result = $modul->deleteModul($sessionHash, $_POST["modulId"]);
        if (!$result["success"]) {
            $msg = $result["error"];
            $alertType = "alert-danger";
        } else {
            $msg = $result["info"];
            $alertType = "alert-success";
        }
    }
    $sortBy = "name";
    if (isset($_POST["sortBy"])) {
        $sortBy = $_POST["sortBy"];
    }
    $nameChecked = "";
    $forenameChecked = "";
    $adminChecked = "";
    $activeChecked = "";
    $emailChecked  = "";
    $matrikelChecked = "";
    switch ($sortBy) {
        case "name":
            $nameChecked = "checked";
            break;
        case "forename":
            $forenameChecked = "checked";
            break;
        case "admin":
            $adminChecked = "checked";
            break;
        case "activated":
            $activeChecked = "checked";
            break;
        case "email":
            $emailChecked = "checked";
            break;
        case "matrikelnummer":
            $matrikelChecked = "checked";
            break;
    }

    $userList = $user->getUserList($userId, $sortBy);
    if (isset($_POST["checkCuts"])) {
        $result = $cut->checkForCuts($_POST["checkCuts"]);
        if ($result["success"]) {
            $msg = $result["info"] . " Einträge wurden hinzugefügt.";
            $alertType = "alert-success";
        } else {
            $msg = $result["error"];
            $alertType = "alert-danger";
        }
    }
    if (isset($_POST["modulName"])) {
        $result = $modul->addModul($_COOKIE["sessionHash"], $_POST["modulName"]);
        if ($result["success"]) {
            $msg = "Modul wurden hinzugefügt.";
            $alertType = "alert-success";
        } else {
            $msg = $result["error"];
            $alertType = "alert-danger";
        }
    }

    $result = $category->getCategorys();
    if ($result["errorCode"] == null) {
        $categorys = $result["info"];
    }
}
if (!$isAdmin || !$loggedIn) {
    header("Location: login.php?redirect=admin.php");
}
?>
<?php
echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Als Student vom Institut für klinische Pathologie Freiburg kannst du hier Mikroskopschnitte schnell und einfach einsehen."));
?>

<body>

    <!-- Navigation -->
    <?php
    echo ($pageBuilder->getNavBar($loggedIn, $isAdmin));
    ?>

    <!-- Page Content -->
    <main>
        <?php
        if ($msg != null) {
            echo ('<div class="alert ' . $alertType . '" style="margin-bottom: 0px;">' . $msg . '
                      </div>');
        }
        ?>
        <div class="card">
            <h5 class="card-header bg-2nd text-white">Benutzer Liste</h5>
            <div class="card-body">
                <form>
                    <h4>Sortieren nach: </h4>
                    <fieldset>
                        <input type="radio" id="name" onclick="sort('name')" name="filter" value="Name" <?php echo $nameChecked; ?>>
                        <label for="name"> Name</label>
                        <input type="radio" id="vorname" onclick="sort('forename')" name="filter" value="Vorname" <?php echo $forenameChecked; ?>>
                        <label for="vorname"> Vorname</label>
                        <input type="radio" id="email" onclick="sort('email')" name="filter" value="Email" <?php echo $emailChecked; ?>>
                        <label for="email"> Email</label>
                        <input type="radio" id="admin" onclick="sort('admin')" name="filter" value="Admin" <?php echo $adminChecked; ?>>
                        <label for="admin"> Admin</label>
                        <input type="radio" id="active" onclick="sort('activated')" name="filter" value="Aktiviert" <?php echo $activeChecked; ?>>
                        <label for="active"> Aktiviert</label>
                        <input type="radio" id="immatrikulationsnummer" onclick="sort('matrikelnummer')" name="filter" value="Immatrikulationsnummer" <?php echo $matrikelChecked; ?>>
                        <label for="immatrikulationsnummer"> Immatrikulationsnummer</label>
                    </fieldset>
                </form>
                <input type="text" id="searchUser" name="name" class="form-control" placeholder="Suche (Email, Immatrikulationsnummer oder Name)">
                <div class="list" style="height: 600px; margin-top: 5px;" id="userListe">
                    <?php
                    if ($userList["success"]) {
                        $userList = $userList["info"];
                        $checkBoxId = 0;
                        foreach ($userList as $user) {
                            $activatedChecked = "";
                            $adminChecked = "";
                            $emailVerifyed = '<i class="fas fa-times ignoreCursor" style="margin: 0px; color: red;"></i>';
                            if ($user["verifyed"]) {
                                $emailVerifyed = '<i class="fas fa-check ignoreCursor" style="margin: 0px; color: green;"></i>';
                            }
                            if ($user["activated"]) {
                                $activatedChecked = "checked";
                            }
                            if ($user["admin"]) {
                                $adminChecked = "checked";
                            }
                            echo ('
                                    <div class="userMedia">
                                    
                                    <details>
                                             
                                    <summary>' . $user["name"] . ", " . $user["forename"] . '
                                                </summary>
                                    <div class="row">
                                    <div class="information">
                                        <div class="element">
                                                <p class="filterMatrikel">Matrikelnummer: ' . $user["matrikelnummer"] . '</p>
                                        </div>
                                        <div class="element" >
                                                <p>Email: <a href="mailto: ' . $user["email"] . '">' . $user["email"] . '</a></p>
                                        </div>
                                        <div class="element">
                                            <p>Letzter Login: ' . date('d.m.Y H:i', $user["last_login"]) . ' Uhr</p>
                                        </div>
                                        <div class="element">
                                            <p>Email verifiziert: ' .  $emailVerifyed . '</p>
                                        </div>
                                        <div class="element">
                                            <p>Erstellt am: ' .  date('d.m.Y', $user["created"]) . '</p>
                                        </div>
                                    </div>

                                    <div class="adminstration">
                                            <form method="POST" style="float: right;"action="admin.php">
                                                <input name="userId" value="' . $user["id"] . '" style="display: none;">');
                            if (isset($_POST["sortBy"])) {
                                echo ('<input name="sortBy" value="' . $_POST["sortBy"] . '" style="display: none;">');
                            }
                            echo ('
                                                    <div class="row">
                                                    
                                                    <div class="form-check">
                                                        <input type="checkbox" id="checkboxi-' . $checkBoxId . '" class="form-check-input" name="active" ' . $activatedChecked . ' >
                                                        <label class="form-check-label" onclick="check(`checkboxi-' . $checkBoxId . '`)" for="exampleCheck1"><span></span>Aktiv</label>
                                                        <input type="checkbox" id="checkbox-' . $checkBoxId . '" class="form-check-input" name="admin" ' . $adminChecked . '>
                                                        <label class="form-check-label" onclick="check(`checkbox-' . $checkBoxId . '`)" for="exampleCheck1"><span></span>Admin</label>
                                                    </div>
                                                    <div class="form-check"  style="float: right;">
                                                        <i onclick="deleteUser(' . $user["id"] . ')" class="fas fa-trash-alt"></i>
                                                    </div>
                                                    </div>
                                                    <div class="form-check">
                                                        <button type="submit"  class="btn btn-primary" >Speichern</button>
                                                    </div>

                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                    </details>');
                            $checkBoxId++;
                        }
                    }
                    ?>

                </div>
            </div>
        </div>

        <div class="card">
            <h5 class="card-header bg-2nd text-white">Schnitte</h5>
            <div class="card-body">

                <p>Es sind aktuelle <?php echo ($cut->countCuts()); ?> Schnitte in der Datenbank</p>

            </div>
        </div>
        <div class="card">
            <h5 class="card-header bg-2nd text-white">Immatrikulationsnummern</h5>
            <div class="card-body">

                <form enctype="multipart/form-data" action="admin.php" method="POST">
                    <!-- MAX_FILE_SIZE muss vor dem Dateiupload Input Feld stehen -->
                    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                    <!-- Der Name des Input Felds bestimmt den Namen im $_FILES Array -->
                    CSV hochladen: <input name="csvFile" type="file" />
                    <button type="submit" class="btn btn-primary">Hochladen und
                        Hinzufügen</button>

                </form>

            </div>
        </div>

        <div class="card">
            <h5 class="card-header bg-2nd text-white">Filter</h5>
            <div class="card-body">

                <div class="filter">
                    <?php
                    while ($fruit_name = current($categorys)) {
                        $catName = key($categorys);
                        switch (key($categorys)) {
                            case "lecturer":
                                $catName = "Dozent";
                                break;
                            case "organgroup":
                                $catName = "organgruppe";
                                break;
                            case "diagnosisgroup":
                                $catName = "Diagnosegruppe";
                                break;
                        }
                        echo ('<div class="dropdown filter-dropdown">
																			<p>' . ucfirst($catName) . ':</p><button type="button" id="' . key($categorys) . '" value="-1" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">- - </button>
																			<ul class="dropdown-menu">
																		');
                        foreach ($categorys[key($categorys)] as $item) {
                            if ($item["name"] != "Alle")
                                echo ('<li><a id="' . $item["id"] . '" class="dropdown-item"><i style="color: #0062cc; margin-right:5px;" id="' . $item["id"] . '" class="fas fa-trash-alt" ></i>' . $item["name"] . '</a></li>');
                        }
                        echo ('<li><a id="-2" class="dropdown-item">[Hinzufügen]</a></li>');

                        echo ('</ul>
																			  </div>
																			  ');

                        next($categorys);
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="card">
            <h5 class="card-header bg-2nd text-white">Module</h5>
            <div class="card-body">
                <form action="admin.php" method="POST" style="width: 100%">

                    <div class="row" style="margin-bottom: 10px;">

                        <div class="col-8">
                            <input type="text" id="addModul" name="modulName" class="form-control" style="margin: 0px;" placeholder="Neues Modul" value="" />

                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-primary" style="margin: 0px;">+</button>
                        </div>

                    </div>
                </form>

                <div class="list" style="height: 350px;" id="modulList">
                    <?php
                    $moduls = $modul->getModuls();
                    $moduls = $moduls["info"];
                    foreach ($moduls as $item) {
                        echo ('<div class="userMedia"><div class="row"><div class="col-11">');
                        echo ($item["name"]);
                        echo ('</div><div class="col-1"><i style="float: right; margin-left: auto; margin-top: 4px;" onclick="deleteModul(' . $item["id"] . ')" class="fas fa-trash-alt"></i></div></div></div>');
                    }

                    ?>

                </div>

            </div>
        </div>
        <div class="card">
            <h5 class="card-header bg-2nd text-white">Schnitt hochladen (Tiff / Tif) <span id="working"> - Tiff Dateien werden konvertiert . . .</span></h5>
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="file" id="files" name="files[]" multiple="multiple" accept=".tiff,.tif" /><br /><br />
                    <button class="btn btn-primary" type="submit" id="upload" name="upload" value="Upload">Hochladen</button><span style="color: red;" id="errormsg"></span><br />
                    <!-- Progress will be shown here -->
                    <div id="uploadlist" class="progress"></div>
                </form>
            </div>
        </div>
        <div class="card">
            <h5 class="card-header bg-2nd text-white">Log-Files</h5>
            <div class="card-body">
                <form>
                    <h4>Log-Files:</h4>
                    <fieldset>
                        <input type="radio" id="worklist" onclick="loadLog('worklist.txt')" name="filter" value="Worklist" checked>
                        <label for="worklist"> Worklist-Log</label>
                        <input type="radio" id="hash" onclick="loadLog('hash.txt')" name="filter" value="Hash">
                        <label for="hash"> Hash-Log</label>
                        <input type="radio" id="user" onclick="loadLog('user.txt')" name="filter" value="User">
                        <label for="user"> User-Log</label>
                        <input type="radio" id="cuts" onclick="loadLog('cuts.txt')" name="filter" value="Schnitte">
                        <label for="cuts"> Schnitte-Log</label>
                    </fieldset>
                </form>
                <textarea id="log" readonly></textarea>
            </div>
        </div>


        <div id="modalCategory" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <div class="row">
                    <h3>Filter Name:</h3>
                    <span class="close">&times;</span>
                </div>
                <form method="POST" action="admin.php">
                    <input id="categoryName" name="category" type="text" style="display: none;" value="" />
                    <div class="form-group">
                        <input name="newCategory" id="newCategory" type="text" class="form-control" placeholder="Filter Name" />
                    </div>
                    <button type="submit" style="margin-left: 10px;" class="btn btn-primary">Hinzuf&uuml;gen</button>

                </form>
            </div>
        </div>
        <?php

        echo ($pageBuilder->getFooter());

        ?>
    </main>

</body>


<?php
$array = array("../js/admin.js", "../js/filter.js", "../js/log.js");
echo ($pageBuilder->getJsTags($array));

?>







</html>