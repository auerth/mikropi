<?php

class Cut
{

    private $fileURL = "/files/cuts/";
    private $logFile = "../logs/cuts.log";
    public function getCutsFiltered($semester, $dozent, $organ, $organgruppe, $schnittquelle, $icd_0_, $icd_10_, $diagnosegruppe)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");

        $semester = $db->real_escape_string($semester);
        $dozent = $db->real_escape_string($dozent);

        $organ = $db->real_escape_string($organ);
        $organgruppe = $db->real_escape_string($organgruppe);

        $schnittquelle = $db->real_escape_string($schnittquelle);
        $icd_0_ = $db->real_escape_string($icd_0_);

        $icd_10_ = $db->real_escape_string($icd_10_);
        $diagnosegruppe = $db->real_escape_string($diagnosegruppe);
        $sql = "SELECT DISTINCT c.*, ttc.categoryId FROM cut c, ttCutCategory ttc WHERE ttc.cutId = c.id AND c.toDelete = '0'";
        if ($result = $db->query($sql)) {
            $info = array();
            $rows = array();
            $semesters = array();
            $dozenten = array();
            $organe = array();
            $organgruppen = array();
            $schnittquellen = array();
            $icd_0_s = array();
            $icd_10_s = array();
            $diagnosegruppen = array();

            while ($row = $result->fetch_array()) {
                if ($semester == -1 || $row["categoryId"] == $semester) {
                    array_push($semesters, $row["id"]);
                }
                if ($dozent == -1 || $row["categoryId"] == $dozent) {
                    array_push($dozenten, $row["id"]);
                }
                if ($organ == -1 || $row["categoryId"] == $organ) {
                    array_push($organe, $row["id"]);
                }
                if ($organgruppe == -1 || $row["categoryId"] == $organgruppe) {
                    array_push($organgruppen, $row["id"]);
                }
                if ($schnittquelle == -1 || $row["categoryId"] == $schnittquelle) {
                    array_push($schnittquellen, $row["id"]);
                }
                if ($icd_0_ == -1 || $row["categoryId"] == $icd_0_) {
                    array_push($icd_0_s, $row["id"]);
                }
                if ($icd_10_ == -1 || $row["categoryId"] == $icd_10_) {
                    array_push($icd_10_s, $row["id"]);
                }
                if ($diagnosegruppe == -1 || $row["categoryId"] == $diagnosegruppe) {
                    array_push($diagnosegruppen, $row["id"]);
                }

                array_push($rows, $row);
            }
            $info = array();
            $added = array();

            foreach ($rows as $row) {
                if (!in_array($row["id"], $added) && in_array($row["id"], $semesters) && in_array($row["id"], $dozenten) && in_array($row["id"], $organe) && in_array($row["id"], $organgruppen) && in_array($row["id"], $schnittquellen) && in_array($row["id"], $icd_0_s) && in_array($row["id"], $icd_10_s) && in_array($row["id"], $diagnosegruppen)) {
                    $folderNumber = 0;
                    $path = "../files/cuts/" . $row["file"] . "_files/" . $folderNumber;
                    $isFolder = true;
                    if (file_exists("../files/cuts/" . $row["file"] . ".dzi")) {
                        $xml = file_get_contents("../files/cuts/" . $row["file"] . ".dzi");
                        $p = xml_parser_create();
                        xml_parse_into_struct($p, $xml, $vals, $index);
                        xml_parser_free($p);
                        $endung = ($vals[0]["attributes"]["FORMAT"]);
                        $thumbnail = "";
                        while ($isFolder) {
                            if (is_dir($path)) {
                                $size = getimagesize($path . "/0_0." . $endung);
                                if (file_exists($path . "/0_1." . $endung)) {
                                    $folderNumber = $folderNumber - 1;
                                    $path = "../files/cuts/" . $row["file"] . "_files/" . $folderNumber;
                                    $thumbnail = "/" . $path . "/0_0." . $endung;
                                    $isFolder = false;
                                } else {
                                    $folderNumber = $folderNumber + 1;
                                    $path = "../files/cuts/" . $row["file"] . "_files/" . $folderNumber;
                                    $isFolder = true;
                                }
                            } else {
                                $isFolder = false;
                            }
                        }

                        if ($thumbnail == "") {
                            $folderNumber = 10;
                            $path = "../files/cuts/" . $row["file"] . "_files/" . $folderNumber;
                            $thumbnail = "/" . $path . "/0_0." . $endung;
                        }
                        array_push($info, array(
                            "id" => $row["id"],
                            "name" => $row["name"],
                            "description" => $row["description"],
                            "uploader" => $row["uploader"],
                            "file" => $this->fileURL . $row["file"] . ".dzi",
                            "thumbnail" => $thumbnail

                        ));

                        array_push($added, $row["id"]);
                    }
                }
            }

            if ($semester == -1 && $dozent == -1 && $organ == -1 && $organgruppe == -1 && $schnittquelle == -1 && $icd_0_ == -1 && $icd_10_ == -1 && $diagnosegruppe == -1) {

                $sql = "SELECT * FROM cut;";
                if ($result3 = $db->query($sql)) {
                    while ($row2 = $result3->fetch_array()) {
                        if (file_exists("../files/cuts/" . $row2["file"] . ".dzi")) {
                            $xml = file_get_contents("../files/cuts/" . $row2["file"] . ".dzi");
                            $p = xml_parser_create();
                            xml_parse_into_struct($p, $xml, $vals, $index);
                            xml_parser_free($p);
                            $endung = ($vals[0]["attributes"]["FORMAT"]);
                            $sql = 'SELECT * FROM ttCutCategory WHERE cutId = "' . $row2["id"] . '";';
                            if ($result2 = $db->query($sql)) {
                                $row_cnt = $result2->num_rows;
                                if ($row_cnt == 0) {
                                    $folderNumber = 0;
                                    $path = "../files/cuts/" . $row2["file"] . "_files/" . $folderNumber;
                                    $isFolder = true;
                                    $thumbnail = "";
                                    while ($isFolder) {
                                        if (is_dir($path)) {
                                            try {
                                                $size = getimagesize($path . "/0_0." . $endung);
                                                if (file_exists($path . "/0_1." . $endung)) {
                                                    $folderNumber = $folderNumber - 1;
                                                    $path = "../files/cuts/" . $row2["file"] . "_files/" . $folderNumber;
                                                    $thumbnail = "/" . $path . "/0_0." . $endung;
                                                    $isFolder = false;
                                                } else {
                                                    $folderNumber = $folderNumber + 1;
                                                    $path = "../files/cuts/" . $row2["file"] . "_files/" . $folderNumber;
                                                    $isFolder = true;
                                                }
                                            } catch (Exception $e) { }
                                        } else {
                                            $isFolder = false;
                                        }
                                    }
                                    if ($thumbnail == "") {
                                        $folderNumber = 10;
                                        $path = "../files/cuts/" . $row2["file"] . "_files/" . $folderNumber;
                                        $thumbnail = "/" . $path . "/0_0." . $endung;
                                    }

                                    array_push($info, array(
                                        "name" => $row2["name"],
                                        "id" => $row2["id"],
                                        "description" => $row2["description"],
                                        "uploader" => $row2["uploader"],
                                        "file" => $this->fileURL . $row2["file"] . ".dzi",
                                        "thumbnail" => $thumbnail
                                    ));
                                }
                            } else { }
                        } else {
                            // $sql = 'DELETE * FROM cut WHERE id = "' . $row2["id"] . '";'; 
                            // $db->query($sql);
                        }
                    }
                }
            }
            $this->aasort($info, 'name');
            $newInfo = array();
            foreach ($info as $ii) {
                array_push($newInfo, $ii);
            }

            $jsonResult["success"] = true;
            $jsonResult["info"] = $newInfo;
        }

