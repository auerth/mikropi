<?php
$file_cut = "../classes/cut.php";
$file_category = "../classes/category.php";
$file_overlay = "../classes/overlay.php";
$file_dashboard = "../classes/dashboard.php";
$file_pagebuilder = "../classes/pagebuilder.php";

//check if files exists
if (file_exists($file_cut) && file_exists($file_category) && file_exists($file_overlay) && file_exists($file_dashboard) && file_exists($file_pagebuilder)) {
    include($file_cut);
    include($file_category);
    include($file_overlay);
    include($file_dashboard);
    include($file_pagebuilder);
    $loggedIn = false;
    $isAdmin = false;
    $serverUrl = "";
    $cuts = null;
    $categorys = null;
    $cutId = null;
    $cutMessage = null;
    $dashboardMessage = null;
    $overlayMessage = null;
    $name = "";
    $dashboard = new Dashboard();
    $overlay = new Overlay();
    $pageBuilder = new PageBuilder();
    $overlaysVisible = false;
    $cutCategorys = null;
    $categorysByCut = null;

    //Add Category to Cut
    if (isset($_POST["filterid"]) && isset($_POST["cutid"]) && isset($_POST["hash"])) {
        $hash = $_POST["hash"];
        $filterId = $_POST["filterid"];
        $cutId = $_POST["cutid"];
        $category = new Category();
        $result = $category->putCategory($hash, $cutId, $filterId);
        die(json_encode($result));
    }

    if ((!isset($_COOKIE["sessionHash"]) || $_COOKIE["sessionHash"] == -1) &&  isset($_COOKIE["loggedin_salt"])) {
        header("Location: login.php");
    }

    //If sessionHash is set user is logged in
    if (isset($_COOKIE["sessionHash"]) && $_COOKIE["sessionHash"] != -1) {
        $loggedIn = true;
        //Check if is admin
        if (isset($_COOKIE["isAdmin"])) {
            $isAdmin = $_COOKIE["isAdmin"];
        }
        if (isset($_COOKIE["name"])) {
            $name = $_COOKIE["name"];
        }
        $cut = new Cut();
        $category = new Category();
        $result = $category->getCategorys();
        if ($result["errorCode"] == null) {
            $categorys = $result["info"];

            $result = $cut->getCutsFiltered(-1, -1, -1, -1, -1, -1, -1, -1);
            $result = json_decode($result, true);
            if ($result["errorCode"] == null) {
                $cuts = $result["info"];
            } else {
                $error = $result["error"];
            }
        } else {
            $error = $result["error"];
        }
        //if get parameter has value load cut informations
        if (isset($_GET["id"]) && $_GET["id"] != null) {
            $category = new Category();
            $result = $category->getCategoryOfCut($_COOKIE["sessionHash"], $_GET["id"]);
            if ($result["success"]) {
                $cutCategorys = $result["info"];
            } else {
                $error = $result["error"];
            }
            $result = $category->getAllCategorysFromCut($_GET["id"]);
            if ($result["success"]) {
                $categorysByCut = $result["info"];
            } else {
                $error = $result["error"];
            }
        }
        //change title of cut
        if (isset($_POST["cutId"]) && isset($_POST["newTitle"]) &&  $isAdmin) {
            $isPrivate = 1;

            if (!isset($_POST["isPrivate"])) {
                $isPrivate = 0;
            }

            $result = $cut->updateCutName($_COOKIE["sessionHash"], $_POST["cutId"], $_POST["newTitle"], $isPrivate);
            if (!$result["success"]) {
                $cutMessage = $result["error"];
                $alertType = "alert-danger";
            }
            //change description of cut
        } else if (isset($_POST["cutId"]) && isset($_POST["newDescription"]) && $isAdmin) {
            $result = $cut->updateCutDescription($_COOKIE["sessionHash"], $_POST["cutId"], $_POST["newDescription"]);
            if (!$result["success"]) {
                $cutMessage = $result["error"];
                $alertType = "alert-danger";
            }
            //delte cut
        } else if (isset($_POST["cutId"]) && isset($_POST["deleteCut"]) && $isAdmin) {
            $result = $cut->deleteCut($_COOKIE["sessionHash"], $_POST["cutId"]);
            if (!$result["success"]) {
                $cutMessage = $result["error"];
                $alertType = "alert-danger";
            } else {
                $cutMessage = $result["info"];
                $alertType = "alert-success";
                header("Location: index.php");
            }
            //change overlay name    
        } else if (isset($_POST["overlayId"]) && isset($_POST["newOverlay"]) && $isAdmin) {
            $result = $overlay->editOverlay($_COOKIE["sessionHash"], $_POST["overlayId"], $_POST["newOverlay"]);
            if (!$result["success"]) {
                $overlayMessage = $result["error"];
                $alertType = "alert-danger";
            }
            $overlaysVisible = true;
        }
        //Add Dashboard entry
        if (isset($_POST["title"]) && isset($_POST["text"]) && $isAdmin) {
            $result = $dashboard->addDashboardEntry($_COOKIE["sessionHash"], $_POST["title"], $_POST["text"]);
            if (!$result["success"]) {
                $dashboardMessage = $result["error"];
                $alertType = "alert-danger";
            }
        }
        //Add new overlay
        if (isset($_POST["title"]) && isset($_GET["id"]) && isset($_POST["location"]) && isset($_POST["size"]) && $isAdmin) {
            $result = $overlay->addOverlay($_COOKIE["sessionHash"], $_GET["id"], $_POST["title"], $_POST["location"], $_POST["size"]);
            if (!$result["success"]) {
                $overlayMessage = $result["error"];
                $alertType = "alert-danger";
            }
            $overlaysVisible = true;
        }
        //Delete existing Overlay
        if (isset($_POST["overlayId"]) && isset($_GET["id"]) && $isAdmin && !isset($_POST["newOverlay"])) {
            $overlay = new Overlay();
            $result = $overlay->deleteOverlay($_COOKIE["sessionHash"], $_POST["overlayId"]);
            if (!$result["success"]) {
                $overlayMessage = $result["error"];
                $alertType = "alert-danger";
            }
            $overlaysVisible = true;
        }
        //Delete dashboard entry
        if (isset($_POST["dashId"]) && $isAdmin) {
            $result = $dashboard->deleteDashboardEntry($_COOKIE["sessionHash"], $_POST["dashId"]);
            if (!$result["success"]) {
                $dashboardMessage = $result["error"];
                $alertType = "alert-danger";
            }
        }
    } else {
        //Not LoggedIn
        $loggedIn = false;
    }
} else {
    //Some Class files are missing
    die("System Error! Support: admin@mikropi.de");
}
?>
<!DOCTYPE html>
<html lang="de">
<?php
//Load header of page with title and description an notifaction stylesheet
echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Als Student vom Institut für klinische Pathologie Freiburg kannst du hier Mikroskopschnitte schnell und einfach einsehen.", array("../css/notification.css")));
?>

