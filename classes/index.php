<?php
include('cut.php');
include('category.php');
$cut = new Cut();
$result = $cut->getCutsFiltered($_POST["semester"], $_POST["dozent"], $_POST["organ"], $_POST["organgruppe"], $_POST["schnittquelle"], $_POST["icd_0"], $_POST["icd_10"], $_POST["diagnosegruppe"]);
$cuts = json_decode($result, true);
if ($cuts["success"]) {
    $cuts = $cuts["info"];
    $info = array("info"=>array());
    $category = new Category();
    foreach ($cuts as $item) {
        $result = $category->getCategoryOfCut($_COOKIE["sessionHash"], $item["id"]);
        $categorysOfCut = null;
        if ($result["success"]) {
            $categorysOfCut = $result["info"];
        }
        $inf = array("html"=>'
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
    ',"name"=>$item["name"]);
        array_push ($info["info"],$inf);
                
    }
    echo(json_encode($info));
} else {
    echo (json_encode(array()));
}
