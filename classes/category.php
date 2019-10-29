<?php



class category
{

    public function getCategorys()
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );

        $semesters = null;

        include("../etc/db.php");
        $sql = "SELECT c.name,c.id FROM category c, semester s WHERE s.categoryId = c.id ORDER BY c.name ASC;";
        if ($result = $db->query($sql)) {
            $semesters = array();
            array_push($semesters, array(
                "name" => "Alle",
                "id" => "-1"
            ));
            while ($row = $result->fetch_array()) {
                array_push($semesters, array(
                    "name" => $row["name"],
                    "id" => $row["id"]

                ));
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }

        $dozenten = null;
        $sql = "SELECT c.name,c.id FROM category c, lecturer l WHERE l.categoryId = c.id ORDER BY c.name ASC;";
        if ($result = $db->query($sql)) {
            $dozenten = array();
            array_push($dozenten, array(
                "name" => "Alle",
                "id" => "-1"
            ));
            while ($row = $result->fetch_array()) {
                array_push($dozenten, array(
                    "name" => $row["name"],
                    "id" => $row["id"]
                ));
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }

        $organ = null;
        $sql = "SELECT c.name,c.id FROM category c, organ o WHERE o.categoryId = c.id ORDER BY c.name ASC;";
        if ($result = $db->query($sql)) {
            $organ = array();
            array_push($organ, array(
                "name" => "Alle",
                "id" => "-1"
            ));
            while ($row = $result->fetch_array()) {
                array_push($organ, array(
                    "name" => $row["name"],
                    "id" => $row["id"]
                ));
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }

        $organgroup = null;
        $sql = "SELECT c.name,c.id FROM category c, organgroup o WHERE o.categoryId = c.id ORDER BY c.name ASC;";
        if ($result = $db->query($sql)) {
            $organgroup = array();
            array_push($organgroup, array(
                "name" => "Alle",
                "id" => "-1"
            ));
            while ($row = $result->fetch_array()) {
                array_push($organgroup, array(
                    "name" => $row["name"],
                    "id" => $row["id"]
                ));
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }

        $schnittquelle = null;
        $sql = "SELECT c.name,c.id FROM category c, schnittquelle s WHERE s.categoryId = c.id ORDER BY c.name ASC;";
        if ($result = $db->query($sql)) {
            $schnittquelle = array();
            array_push($schnittquelle, array(
                "name" => "Alle",
                "id" => "-1"
            ));
            while ($row = $result->fetch_array()) {
                array_push($schnittquelle, array(
                    "name" => $row["name"],
                    "id" => $row["id"]
                ));
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }

        $diagnosisgroup = null;
        $sql = "SELECT c.name,c.id FROM category c, diagnosisgroup d WHERE d.categoryId = c.id ORDER BY c.name ASC;";
        if ($result = $db->query($sql)) {
            $diagnosisgroup = array();
            array_push($diagnosisgroup, array(
                "name" => "Alle",
                "id" => "-1"
            ));
            while ($row = $result->fetch_array()) {
                array_push($diagnosisgroup, array(
                    "name" => $row["name"],
                    "id" => $row["id"]

                ));
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }

        $icd_0 = null;
        $sql = "SELECT c.name,c.id FROM category c, ICD_0 d WHERE d.categoryId = c.id ORDER BY c.name ASC;";
        if ($result = $db->query($sql)) {
            $icd_0 = array();
            array_push($icd_0, array(
                "name" => "Alle",
                "id" => "-1"
            ));
            while ($row = $result->fetch_array()) {
                array_push($icd_0, array(
                    "name" => $row["name"],
                    "id" => $row["id"]
                ));
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }

        $icd_10 = null;
        $sql = "SELECT c.name,c.id FROM category c, ICD_10 d WHERE d.categoryId = c.id ORDER BY c.name ASC;";
        if ($result = $db->query($sql)) {
            $icd_10 = array();
            array_push($icd_10, array(
                "name" => "Alle",
                "id" => "-1"
            ));
            while ($row = $result->fetch_array()) {
                array_push($icd_10, array(
                    "name" => $row["name"],
                    "id" => $row["id"]
                ));
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }

        $info = array(
            "semester" => $semesters,
            "lecturer" => $dozenten,
            "organ" => $organ,
            "organgroup" => $organgroup,
            "schnittquelle" => $schnittquelle,
            "diagnosisgroup" => $diagnosisgroup,
            "icd_0" => $icd_0,
            "icd_10" => $icd_10

        );
        $jsonResult["success"] = true;
        $jsonResult["info"] = $info;
        return $jsonResult;
    }


    public function getAllCategorysFromCut($cutId)
    {

        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );

        $info = null;
        $jsonResult["error"] = $cutId;
        include("../etc/db.php");
        $sql = "SELECT categoryId FROM  ttCutCategory WHERE cutId = '" . $cutId . "';";
        if ($result = $db->query($sql)) {
            $info = array();
            while ($row = $result->fetch_array()) {

                array_push($info, array(
                    "categoryId" => $row["categoryId"]
                ));
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error2"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }


        $jsonResult["success"] = true;
        $jsonResult["info"] = $info;
        return ($jsonResult);
    }

    public function getCategoryOfCut($sessionHash, $cutId)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        $info = array(
            "semester" => "",
            "dozent" => "",
            "organ" => "",
            "schnittquelle" => "",
            "organgruppe" => "",
            "diagnosegruppe" => "",
            "icd_0" => "",
            "icd_10" => ""

        );
        include_once("../classes/user.php");
        $user = new User();
        include("../etc/db.php");
        $sessionHash = $db->real_escape_string($sessionHash);
        $cutId = $db->real_escape_string($cutId);
        $sql = "SELECT categoryId FROM ttCutCategory WHERE cutId = '" . $cutId . "'";
        if ($result2 = $db->query($sql)) {

            while ($row = $result2->fetch_array()) {
                $isSemester = false;
                $isDozent = false;
                $isOrgan = false;
                $isSchnittquelle = false;
                $isOrgangruppe = false;
                $isDiagnosegruppe = false;
                $isICD_0 = false;
                $isICD_10 = false;
                $name = "";

                $sql = "SELECT * FROM category WHERE id = '" . $row["categoryId"] . "'";
                if ($result = $db->query($sql)) {
                    $resultArray = $result->fetch_array();
                    $name = $resultArray["name"];
                }


                $sql = "SELECT * FROM semester WHERE categoryId = '" . $row["categoryId"] . "'";
                if ($result = $db->query($sql)) {
                    if ($result->num_rows == 1) {
                        $isSemester = true;
                    }
                }
                $sql = "SELECT * FROM lecturer WHERE categoryId = '" . $row["categoryId"] . "'";

                if ($result = $db->query($sql)) {
                    if ($result->num_rows == 1) {
                        $isDozent = true;
                    }
                }
                $sql = "SELECT * FROM organ WHERE categoryId = '" . $row["categoryId"] . "'";

                if ($result = $db->query($sql)) {
                    if ($result->num_rows == 1) {
                        $isOrgan = true;
                    }
                }
                $sql = "SELECT * FROM schnittquelle WHERE categoryId = '" . $row["categoryId"] . "'";

                if ($result = $db->query($sql)) {
                    if ($result->num_rows == 1) {
                        $isSchnittquelle = true;
                    }
                }
                $sql = "SELECT * FROM diagnosisgroup WHERE categoryId = '" . $row["categoryId"] . "'";

                if ($result = $db->query($sql)) {
                    if ($result->num_rows == 1) {
                        $isDiagnosegruppe = true;
                    }
                }
                $sql = "SELECT * FROM ICD_0 WHERE categoryId = '" . $row["categoryId"] . "'";

                if ($result = $db->query($sql)) {
                    if ($result->num_rows == 1) {
                        $isICD_0 = true;
                    }
                }
                $sql = "SELECT * FROM ICD_10 WHERE categoryId = '" . $row["categoryId"] . "'";

                if ($result = $db->query($sql)) {
                    if ($result->num_rows == 1) {
                        $isICD_10 = true;
                    }
                }
                if ($isSemester) {
                    $info["semester"] = $info["semester"]  . $name . "; ";
                }
                if ($isOrgan) {
                    $info["organ"] = $info["organ"] . $name . "; ";
                }
                if ($isDozent) {
                    $info["dozent"] = $info["dozent"] . $name . "; ";
                }
                if ($isOrgangruppe) {
                    $info["organgruppe"] = $info["organgruppe"] . $name . "; ";
                }
                if ($isSchnittquelle) {
                    $info["schnittquelle"] = $info["schnittquelle"] . $name . "; ";
                }
                if ($isDiagnosegruppe) {
                    $info["diagnosegruppe"] = $info["diagnosegruppe"] . $name . "; ";
                }
                if ($isICD_0) {
                    $info["icd_0"] = $info["icd_0"] . $name . "; ";
                }
                if ($isICD_10) {
                    $info["icd_10"] = $info["icd_10"] . $name . "; ";
                }
            }
            $jsonResult["success"] = true;
            $jsonResult["info"] = $info;
        } else {
            $jsonResult["error"] = "Error by selecting Data (" . $db->error . ")";
            $jsonResult["success"] = false;
        }

        return $jsonResult;
    }

    public function deleteCategory($sessionHash, $category, $categoryId)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );

        include_once("../classes/user.php");
        $user = new User();
        include("../etc/db.php");
        if ($categoryId != "") {

            $sessionHash = $db->real_escape_string($sessionHash);
            $category = $db->real_escape_string($category);
            $categoryId = $db->real_escape_string($categoryId);

            $isAdmin = $user->isAdmin($sessionHash);
            $isAdmin = $isAdmin["success"];
            $delete = false;
            $delete2 = false;
            $delete3 = false;
            if (!$isAdmin) {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
                return $jsonResult;
            }
            $sql = "DELETE FROM ttCutCategory WHERE categoryId = '" . $categoryId . "';";
            if ($result = $db->query($sql)) {
                $delete = true;
            }
            $sql = "DELETE FROM " . $category . " WHERE categoryId = '" . $categoryId . "';";
            if ($result = $db->query($sql)) {
                $delete2 = true;
            }
            $sql = "DELETE FROM category WHERE id = '" . $categoryId . "';";
            if ($result = $db->query($sql)) {
                $delete3 = true;
            }
            $jsonResult["success"] = true;
            $jsonResult["info"] = "Filter deleted";
        } else {
            $jsonResult["success"] = false;

            $jsonResult["error"] = "Error by deleting Data (Filter id is null)";
        }
        return $jsonResult;
    }

    public function addCategory($userId, $category, $categoryName)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );

        include_once("../classes/user.php");
        include("../etc/db.php");

        $user = new User();
        $userId = $db->real_escape_string($userId);
        $category = $db->real_escape_string($category);
        $categoryName = $db->real_escape_string($categoryName);
        if ($category == "icd_0") {
            $category = "ICD_0";
        }
        if ($category == "icd_10") {
            $category = "ICD_10";
        }
        $isAdmin = $user->isAdmin($userId);
        $isAdmin = $isAdmin["success"];
        if (!$isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }
        $sql = "SELECT c.id FROM category c, " . $category . " s WHERE c.name like '" . $categoryName . "' AND c.id = s.categoryId ;";
        if ($result = $db->query($sql)) {
            $num_rows = $result->num_rows;
            if ($num_rows == 0) {
                $sql = "INSERT INTO category (name) VALUES ('" . $categoryName . "'); ";
                if ($result = $db->query($sql)) {
                    $sql = "SELECT * FROM category WHERE `id`= LAST_INSERT_ID();";
                    if ($result = $db->query($sql)) {
                        $row = $result->fetch_array();
                        $sql = "INSERT INTO " . $category . " (categoryId) VALUES ('" . $row["id"] . "'); ";
                        if ($result = $db->query($sql)) {
                            $jsonResult["success"] = true;
                            $jsonResult["info"] = "Filter added";
                        } else {
                            $jsonResult["success"] = false;
                            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                            $jsonResult["errorCode"] = "1";
                        }
                    } else {
                        $jsonResult["success"] = false;
                        $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                        $jsonResult["errorCode"] = "1";
                    }
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                    $jsonResult["errorCode"] = "1";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Filter exsistiert bereits.";
                $jsonResult["errorCode"] = "2";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }

        return $jsonResult;
    }


    public function putCategory($userId, $cutId, $categoryId)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );

        include_once("../classes/user.php");
        include_once("../etc/db.php");
        $user = new User();
        $isAdmin = $user->isAdmin($userId);
        $isAdmin = $isAdmin["success"];
        if (!$isAdmin) {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Sie haben keine Administrator Rechte.";
            return $jsonResult;
        }
        $sql = "SELECT * FROM ttCutCategory WHERE cutId = '" . $cutId . "' AND categoryId = '" . $categoryId . "' ;";
        if ($result = $db->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_array()) {
                    $sql2 = "DELETE FROM ttCutCategory WHERE id = '" . $row['id'] . "';";
                    if ($result2 = $db->query($sql2)) {
                        $jsonResult["success"] = true;

                        $jsonResult["info"] = "Filter deleted";
                    } else {
                        $jsonResult["success"] = false;
                        $jsonResult["error"] = "Error by data deleting (" . $db->error . ").";
                    }
                }
            } else {
                $sql = "INSERT INTO ttCutCategory (categoryId,cutId) VALUES ('" . $categoryId . "','" . $cutId . "'); ";
                if ($result = $db->query($sql)) {
                    $jsonResult["success"] = true;
                    $jsonResult["info"] = "Filter added";
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Error by data inserting (" . $db->error . ").";
                    $jsonResult["errorCode"] = "1";
                }
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }




        return $jsonResult;
    }
}