<body>
    <!-- Navigation Bar -->
    <?php
    echo ($pageBuilder->getNavBar($loggedIn, $isAdmin));
    ?>

    <!-- Page Content -->
    <main>
        <?php
        if ($loggedIn) {
            //Admin components
            $editTitle = "";
            $deleteCut = "";
            $editDescription = "";
            $editFilter = "";
            $deleteOverlay = "";
            $editOverlay = "";

            //check if overlay should be shown
            if (isset($_GET['noOverlay'])) {
                $editOverlays = '<input type="checkbox">
                    <label  onclick="annos(' . $_GET["id"] . ')" >
                        <span></span>Annotationen zeigen
                    </label>';
            } else {
                $editOverlays = '
                <input type="checkbox" checked>
                <label onclick="noAnnos(' . $_GET["id"] . ')">
                    <span></span>Annotationen zeigen
                </label>';
            }
            $cut = new Cut();
            //load cut information
            $cutId = $_GET["id"];
            $cutInfo = $cut->getCutInfo($_GET["id"]);
            //check if user should see admin components
            if ($isAdmin) {
                $editTitle = "<i class='fas fa-pencil-alt fa-1x' id='editTitle'></i>";
                $deleteCut = "<i class='fas fa-trash-alt fa-1x right' onclick='deleteCut(" . $_GET["id"] . ")'></i>";
                $editDescription = "<i class='fas fa-pencil-alt fa-1x' id='editDescription'></i>";
                $editOverlays .= "<button type='submit' id='addOverlay' onclick='drawOverlay()' style='margin-left:auto; float: right;' class='btn btn-primary' >+</button>";
                $editFilter = '<button type="submit" id="editFilter" style="margin-left:20px;" class="btn btn-primary" >Filter bearbeiten</button>';
                $private = $cutInfo["info"]["isPrivate"] == 0 ? '' : '<div class="flex" style="display: flex;padding: 0 30px; align-items: center;"><h4 style="color: white;">Dieser Schnitt ist nicht für Studenten sichtbar!</h4></div>';
            }



            //build cut page

            //sidebar
            $overlays = "<ul>";
            foreach ($cutInfo["info"]["overlays"] as $overlay) {
                if ($isAdmin) {
                    $deleteOverlay = "<i style='color: var(--primary);margin-left: auto; margin-right:0px; padding:0px; float: right; height:auto; width: auto;' onclick='deleteOverlay(" . $overlay["id"] . ")' class='fas fa-trash-alt'></i>";
                    $editOverlay = "<i style='color: var(--primary); margin-left: 2px;height: auto; width: auto;' class='editOverlayName fas fa-pencil-alt fa-1x' id='editOverlayName' name='" . $overlay["id"] . "'></i>";
                }
                $overlays = $overlays . "<li id='" . $overlay["id"] . "'>" . $overlay["name"] . $editOverlay . $deleteOverlay . "</li>";
                $deleteOverlay = "";
            }
            $overlays = $overlays . "</ul>";
            if ($cutMessage != null) {
                echo ('<div class="alert ' . $alertType . '">' . $cutMessage . '
								</div>');
            }
            if ($overlayMessage != null) {
                echo ('<div class="alert ' . $alertType . '">' . $overlayMessage . '
								</div>');
            }

            echo ("<div class='wrapper'><div class='row bg-second'><h1 id='title' >" . $cutInfo["info"]["name"] . "</h1>" . $editTitle . $editFilter . $private . $deleteCut . "</div>");
            echo ('<div class="flexbox" style="flex-wrap: nowrap;">');
            $overlayDiv = "<div class='overlays' style='display: none;' id='overlay'>";
            $overlayDivM = "<div class='overlaysM' style='display: none;' id='overlayM'>";

            if ($overlaysVisible) {
                echo ('<div class="description" id="description" style="display: none">');
                $overlayDiv = "<div class='overlays' id='overlay'>";
            } else {
                echo ('<div class="description" id="description">');
            }
            echo ("<div id='description-text'>" . $cutInfo["info"]["description"] . "</div>" . $editDescription . "</li></div>
        " . $overlayDiv . "<div class='row'>" . $editOverlays . "</div>" . $overlays . "
        </div><ul class='cutbar'><li> <i class='fa-solid fa-chevron-left fa-2x bg-main' id='hide'></i></li><li><i class='fas fa-align-left fa-2x bg-main' id='itemDescription'></i></li><li><i id='itemOverlay' class='fas fa-flag fa-2x bg-main'></i></li></ul>");
            echo ("<div class='cutfile'>");

            //openseadragon element (cut)
            echo ('<div id="toolbarDiv" class="toolbar" style="width: 100%; height: 2%;">
                <div class="row">
		                <a id="zoom-in" class="icon" href="#"><img class="iconimg" src="../js/openseadragon/images/plus.png"/></a>
		                <a id="zoom-out" class="icon" href="#"><img class="iconimg" src="../js/openseadragon/images/minus.png"/></a>
		                <a id="home" class="icon" href="#"><img class="iconimg" src="../js/openseadragon/images/home.png"/></a>
                        <a id="fullpage" class="icon" href="#"><img class="iconimg" src="../js/openseadragon/images/fullpage.png"/></a>
                        <a id="location" class="icon" href="#"><img class="iconimg" src="../js/openseadragon/images/location.png"/></a>
                        <a id="zoom25" class="icon" href="#"><img class="iconimg" src="../js/openseadragon/images/10x.png"/></a>
                        <a id="zoom50" class="icon" href="#"><img class="iconimg" src="../js/openseadragon/images/40x.png"/></a>
                        <a id="zoom75" class="icon" href="#"><img class="iconimg" src="../js/openseadragon/images/80x.png"/></a>
                        <a id="zoom100" class="icon" href="#"><img class="iconimg" src="../js/openseadragon/images/100x.png"/></a></div>
                    </div>
                  
                        <div class="openseadragon" id="openseadragon1"><div class="viewerdetails">
                        <p>Zoom Level: <span id="zoomlevel"></span></p>
                        <p>X-Position: <span id="coordinateX"></span></p>
                        <p>Y-Position: <span id="coordinateY"></span></p>
                        <p style="font-size: 11px;">Mit einem Rechtsklick auf den Objektträger wird die Koordinate in die Zwischenablage kopiert</p>
                        
                    </div> </div></div></div>');
            echo ("<div class='mobilebar'>");
            echo ("<div class='row'>
                <i class='fas fa-align-left fa-2x bg-main' id='itemDescriptionM'></i>
                <i id='itemOverlayM' class='fas fa-flag fa-2x bg-main'></i>
                </div>");
            echo ("<div class='descriptionM'>");
            echo ("<div id='description-text'>" . $cutInfo["info"]["description"] . "</div>" . $editDescription . "</li></div>
                " . $overlayDivM . "<div class='row'>" . $editOverlays . "</div>" . $overlays . "
                </div>");
            echo ("</div>");
            echo ("<div id='snackbar'>Koordinate wurden in die Zwischenablage kopiert</div>");
            //Create Modals for editing if user is admin
            if ($isAdmin) {
                $isPrivate = $cutInfo["info"]["isPrivate"] == 0 ? "" : "checked";

                echo (' <div id="modalTitle" class="modal">
Test
  <!-- Modal content -->
  <div class="modal-content">
    <div class="row"><h3>Name ändern:</h3><span class="close">&times;</span></div>
	   <form method="POST" action="cut.php?id=' . $cutId . '">
	   <input  name="cutId" type="text" style="display: none;" value="' . $cutId . '"/>
        <div class="form-group">
            <input name="newTitle" id="newTitle" type="text" class="form-control" placeholder="Name eingeben"/>
        </div>
        <div class="row" style="padding: 0; display: flex; gap: 20px; align-items: center;">
        <button type="submit" style="margin-left:10px;" class="btn btn-primary" >Speichern</button>

        <div class="form-check" style="margin: 0;">

        <input type="checkbox" id="isPrivate" class="form-check-input" name="isPrivate" style="display: unset;" ' . $isPrivate . '>
        <label class="form-check-label"  for="isPrivate">Privat</label></div>

    </div>

        </form>
    </div>
</div>');
                echo ('<div id="modalOverlay" class="modal">
              
  <!-- Modal content -->
  <div class="modal-content">
    <div class="row"><h3>Name ändern:</h3><span class="close">&times;</span></div>
	   <form method="POST" action="cut.php?id=' . $cutId . '">
	   <input id="overlayId" name="overlayId" type="text" style="display: none;" value=""/>
        <div class="form-group">

            <input name="newOverlay" id="newOverlay" type="text" class="form-control" placeholder="Name eingeben"/>
        </div>
	    <button type="submit" style="margin-left:10px;" class="btn btn-primary" >Speichern</button>
                
        </form>
    </div>
</div>');
                $listOverlayWithId = "<ul class='overlayAdder'>";
                foreach ($cutInfo["info"]["overlays"] as $overlay) {
                    $listOverlayWithId = $listOverlayWithId . "<li id='" . $overlay["id"] . "'>" . $overlay["name"] . "</li>";
                    $deleteOverlay = "";
                }
                echo ('<div id="modalDescription" class="modal">

<!-- Modal content -->
<div class="modal-content">
<div class="row"><h3>Beschreibung ändern:</h3><span class="close" id="closeDec">&times;</span></div><div>' . $listOverlayWithId . '</ul></div>
<form method="POST" action="cut.php?id=' . $cutId . '">
<input  name="cutId" type="text" style="display: none;" value="' . $cutId . '">

<div class="form-group">
<textarea name="newDescription" rows="8" id="newDescription" class="form-control" placeholder="Beschreibung eingeben"></textarea>
</div>
<button type="submit" style="margin-left:10px;" class="btn btn-primary" >Speichern</button>

</form>
</div>

</div>');

                echo ('<div id="modalFilter" class="modal">
             
<!-- Modal content -->
<div class="modal-content">
<div class="row"><h3>Filter ändern:</h3><span class="close" id="closeDec">&times;</span></div>
<form method="POST" action="cut.php?id=' . $cutId . '">
                
<div class="form-group row text-center">');
                $checkBoxId = 0;

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
                    $value = "";
                    switch (key($categorys)) {
                        case "lecturer":
                            $value = $cutCategorys["dozent"];
                            break;
                        case "organgroup":
                            $value = $cutCategorys["organgruppe"];
                            break;
                        case "diagnosisgroup":
                            $value = $cutCategorys["diagnosegruppe"];
                            break;
                        case "semester":
                            $value = $cutCategorys["semester"];
                            break;
                        case "icd_0":
                            $value = $cutCategorys["icd_0"];
                            break;
                        case "icd_10":
                            $value = $cutCategorys["icd_10"];
                            break;
                        case "organ":
                            $value = $cutCategorys["organ"];
                            break;
                        case "schnittquelle":
                            $value = $cutCategorys["schnittquelle"];
                            break;
                    }
                    echo ('<div class="dropdown filter-dropdown">
																			    <p style="color: black; font-size: 13px;">' . ucfirst($catName) . ':</p>   <button type="button" id="' . key($categorys) . '" value="-1" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">' . "--" . '</button>
																			<ul class="dropdown-menu scrollable-menu">
                                                                        ');
                    foreach ($categorys[key($categorys)] as $item) {
                        if ($item["name"] != "Alle") {
                            $isChecked = "";
                            $itemId = $item["id"];

                            foreach ($categorysByCut as $categoryId) {
                                if ($itemId == $categoryId["categoryId"]) {
                                    $isChecked = "checked";
                                }
                            }
                            echo ('<li> 
                                <input type="checkbox" class="Test" id="checkBox-' . $checkBoxId . '" ' . $isChecked . '><label onclick="putFilter(' . $item["id"] . ',' . $cutId . ',`' . $_COOKIE["sessionHash"] . '`,' . $checkBoxId . ')" value="' . $item["name"] . '" >
                                <span></span>' . $item["name"] . '</label></li>');
                            $checkBoxId++;
                        }
                    }

                    echo ('</ul>
																			  </div>
																			  ');

                    next($categorys);
                }

                echo ('</div>
          
</form>
</div>
                
</div>');
            }
        } else {
            header('Location: login.php?redirect=cut.php?id=' . $_GET["id"]);
        }

        ?>

        <?php
        //Get Javascript for Openseadragon Element

        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            if (isset($_GET["noOverlay"])) {

                echo ($cut->getCutImage($_GET["id"], false));
            } else {

                echo ($cut->getCutImage($_GET["id"]));
            }
        }

        //JavaScript From Pagebuilder
        $array = array("../js/list.js", "../js/cut.js");

        echo ($pageBuilder->getJsTags($array));

        ?>
        </div>
    </main>




</body>

</html>