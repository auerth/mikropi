<?php
include("../classes/cut.php");
include("../classes/category.php");
include("../classes/dashboard.php");
include("../classes/overlay.php");
include("../classes/pagebuilder.php");
$loggedIn = false;
$isAdmin = false;
$serverUrl = "https://mikropi.de/";
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

if (isset($_POST["filterid"]) && isset($_POST["cutid"]) && isset($_POST["hash"])) {
    $hash = $_POST["hash"];
    $filterId = $_POST["filterid"];
    $cutId = $_POST["cutid"];
    $category = new Category();
    $result = $category->putCategory($hash, $cutId, $filterId);
    die(json_encode($result));
}
if (isset($_COOKIE["sessionHash"]) && $_COOKIE["sessionHash"] != -1) {
    $loggedIn = true;
    if (isset($_COOKIE["isAdmin"])) {
        $isAdmin = $_COOKIE["isAdmin"];
    }
    if (isset($_COOKIE["name"])) {
        $name = $_COOKIE["name"];
    }
    $cut = new Cut();



    if (isset($_GET["cuts"])) {
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
    }
    if (isset($_GET["cuts"]) && $_GET["cuts"] != null) {
        $category = new Category();
        $result = $category->getCategoryOfCut($_COOKIE["sessionHash"], $_GET["cuts"]);
        if ($result["success"]) {
            $cutCategorys = $result["info"];
        } else {
            $error = $result["error"];
        }

        $result = $category->getAllCategorysFromCut($_GET["cuts"]);
        if ($result["success"]) {
            $categorysByCut = $result["info"];
        } else {
            $error = $result["error"];
        }
    }

    if (isset($_POST["cutId"]) && isset($_POST["newTitle"]) && $isAdmin) {
        $result = $cut->updateCutName($_COOKIE["sessionHash"], $_POST["cutId"], $_POST["newTitle"]);
        if (!$result["success"]) {
            $cutMessage = $result["error"];
            $alertType = "alert-danger";
        }
    } else if (isset($_POST["cutId"]) && isset($_POST["newDescription"]) && $isAdmin) {
        $result = $cut->updateCutDescription($_COOKIE["sessionHash"], $_POST["cutId"], $_POST["newDescription"]);
        if (!$result["success"]) {
            $cutMessage = $result["error"];
            $alertType = "alert-danger";
        }
    } else if (isset($_POST["cutId"]) && isset($_POST["deleteCut"]) && $isAdmin) {
        $result = $cut->deleteCut($_COOKIE["sessionHash"], $_POST["cutId"]);
        if (!$result["success"]) {
            $cutMessage = $result["error"];
            $alertType = "alert-danger";
        }else{
            $cutMessage = $result["info"];
            $alertType = "alert-success";
            header("Location: index.php?cuts");
        }

    } else if (isset($_POST["overlayId"]) && isset($_POST["newOverlay"]) && $isAdmin) {
        $result = $overlay->editOverlay($_COOKIE["sessionHash"], $_POST["overlayId"], $_POST["newOverlay"]);
        if (!$result["success"]) {
            $overlayMessage = $result["error"];
            $alertType = "alert-danger";
        }
        $overlaysVisible = true;
    }

    if (isset($_POST["title"]) && isset($_POST["text"]) && $isAdmin) {
        $result = $dashboard->addDashboardEntrie($_COOKIE["sessionHash"], $_POST["title"], $_POST["text"]);
        if (!$result["success"]) {
            $dashboardMessage = $result["error"];
            $alertType = "alert-danger";
        }
    }
    if (isset($_POST["title"]) && isset($_GET["cuts"]) && isset($_POST["location"]) && isset($_POST["size"]) && $isAdmin) {

        $result = $overlay->addOverlay($_COOKIE["sessionHash"], $_GET["cuts"], $_POST["title"], $_POST["location"], $_POST["size"]);
        if (!$result["success"]) {
            $overlayMessage = $result["error"];
            $alertType = "alert-danger";
        }
        $overlaysVisible = true;
    }
    if (isset($_POST["overlayId"]) && isset($_GET["cuts"]) && $isAdmin && !isset($_POST["newOverlay"])) {
        $overlay = new Overlay();

        $result = $overlay->deleteOverlay($_COOKIE["sessionHash"], $_POST["overlayId"]);
        if (!$result["success"]) {
            $overlayMessage = $result["error"];
            $alertType = "alert-danger";
        }
        $overlaysVisible = true;
    }

    if (isset($_POST["dashId"]) && $isAdmin) {
        $result = $dashboard->deleteDashboardEntrie($_COOKIE["sessionHash"], $_POST["dashId"]);
        if (!$result["success"]) {
            $dashboardMessage = $result["error"];
            $alertType = "alert-danger";
        }
    }
} else {
    $loggedIn = false;
}

