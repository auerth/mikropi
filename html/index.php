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

        // Cutlist Page
        if ($cuts != null || !isset($_GET["cuts"]) || $categorys != null) {
            //if not logged in redirect to login.php 
            if (!$loggedIn) {
                header("Location: login.php?redirect=index.php");
            }
            //draw filter dropdown menus
            if ($categorys != null) {
                echo ('<div class="max-width"><details open><summary>Filter</summary>');
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
                                    <p>' . ucfirst($catName) . ':</p>
                                    <button type="button" id="' . key($categorys) . '" value="-1" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Alle</button>
									<ul class="dropdown-menu scrollable-menu">');
                    foreach ($categorys[key($categorys)] as $item) {
                        echo (' <li><a id="' . $item["id"] . '" class="dropdown-item">' . $item["name"] . '</a></li>');
                    }
                    echo ('</ul></div>');
                    next($categorys);
                }
                //seachbar
                echo ('</details>');
            }
            //draw cutlist
            echo ('<div style="padding: 0 15px 1rem 15px;"><input type="text" id="search" style="" class="form-control" placeholder="Suche" value="" /></div>');
            echo ('<div id="liste" class="list">');
            foreach ($cuts as $item) {
                //get categorys of cut
                $result = $category->getCategoryOfCut($_COOKIE["sessionHash"], $item["id"]);
                $categorysOfCut = null;
                if ($result["success"]) {
                    $categorysOfCut = $result["info"];
                }
                echo ('
								<div id="' . $item["id"] . '" class="media" onclick="navigateCut(' . $item["id"] . ')">
									<img id="' . $item["id"] . '" class="mr-3" src="' . $item["thumbnail"] . '"  alt="No Thumbnail">
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
            echo ('</div></div>');
            //if cuts parameter has value show specified cut
        }


        ?>

        <?php
        //Get Javascript for Openseadragon Element



        //JavaScript From Pagebuilder
        $array = array("../js/list.js", "../js/cut.js");

        echo ($pageBuilder->getJsTags($array));

        ?>
    </main>




</body>

</html>