        return json_encode($jsonResult);
    }



    public function aasort(&$array, $key)
    {
        $sorter = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }
        $array =  $ret;
    }



    public function countCuts()
    {
        include("../etc/db.php");
        $sql = "SELECT * FROM cut;";
        if ($result = $db->query($sql)) {
            $row_cnt = $result->num_rows;
            return $row_cnt;
        }
        return 0;
    }

    public function checkForCuts($userId)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );

        include("../etc/db.php");
        $userId = $db->real_escape_string($userId);

        $user = new User();
        $isAdmin = $user->isAdmin($userId);
        $isAdmin = $isAdmin["success"];
        if (!$isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }
        $dir = '../files/cuts';
        $files1 = scandir($dir);
        $files = array();
        foreach ($files1 as $file) {
            if (substr($file, -4) == ".dzi") {
                array_push($files, $file);
            }
        }
        $added = 0;
        foreach ($files as $file) {
            $fileS = substr($file, 0, -4);
            $sql = "SELECT * FROM cut WHERE file like '" . $fileS . "';";
            if ($result = $db->query($sql)) {
                $row_cnt = $result->num_rows;
                if ($row_cnt < 1) {
                    $sql = "INSERT INTO cut (name, description, uploader,file)
						VALUES ('" . $fileS . "', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','" . $userId . "','" . $fileS . "'); ";
                    if ($result = $db->query($sql)) {
                        $added++;
                    } else {
                        $jsonResult["success"] = false;
                        $jsonResult["error"] = "Error by Inserting Data (Request error).";
                        $jsonResult["errorCode"] = "1";
                    }
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Error by data selecting.";
                $jsonResult["errorCode"] = "1";
            }
        }

        $jsonResult["success"] = true;
        $jsonResult["info"] = $added;

        return $jsonResult;
    }

    public function getCutInfo($id, $overlay = true)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        $id = $db->real_escape_string($id);

        $sql = "SELECT * FROM cut WHERE id =  '" . $id . "';";
        if ($result = $db->query($sql)) {
            $row_cnt = $result->num_rows;
            if ($row_cnt == 1) {
                $row = $result->fetch_array();
                if ($overlay) {
                    $sql = "SELECT * FROM overlay WHERE cutId = '" . $row["id"] . "';";
                    $overlays = array();
                    if ($result = $db->query($sql)) {
                        while ($row2 = $result->fetch_array()) {

                            array_push($overlays, $row2);
                        }
                    }
                }
                $info = array(
                    "id" => $row["id"],
                    "name" => $row["name"],
                    "description" => $row["description"],
                    "overlays" => $overlays,
                    "uploader" => $row["uploader"]
                );
                $jsonResult["success"] = true;
                $jsonResult["info"] = $info;
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Error by data selecting (Request error).";
                $jsonResult["errorCode"] = "1";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }

        return $jsonResult;
    }

    public function getCutImage($id, $overlay = true)
    {
        include("../etc/db.php");
        $id = $db->real_escape_string($id);

        $sql = "SELECT * FROM cut WHERE id =  '" . $id . "';";

        if ($result = $db->query($sql)) {
            $row_cnt = $result->num_rows;
            if ($row_cnt == 1) {
                $row = $result->fetch_array();
                $file = $row["file"];
                $filename = "../files/cuts/" . $file . ".dzi";

                if (file_exists($filename)) {

                    $overlays = "";
                    if ($overlay) {
                        $sql = "SELECT * FROM overlay WHERE cutId = '" . $id . "';";
                        if ($result = $db->query($sql)) {
                            while ($row = $result->fetch_array()) {
                                $overlays = $overlays . ' var elt = document.createElement("div");
                    elt.className = "runtime-overlay";
                    elt.style.outline = "3px solid #3281D6";
                    elt.style.opacity = "0.9";
                    elt.textContent = "";
                    elt.id="o-' . $row["id"] . '";     
                    viewer.addOverlay({
                        element: elt,
                        id: "' . $row["id"] . '",
                        location: new OpenSeadragon.Rect(' . $row["fromX"] . ', ' . $row["fromY"] . ', ' . $row["sizeX"] . ',' . $row["sizeY"] . '),
                        rotationMode: OpenSeadragon.OverlayRotationMode.BOUNDING_BOX
                    });
                    //var tracker = new OpenSeadragon.MouseTracker({
                    //    element: elt,
                    //    clickHandler: function(event) {
                            //var overlay = viewer.getOverlayById(event.originalEvent.target.id);
                            //viewer.viewport.fitBounds(overlay.getBounds(viewer.viewport));
                    //    }
                    // });
                    
                    
                    

                   
                     ';
                    
                            }
                        } else { }
                    }

                    $html = '
							<script src="js/openseadragon/openseadragon.min.js"></script>
							<script type="text/javascript">
							var viewer = OpenSeadragon({
								id: "openseadragon1",
								showNavigator:  true,
								navigatorPosition:   "TOP_RIGHT",
								prefixUrl: "js/openseadragon/images/",
								tileSources: "files/cuts/' . $file . '.dzi",
								zoomInButton:   "zoom-in",
                                zoomOutButton:  "zoom-out",
								homeButton:     "home",
								fullPageButton: "fullpage",
								toolbar:        "toolbarDiv",
							});
						viewer.addHandler("open", function(event) {
							' . $overlays . '
					    });
								</script>';

                    return $html;
                } else {
                    return "No Image";
                }
            } else {
                return "Error selecting Data. Selected: " . $row_cnt . "<br>Try to select id: " . $id;
            }
        } else {
            return "Database error.";
        }
    }

    public function addCutWithFilter($uploaderId, $name, $description, $filename, $semester, $dozent, $diagnosegruppe, $organ, $organgruppe, $schnittquelle, $icd_0, $icd_10)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../classes/user.php");
        $uploaderId = $db->real_escape_string($uploaderId);

        $user = new User();
        $isAdmin = $user->isAdmin($uploaderId);
        $isAdmin = $isAdmin["success"];
        if (!$isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }
        include("etc/db.php");
        $sql = "SELECT * FROM cut;";
        if ($result = $db->query($sql)) {
            $nameExists = false;
            while ($row = $result->fetch_array()) {
                if (strtolower($row["name"]) == strtolower($name)) {
                    $nameExists = true;
                    break;
                }
            }
            if (!$nameExists) {
                $sql = "INSERT INTO cut (name, description, uploader,file)
						VALUES ('" . $name . "', '" . $description . "', '" . $uploaderId . "','" . $filename . "'); ";
                if ($result = $db->query($sql)) {
                    $cutId = $db->insert_id;
                    if ($semester > -1) {
                        $sql = "INSERT INTO ttCutCategory (cutId, categoryId)
								VALUES ('" . $cutId . "', '" . $semester . "'); ";
                        if (!$result = $db->query($sql)) {
                            $jsonResult["success"] = false;
                            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                            $jsonResult["errorCode"] = "1";
                            return $jsonResult;
                        }
                    }
                    if ($dozent > -1) {
                        $sql = "INSERT INTO ttCutCategory (cutId, categoryId)
								VALUES ('" . $cutId . "', '" . $dozent . "'); ";
                        if (!$result = $db->query($sql)) {
                            $jsonResult["success"] = false;
                            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                            $jsonResult["errorCode"] = "1";
                            return $jsonResult;
                        }
                    }
                    if ($diagnosegruppe > -1) {
                        $sql = "INSERT INTO ttCutCategory (cutId, categoryId)
								VALUES ('" . $cutId . "', '" . $diagnosegruppe . "'); ";
                        if (!$result = $db->query($sql)) {
                            $jsonResult["success"] = false;
                            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                            $jsonResult["errorCode"] = "1";
                            return $jsonResult;
                        }
                    }
                    if ($organ > -1) {
                        $sql = "INSERT INTO ttCutCategory (cutId, categoryId)
								VALUES ('" . $cutId . "', '" . $organ . "'); ";
                        if (!$result = $db->query($sql)) {
                            $jsonResult["success"] = false;
                            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                            $jsonResult["errorCode"] = "1";
                            return $jsonResult;
                        }
                    }
                    if ($organgruppe > -1) {
                        $sql = "INSERT INTO ttCutCategory (cutId, categoryId)
								VALUES ('" . $cutId . "', '" . $organgruppe . "'); ";
                        if (!$result = $db->query($sql)) {
                            $jsonResult["success"] = false;
                            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                            $jsonResult["errorCode"] = "1";
                            return $jsonResult;
                        }
                    }
                    if ($schnittquelle > -1) {
                        $sql = "INSERT INTO ttCutCategory (cutId, categoryId)
								VALUES ('" . $cutId . "', '" . $schnittquelle . "'); ";
                        if (!$result = $db->query($sql)) {
                            $jsonResult["success"] = false;
                            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                            $jsonResult["errorCode"] = "1";
                            return $jsonResult;
                        }
                    }
                    if ($icd_0 > -1) {
                        $sql = "INSERT INTO ttCutCategory (cutId, categoryId)
								VALUES ('" . $cutId . "', '" . $icd_0 . "'); ";
                        if (!$result = $db->query($sql)) {
                            $jsonResult["success"] = false;
                            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                            $jsonResult["errorCode"] = "1";
                            return $jsonResult;
                        }
                    }
                    if ($icd_10 > -1) {
                        $sql = "INSERT INTO ttCutCategory (cutId, categoryId)
								VALUES ('" . $cutId . "', '" . $icd_10 . "'); ";
                        if (!$result = $db->query($sql)) {
                            $jsonResult["success"] = false;
                            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                            $jsonResult["errorCode"] = "1";
                            return $jsonResult;
                        }
                    }

                    $jsonResult["success"] = true;
                    $jsonResult["info"] = "Cut added";
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                    $jsonResult["errorCode"] = "1";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Name already exists.";
                $jsonResult["errorCode"] = "2";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }
        return $jsonResult;
    }

    public function updateCutName($userId, $id, $name)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include_once("../classes/user.php");
        include("../etc/db.php");

        $userId = $db->real_escape_string($userId);
        $id = $db->real_escape_string($id);
        $name = $db->real_escape_string($name);

        $user = new User();
        $isAdmin = $user->isAdmin($userId);
        $isAdmin = $isAdmin["success"];
        if (!$isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }
        $sql = "SELECT * FROM cut WHERE id = '" . $id . "';";
        if ($result = $db->query($sql)) {
            $num_rows = $result->num_rows;
            if ($num_rows == 1) {
                $sql = "UPDATE cut SET name = '" . $name . "' WHERE id = '" . $id . "';";
                if ($result = $db->query($sql)) {
                    $jsonResult["success"] = true;
                    $jsonResult["info"] = "Cut updated";
                    $log = file_get_contents($this->logFile);
                    file_put_contents($this->logFile, $log . "INFO-" . date('d/m/Y H:i:s', time() + 2 * 3600) . ": Name changed from Cut-".$id." from " . $userId . "\n");
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Error by data inserting (" . $db->error . ").";
                    $jsonResult["errorCode"] = "1";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Error by data selecting (num rows != 1).";
                $jsonResult["errorCode"] = "1";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }
        return $jsonResult;
    }

    public function updateCutDescription($userId, $id, $description)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include_once("../classes/user.php");
        include("../etc/db.php");

        $userId = $db->real_escape_string($userId);
        $id = $db->real_escape_string($id);
        $description = $db->real_escape_string($description);

        $user = new User();
        $isAdmin = $user->isAdmin($userId);
        $isAdmin = $isAdmin["success"];
        if (!$isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }
        $sql = "SELECT * FROM cut WHERE id = '" . $id . "';";
        if ($result = $db->query($sql)) {
            $num_rows = $result->num_rows;
            if ($num_rows == 1) {
                $sql = "UPDATE cut SET description = '" . $description . "' WHERE id = '" . $id . "';";
                if ($result = $db->query($sql)) {
                    $jsonResult["success"] = true;
                    $jsonResult["info"] = "Cut updated.";
                    $log = file_get_contents($this->logFile);
                    file_put_contents($this->logFile, $log . "INFO-" . date('d/m/Y H:i:s', time() + 2 * 3600) . ": Description changed from Cut-".$id." from " . $userId . "\n");
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Error by data inserting (" . $db->error . ").";
                    $jsonResult["errorCode"] = "1";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Error by data selecting (num rows != 1).";
                $jsonResult["errorCode"] = "1";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }
        return $jsonResult;
    }

    public function deleteCut($sessionHash,$cutId)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        $sessionHash = $db->real_escape_string($sessionHash);
        $cutId = $db->real_escape_string($cutId);

        include_once("../classes/user.php");
        $user = new User();
        $isAdmin = $user->isAdmin($sessionHash);
        $isAdmin = $isAdmin["success"];
        if (!$isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }
        $sql = "UPDATE cut SET toDelete = '1' WHERE id = '" . $cutId . "';";
        if ($result = $db->query($sql)) {
            $jsonResult["success"] = true;
            $jsonResult["info"] = "Schnitt wird in kürze gelöscht.";

        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }
        return $jsonResult;
    }
}