?>
<!DOCTYPE html>
<html lang="de">
<?php

echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Als Student vom Institut für klinische Pathologie Freiburg kannst du hier Mikroskopschnitte schnell und einfach einsehen.", array("../css/notification.css")));
?>

<body>
    <!-- Navigation -->
    <?php
    echo ($pageBuilder->getNavBar($loggedIn, $isAdmin));
    ?>

    <!-- Page Content -->
    <main>
        <?php
       
        // Loop the list results
        if ($cuts != null || isset($_GET["cuts"]) || $categorys != null) {
            if ($_GET["cuts"] == null) {
                if (!$loggedIn) {
                    header('Location: https://mikropi.de/login.php');
                }

                if ($categorys != null) {
                    echo ('<i class="fas fa-filter fa-2x bg-main" style="padding: 4px; border-radius: 4px; margin: 5px;"id="disFilter"></i>');
                    echo ('<div class="filter" >');
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
									<p>' . ucfirst($catName) . ':</p><button type="button" id="' . key($categorys) . '" value="-1" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Alle</button>
									<ul class="dropdown-menu scrollable-menu">
								');
                        foreach ($categorys[key($categorys)] as $item) {
                            echo ('<li><a id="' . $item["id"] . '" class="dropdown-item">' . $item["name"] . '</a></li>');
                        }

                        echo ('</ul>
									  </div>
									  ');

                        next($categorys);
                    }

                    echo ('								<input type="text" id="search" class="form-control" placeholder="Suche" value="" />
</div>');
                }

                echo ('<div id="liste" class="list">');
                foreach ($cuts as $item) {
                    $result = $category->getCategoryOfCut($_COOKIE["sessionHash"], $item["id"]);
                    $categorysOfCut = null;
                    if ($result["success"]) {
                        $categorysOfCut = $result["info"];
                    }

                    echo ('
								<div id="' . $item["id"] . '" class="media">
									<img id="' . $item["id"] . '" class="mr-3" src="' . $serverUrl . $item["thumbnail"] . '"  alt="No Thumbnail">
									<div id="' . $item["id"] . '" class="media-body">
										<h5 id="' . $item["id"] . '" class="mt-0">' . $item["name"] . '</h5>
                                        <p id="' . $item["id"] . '">' . $item["description"] . '</p>
                                        <div id="' . $item["id"] . '" class="cut_categorys">
                                            <div id="' . $item["id"] . '" class="row">
                                                <p id="' . $item["id"] . '"><span id="' . $item["id"] . '">Semester: </span>' . $categorysOfCut["semester"] . '</p>
                                            </div>
                                            <div id="' . $item["id"] . '" class="row">
                                                <p id="' . $item["id"] . '"><span id="' . $item["id"] . '">Dozent: </span>' . $categorysOfCut["dozent"] . '</p>
                                            </div>
                                            <div id="' . $item["id"] . '" class="row">
                                                <p id="' . $item["id"] . '"><span id="' . $item["id"] . '">Organ: </span>' . $categorysOfCut["organ"] . '</p>
                                            </div>
                                            <div id="' . $item["id"] . '" class="row">
                                                <p id="' . $item["id"] . '"><span id="' . $item["id"] . '">Schnittquelle: </span>' . $categorysOfCut["schnittquelle"] . '</p>
                                            </div>
                                            <div id="' . $item["id"] . '" class="row">
                                                <p id="' . $item["id"] . '"><span id="' . $item["id"] . '">Organgruppe: </span>' . $categorysOfCut["organgruppe"] . '</p>
                                            </div>
                                            <div id="' . $item["id"] . '" class="row">
                                                <p id="' . $item["id"] . '"><span id="' . $item["id"] . '">Diagnosegruppe: </span>' . $categorysOfCut["diagnosegruppe"] . '</p>
                                            </div>
                                            <div id="' . $item["id"] . '" class="row">
                                                <p id="' . $item["id"] . '"><span id="' . $item["id"] . '">ICD_0: </span>' . $categorysOfCut["icd_0"] . '</p>
                                            </div>
                                            <div id="' . $item["id"] . '" class="row">
                                                <p id="' . $item["id"] . '"><span id="' . $item["id"] . '">ICD_10: </span>' . $categorysOfCut["icd_10"] . '</p>
                                            </div>
                                            <div id="' . $item["id"] . '" class="row">
                                            </div>
                                        </div> 
									</div>
								</div>
							');
                }
                echo ('</div>');
            } else if ($loggedIn) {
                $editTitle = "";
                if (isset($_GET['noOverlay'])) {
                    $editOverlays = '
                <input type="checkbox"><label  onclick="annos('.$_GET["cuts"].')" >
                <span></span>Annotationen zeigen
              </label>';
                } else {
                    $editOverlays = '
                <input type="checkbox"  checked><label onclick="noAnnos('.$_GET["cuts"].')">
                <span></span>Annotationen zeigen
              </label>';
                }
                $deleteCut = "";

                $editDescription = "";
                if ($isAdmin) {
                    $editTitle = "<i class='fas fa-pencil-alt fa-1x' id='editTitle'></i>";
                    $deleteCut = "<i class='fas fa-trash-alt fa-1x right' onclick='deleteCut(".$_GET["cuts"].")'></i>";

                    $editDescription = "<i class='fas fa-pencil-alt fa-1x' id='editDescription'></i>";
                    $editOverlays .= "
        <button type='submit' id='addOverlay' onclick='drawOverlay()' style='margin-left:auto; float: right;' class='btn btn-primary' >+</button>";
                }

                $cut = new Cut();
                // Schnittinformationen
                $cutId = $_GET["cuts"];
                if (isset($_GET["noOverlay"])) {
                    $cutInfo = $cut->getCutInfo($_GET["cuts"]);
                } else {
                    $cutInfo = $cut->getCutInfo($_GET["cuts"]);
                }

                $overlays = "<ul>";
                $deleteOverlay = "";
                $editOverlay = "";
                foreach ($cutInfo["info"]["overlays"] as $overlay) {
                    if ($isAdmin) {
                        $deleteOverlay = "<i style='color: #0062cc;margin-left: auto; margin-right:0px; padding:0px; float: right; height:auto; width: auto;' onclick='deleteOverlay(" . $overlay["id"] . ")' class='fas fa-trash-alt'></i>";
                        $editOverlay = "<i style='color: #0062cc; margin-left: 2px;height: auto; width: auto;' class='editOverlayName fas fa-pencil-alt fa-1x' id='editOverlayName' name='" . $overlay["id"] . "'></i>";
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

                echo ("<div class='row bg-second'><h1 id='title' style='color: white;'>" . $cutInfo["info"]["name"] . "</h1>" . $editTitle . $deleteCut."</div>");
                echo ('<div class="flexbox" style="flex-wrap: nowrap; background-color: white;">');
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
        </div><ul class='cutbar'><li><img src='../images/left.png'  id='hide'></li><li><i class='fas fa-align-left fa-2x bg-main' id='itemDescription'></i></li><li><i id='itemOverlay' class='fas fa-flag fa-2x bg-main'></i></li></ul>");
                echo ("<div class='cutfile'>");
                // Schnittdatei
                $editFilter = "";
                if ($isAdmin) {
                    // Neues Modal Hinzuf�gen mit Filterauswahl
                    $editFilter = '<button type="submit" id="editFilter" style="margin-left:auto; float: right;" class="btn btn-primary" >Filter bearbeiten</button>';
                }
                echo ($editFilter . '<div id="toolbarDiv" class="toolbar" style="width: 100%; height: 2%;">
		              <a id="zoom-in" href="#zoom-in"/>
		              <a id="zoom-out" href="#zoom-out"/>
		              <a id="home" href="#home"/>
                      <a id="fullpage" href="#fullpage"/>
                      <a id="zoom25" href="#zoom25" style="display: inline-block; position: relative;"/>
                      <a id="zoom50" href="#zoom50" style="display: inline-block; position: relative;"/>
                      <a id="zoom75" href="#zoom75" style="display: inline-block; position: relative;"/>
                      <a id="zoom100" href="#zoom100" style="display: inline-block; position: relative;"/></a>
	    			</div>
                        <div class="openseadragon" id="openseadragon1" style="width:100%; height:90%;"> </div></div></div>');
                       
                echo("<div class='mobilebar'>");
                echo("<div class='row'>
                <i class='fas fa-align-left fa-2x bg-main' id='itemDescriptionM'></i>
                <i id='itemOverlayM' class='fas fa-flag fa-2x bg-main'></i>
                </div>");
                echo ("<div class='descriptionM'>");
                echo ("<div id='description-text'>" . $cutInfo["info"]["description"] . "</div>" . $editDescription . "</li></div>
                " . $overlayDivM . "<div class='row'>" . $editOverlays . "</div>" . $overlays . "
                </div>");
                echo("</div>");
                echo("</div>");

                if ($isAdmin) {
                    echo ('<div id="modalTitle" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="row"><h3>Name ändern:</h3><span class="close">&times;</span></div>
	   <form method="POST" action="index.php?cuts=' . $cutId . '">
	   <input id="cutId" name="cutId" type="text" style="display: none;" value="' . $cutId . '"/>
        <div class="form-group">
            <input name="newTitle" id="newTitle" type="text" class="form-control" placeholder="Name eingeben"/>
        </div>
	    <button type="submit" style="margin-left:10px;" class="btn btn-primary" >Speichern</button>

        </form>
    </div>
</div>');
                    echo ('<div id="modalOverlay" class="modal">
                
  <!-- Modal content -->
  <div class="modal-content">
    <div class="row"><h3>Name ändern:</h3><span class="close">&times;</span></div>
	   <form method="POST" action="index.php?cuts=' . $cutId . '">
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
                        $listOverlayWithId = $listOverlayWithId . "<li id='".$overlay["id"]."'>" . $overlay["name"] ."</li>";
                        $deleteOverlay = "";
                    }
                    echo ('<div id="modalDescription" class="modal">

<!-- Modal content -->
<div class="modal-content">
<div class="row"><h3>Beschreibung ändern:</h3><span class="close" id="closeDec">&times;</span></div><div>' . $listOverlayWithId . '</ul></div>
<form method="POST" action="index.php?cuts=' . $cutId . '">
<input id="cutId" name="cutId" type="text" style="display: none;" value="' . $cutId . '">

<div class="form-group">
<textarea name="newDescription" rows="8" id="newDescription" type="text" class="form-control" placeholder="Beschreibung eingeben"></textarea>
</div>
<button type="submit" style="margin-left:10px;" class="btn btn-primary" >Speichern</button>

</form>
</div>

</div>');

                    echo ('<div id="modalFilter" class="modal">
                
<!-- Modal content -->
<div class="modal-content">
<div class="row"><h3>Filter ändern:</h3><span class="close" id="closeDec">&times;</span></div>
<form method="POST" action="index.php?cuts=' . $cutId . '">
                
<div class="form-group row text-center">');
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
                        $checkBoxId = 0;
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
                                
                                <input type="checkbox" id="checkBox-' . $checkBoxId . '" ' . $isChecked . '><label onclick="putFilter(' . $item["id"] . ',' . $cutId . ',`' . $_COOKIE["sessionHash"] . '`,' . $checkBoxId . ')" value="' . $item["name"] . '" >
                                <span></span>' . $item["name"] . '</lable>');
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
                header('Location: https://mikropi.de/login.php');
            }
        } else {
            if (!isset($_GET["cuts"]) && $loggedIn) {
                $dashList = $dashboard->getDashboardEntries();
                $success = $dashList["success"];

                if ($success) {
                    $info = $dashList["info"];

                    echo ('<div class="card bgimg">
                    <div class="card-body" stylle="margin-bottom: 0px; padding-bottom: 0px;">
                        <div class="bg-light text-center" style="height: 300px; margin-top: 20px; margin-bottom: 20px; padding-top: 0px;">
                            <div style="padding: 30px 0;">
                                <img height="200" src="../images/logo_white.png">
                                <h2 class="card-text">Willkommen ' . $name . '</h2>
                            </div>
                        </div>
                    </div>
                </div>');

                    if ($isAdmin) {
                        if ($dashboardMessage != null) {
                            echo ('<div class="alert ' . $alertType . '">' . $dashboardMessage . '
								</div>');
                        }
                        echo ('<div class="card item" style="margin: 10px;"><div class="form">
<h4 class="card-header">Neuer Eintrag</h4>
										<form action="index.php" class="card-body" style="margin-top: 0px; margin-bottom: 0px;" method="post">
										<input type="text" id="title" name="title" class="form-control" placeholder="Titel" value="" />
										<textarea class="form-control" name="text" placeholder="Was gibt\'s neues?" rows="7"></textarea>
										<button type="submit" style="margin-top:10px; margin-left: auto; margin-right: 0px;" class="btn btn-primary" >Posten</button>
										</form>
										</div></div>');
                    }
                    echo ("<div class='dashboard'>							");

                    foreach ($info as $item) {
                        $row = "";
                        $row2 = "";
                        if ($isAdmin) {
                            $row = "<div class='row bg-second'><div class='col-10'>";
                            $row2 = "</div><div class='col-2 '><i style='float: right; margin-left: auto;' onclick='deleteDashItem(" . $item["id"] . ")' class='fas fa-trash-alt'></i></div></div>";
                        }
                        echo ("	<div class='card item '>

															" . $row . "<h4 class='card-header'>" . $item["title"] . "</h4>" . $row2 . "
															<p class='card-body'>" . $item["text"] . "</p>
				  <ul class='list-group list-group-flush'>
						    <li class='list-group-item author'>" . $item["uploader"] . " - " . date("d.m.Y H:i:s", $item["timestamp"]) . "</li>
					</ul></div>
						");
                    }
                } else {
                    echo ('<div class="alert alert-danger">' . $dashList["error"] . '
								</div>');
                }
                echo ("</div>");

                echo ("

						</div>");
            } else if (!$loggedIn) {
                echo (file_get_contents("home.html"));
            }
        }
        ?>
                    <?php
                    echo ($pageBuilder->getFooter());
                    if($loggedIn){
                        if (!isset($_COOKIE["bugreport"])) {
                            echo (' <div class="toast__container" id="reportBug">
                        <div class="toast__cell">
                            <div class="toast toast--blue">
                                <div class="toast__icon">
                                    
                                </div>
                                <div class="toast__content">
                                    <p class="toast__type">Bug entdeckt?</p>
                                    <p class="toast__message">Melden uns den Fehler, den du gefunden hast gleich <a href="report.php">hier</a>!</p>
                                </div>
                                <div class="toast__close">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 15.642 15.642">
                                        <path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>');
                        }
                    }
                    ?>


    </main>

    <!-- Bootstrap core JavaScript -->
    <?php
    if (isset($_GET["cuts"]) && $_GET["cuts"] > 0) {
        if (isset($_GET["noOverlay"])) {

            echo ($cut->getCutImage($_GET["cuts"], false));
        } else {

            echo ($cut->getCutImage($_GET["cuts"]));
        }
    }

    $array = array("../js/list.js", "../js/cut.js", "../js/dashboard.js", "../js/notify.js");
    echo ($pageBuilder->getJsTags($array));
    ?>

</body>

</html>