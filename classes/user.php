<?php

/**
 * The User Class - Register, login, delete and edit users
 * @author     Thorben Auer
 * @link       https://softwelop.com
 */
class User
{


    /**
     * User Login
     *
     * @param string   $email     Email of User Account
     * @param string   $password  Password of User Account (SHA-512)
     * 
     * @return array
     */
    public function login($email, $password)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        $email = strtolower(trim($email));

        include("../etc/db.php");
        require_once("../classes/sessionHash.php");
        $session = new SessionHash();
        $email = $db->real_escape_string($email);
        $password = $db->real_escape_string($password);
        $sql = "SELECT * FROM user WHERE email like '" . $email . "';";
        if ($result = $db->query($sql)) {
            $row_cnt = $result->num_rows;
            if ($row_cnt == 1) {
                $row = $result->fetch_array();
                if (strcmp($row["password"], $password) == 0) {
                    if ($this->isVerifydEmail($email)) {
                        if ($row["activated"] == true) {

                            $sessionHash = $session->createHash($row["id"]);
                            if ($sessionHash["success"]) {
                                $isAdmin = $this->isAdmin($sessionHash["info"]);
                                if ($isAdmin["success"]) {
                                    $isAdmin = true;
                                } else {
                                    $isAdmin = false;
                                }
                                $jsonResult["success"] = true;
                                $info = array(
                                    "sessionHash" => $sessionHash["info"],
                                    "email" => $row["email"],
                                    "forename" => $row["forename"],
                                    "name" => $row["name"],
                                    "matrikelnummer" => $row["matrikelnummer"],
                                    "creationDate" => strtotime($row["created"]),
                                    "isAdmin" => $isAdmin
                                );
                                $jsonResult["info"] = $info;
                            } else {
                                $jsonResult["success"] = false;
                                $jsonResult["error"] = $sessionHash["error"];
                                $jsonResult["errorCode"] = $sessionHash["errorCode"];
                            }
                        } else {
                            $jsonResult["success"] = false;
                            $jsonResult["error"] = "Account ist nicht aktiviert.";
                            $jsonResult["errorCode"] = "1";
                        }
                    } else {
                        $jsonResult["success"] = false;
                        $jsonResult["error"] = "Email Adresse ist noch nicht bestätigt. <a href='verifyagain.php' style='color: blue;'>Email erneut senden.</a>";
                        $jsonResult["errorCode"] = "2";
                    }
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Falsches Passwort.";
                    $jsonResult["errorCode"] = "2";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Account nicht gefunden.";
                $jsonResult["errorCode"] = "2";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }

        return $jsonResult;
    }
    /**
     * Add Matrikelnumber to Database to allow register
     *
     * @param string   $sessionHash     User who wants to add 
     * @param int   $matrikelnumber  Number to add
     * 
     * @return array
     */
    public function addMatrikelnumber($sessionHash, $matrikelnumber)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        $sessionHash = $db->real_escape_string($sessionHash);
        $matrikelnumber = $db->real_escape_string($matrikelnumber);
        $isAdmin = $this->isAdmin($sessionHash);
        if (!$isAdmin["success"]) {
            return $isAdmin;
        }
        $sql = "SELECT * FROM matrikelnumber WHERE number = '" . $matrikelnumber . "';";
        if ($result = $db->query($sql)) {
            $count = $result->num_rows;
            if ($count == 0) {
                $sql = "INSERT INTO matrikelnumber (number) VALUES ('" . $matrikelnumber . "');";
                if ($result = $db->query($sql)) {
                    $jsonResult["success"] = true;
                    $jsonResult["info"] = "Added Matrikelnumber to list";
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Error by data inserting (" . $db->error . ").";
                }
            } else {
                $jsonResult["success"] = true;
                $jsonResult["info"] = "Matrikelnummer schon vorhanden";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
        }
        return $jsonResult;
    }
    /**
     * Change Password
     *
     * @param string   $hash            User who wants to change his password
     * @param string   $newPassword     New password (SHA-512)
     * 
     * @return bool
     */
    public function changePassword($hash, $newPassword)
    {
        include("../etc/db.php");
        $hash = $db->real_escape_string($hash);
        $newPassword = $db->real_escape_string($newPassword);

        $sql = "SELECT userId FROM hash WHERE hash like '" . $hash . "';";
        if ($result = $db->query($sql)) {
            $row_cnt = $result->num_rows;
            if ($row_cnt == 1) {
                $userId = $result->fetch_array();
                $sql = "UPDATE user SET password = '" . $newPassword . "' WHERE id = '" . $userId["userId"] . "';";
                if ($result = $db->query($sql)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
            return false;
        } else {
            return false;
        }
    }

    /**
     * Get user id by hash
     *
     * @param string   $sessionHash     Hash of user
     * 
     * @return int
     */
    public function getUserId($sessionHash)
    {
        include("../etc/db.php");
        $sessionHash = $db->real_escape_string($sessionHash);
        $sql = "SELECT userId FROM hash WHERE hash like '" . $sessionHash . "';";
        if ($result = $db->query($sql)) {
            $userId = $result->fetch_array();
            return $userId["userId"];
        } else {
            return -1;
        }
    }

    /**
     * Check if password is equal to User Account
     *
     * @param string   $sessionHash     Hash of user to check password
     * @param string   $password        Password to check
     * 
     * @return bool
     */
    public function checkPassword($hash, $password)
    {
        include("../etc/db.php");
        $hash = $db->real_escape_string($hash);
        $password = $db->real_escape_string($password);
        $sql = "SELECT userId FROM hash WHERE hash like '" . $hash . "';";
        if ($result = $db->query($sql)) {
            $row_cnt = $result->num_rows;
            if ($row_cnt == 1) {
                $userId = $result->fetch_array();
                $sql = "SELECT * FROM user WHERE id = '" . $userId["userId"] . "' AND password like '" . $password . "';";
                if ($result = $db->query($sql)) {
                    if ($result->num_rows == 1) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
            return false;
        } else {
            return false;
        }
    }


    /**
     * Change email of user
     *
     * @param string   $hash            Hash of user to change email
     * @param string   $email           New email
     * 
     * @return bool
     */
    public function changeEmail($hash, $email)
    {
        include("../etc/db.php");
        $hash = $db->real_escape_string($hash);
        $email = $db->real_escape_string($email);
        $email = strtolower(trim($email));
        $sql = "SELECT userId FROM hash WHERE hash like '" . $hash . "';";
        if ($result = $db->query($sql)) {
            $row_cnt = $result->num_rows;
            if ($row_cnt == 1) {
                $userId = $result->fetch_array();
                $sql = "UPDATE user SET email = '" . $email . "' WHERE id = '" . $userId["userId"] . "';";
                if ($result = $db->query($sql)) {
                    $sql = "DELETE FROM verified_email WHERE userId = '" . $userId["userId"] . "';";
                    if ($result = $db->query($sql)) {
                        return true;
                    } else {

                        return true;
                    }
                } else {

                    return false;
                }
            } else {
                return false;
            }
            return false;
        } else {
            return false;
        }
    }


    /**
     * Delete User (Non Admin)
     *
     * @param string   $hash            Hash of user to delete
     * @param int   $matrikelnummer  Matrikelnumber of user (For confirmation)
     * @param string   $password        Password of user (For confirmation)
     * 
     * @return bool
     */
    public function deleteUser($hash, $matrikelnummer, $password)
    {
        include("../etc/db.php");
        $hash = $db->real_escape_string($hash);
        $matrikelnummer = $db->real_escape_string($matrikelnummer);
        $password = $db->real_escape_string($password);
        $matrikelnummer = trim($matrikelnummer);
        $sql = "SELECT userId FROM hash WHERE hash like '" . $hash . "';";
        if ($result = $db->query($sql)) {
            $row_cnt = $result->num_rows;
            if ($row_cnt == 1) {
                $userId = $result->fetch_array();
                $sql = "DELETE FROM user WHERE id = '" . $userId["userId"] . "' AND matrikelnummer like '" . $matrikelnummer . "' AND password like '" . $password . "';";
                if ($result = $db->query($sql)) {
                    $sql = "DELETE FROM admin WHERE userId ='" . $userId["userId"] . "'";
                    if ($result = $db->query($sql)) {
                        $sql = "DELETE FROM verified_email WHERE userId ='" . $userId["userId"] . "'";
                        if ($result = $db->query($sql)) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
            return false;
        } else {
            return false;
        }
    }



    /**
     * Delete User 
     *
     * @param string   $hash            Hash of user who wants to delete
     * @param int  $userId          Id of user to delete
     * 
     * @return array
     */
    public function deleteUserByAdmin($hash, $userId)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        $hash = $db->real_escape_string($hash);
        $userId = $db->real_escape_string($userId);
        if (!$this->isAdmin($hash)) {
            $jsonResult["error"] = "Keine Adminrechte.";
            return $jsonResult;
        }
        $sql = "DELETE FROM admin WHERE userId ='" . $userId . "'";
        if ($result = $db->query($sql)) {
            $sql = "DELETE FROM verified_email WHERE userId ='" . $userId . "'";
            if ($result = $db->query($sql)) {
                $sql = "DELETE FROM user WHERE id = '" . $userId . "';";
                if ($result = $db->query($sql)) {
                    $jsonResult["success"] = true;
                    $jsonResult["success"] = $userId;
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Löschen des Benutzers fehlgeschlagen. (" . $db->error . ")";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Löschen aus Admintabelle fehlgeschlagen. (" . $db->error . ")";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Löschen aus Email-Verifizierungstabelle fehlgeschlagen. (" . $db->error . ")";
        }
        return $jsonResult;
    }

    /**
     * Forgot Password
     *
     * @param string   $email               Email of user to reset password
     * @param string   $matrikelnummer      Matrikelnumber of User
     * 
     * @return array
     */
    public function forgotPassword($email, $matrikelnummer)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        $matrikelnummer = $db->real_escape_string($matrikelnummer);
        $email = $db->real_escape_string($email);

        $sql = "SELECT * FROM user WHERE email like '" . $email . "' AND matrikelnummer like '" . $matrikelnummer . "';";
        if ($result = $db->query($sql)) {
            $row_cnt = $result->num_rows;
            $name = $result->fetch_array();
            if ($row_cnt > 0) {
                $newPassword = $this->randomPassword();
                $newHash = hash('sha256', $newPassword);
                $sql = "UPDATE user SET password = '" . $newHash . "' WHERE email = '" . $email . "';";
                if ($result = $db->query($sql)) {
                    $to = $email;
                    $subject = '[NICHT ANTWORTEN] Information zur Passwort Änderung.';
                    $message = '
Hallo ' . $name["forename"] . ',<br><br>

mit dieser Email bestätigen wir dir die Änderung deines Passworts.<br><br>

Neues Passwort: ' . $newPassword . '<br><br>

Das Passwort kann jeder Zeit in den Einstellungen deines Accounts geändert werden.<br>

Bei Problemen wende dich bitte an folgende Email: support@mikropi.de.<br><br>

Mit freundlichen Grüßen<br><br>

';
                    include('../etc/signatur.php');
                    $message = $message . $signatur;
                    $header  = "MIME-Version: 1.0\r\n";
                    $header .= "Content-type: text/html; charset=utf-8\r\n";
                    $header .= "From: support@mikropi.de\r\n";
                    $header .= "Reply-To: support@mikropi.de\r\n";
                    $header .= "X-Mailer: PHP " . phpversion();
                    mail($to, $subject, $message, $header);
                    $jsonResult["success"] = true;
                    $jsonResult["info"] = "Dein neues Passwort wurde dir per Email zugesendet.";
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Error by data inserting (" . $db->error . ").";
                    $jsonResult["errorCode"] = "1";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Benutzer existiert nicht. Bitte überprüfen deine Eingaben.";
                $jsonResult["errorCode"] = "2";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }
        return $jsonResult;
    }


    /**
     * Create hash for email verification
     *
     * @param int   $userId               User Id of User to create Email Hash
     * 
     * @return string
     */
    public function createHashForEmail($userId)
    {
        include("../etc/db.php");
        $hash = bin2hex(random_bytes(16));
        $sql = "INSERT INTO verified_email (userId, hash, activated)
        VALUES ('" . $userId . "', '" . $hash . "',0);";
        if ($result = $db->query($sql)) {
            return $hash;
        } else {
            return "";
        }
        return "";
    }


    /**
     * Send Reverify Email if user changes Email
     *
     * @param string   $email               Email to verify
     * 
     * @return array
     */
    public function reVerifyEmail($email)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        $email = $db->real_escape_string($email);
        $email = strtolower(trim($email));
        $sql = "SELECT id ,forename FROM user WHERE email like '" . $email . "';";
        if ($result = $db->query($sql)) {
            $num = $result->num_rows;
            if ($num == 1) {
                $row = $result->fetch_array();
                $userId = $row["id"];
                $name = $userId["forename"];
                $sql = "DELETE FROM verified_email WHERE userId = '" . $userId . "'";
                if ($result = $db->query($sql)) {
                    $hash = $this->createHashForEmail($userId);
                    if ($hash != "") {
                        include('../etc/signatur.php');
                        $msg = "Hallo " . $name . ",<br><br>
                        Bitte klicke <a href='https://mikropi.de/verify.php?hash=" . $hash . "'>hier</a> um deine Email zu bestätigen.<br>Danach kannst du dich einloggen. <br><br>Mit freundlichen Grüßen<br><br>";
                        $msg = $msg . $signatur;
                        $header  = "MIME-Version: 1.0\r\n";
                        $header .= "Content-type: text/html; charset=utf-8\r\n";
                        $header .= "From: support@mikropi.de\r\n";
                        $header .= "Reply-To: support@mikropi.de\r\n";
                        $header .= "X-Mailer: PHP " . phpversion();
                        if (mail($email, 'Mikropi - Verifiziere deine Email', $msg, $header)) {
                            $logFile = "../logs/user.log";
                            $log = file_get_contents($logFile);
                            file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": Reverify Email  " . $email . " sent \n");
                        } else {
                            $logFile = "../logs/user.log";
                            $log = file_get_contents($logFile);
                            file_put_contents($logFile, $log . "Error-" . date('d/m/Y H:i:s', time()) . ": Reverify Email not sent to " . $email . "\n");
                        }
                        $jsonResult["success"] = true;
                        $jsonResult["info"] = "Verifizierungsemail gesendet";
                    }
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                    $jsonResult["errorCode"] = "1";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Account exsistiert nicht.";
                $jsonResult["errorCode"] = "1";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }
        return $jsonResult;
    }

    /**
     * Verify Email by hash
     *
     * @param string   $hash      Email Hash
     * 
     * @return array
     */
    public function verifyEmail($hash)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        $hash = $db->real_escape_string($hash);
        $sql = "SELECT * FROM verified_email WHERE hash like '" . $hash . "';";
        if ($result = $db->query($sql)) {
            $num = $result->num_rows;
            if ($num == 1) {
                $row = $result->fetch_array();
                $email = "";
                $sql = "SELECT email FROM user WHERE id = '" . $row["userId"] . "'";
                if ($result = $db->query($sql)) {
                    $num = $result->num_rows;
                    if ($num == 1) {
                        $email = $result->fetch_array()["email"];
                    }
                }
                $sql = "UPDATE verified_email SET activated = '1' WHERE id = '" . $row["id"] . "';";
                if ($result = $db->query($sql)) {
                    $jsonResult["success"] = true;
                    $logFile = "../logs/user.log";
                    $log = file_get_contents($logFile);
                    file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": Email verified: " . $email . "\n");
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Error by data selecting (Multiple data selected).";
                    $jsonResult["errorCode"] = "2";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Error by data selecting (Multiple data selected).";
                $jsonResult["errorCode"] = "2";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }
        return $jsonResult;
    }

    /**
     * Check if email is verified
     *
     * @param string   $email      Email
     * 
     * @return bool
     */
    public function isVerifydEmail($email)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        $userHash = $db->real_escape_string($userHash);
        $sql = "SELECT id FROM user WHERE email like '" . $email . "';";
        if ($result = $db->query($sql)) {
            $num = $result->num_rows;
            if ($num == 1) {
                $row = $result->fetch_array();
                $userId = $row["id"];
                $sql = "SELECT * FROM verified_email WHERE userId like '" . $userId . "' AND activated = 1;";
                if ($result = $db->query($sql)) {
                    $num = $result->num_rows;
                    if ($num == 1) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
        return false;
    }


    /**
     * Generate random passwort
     *
     * 
     * @return string
     */
    private function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzGdzVHQ4E7pvx6R98h7BXLpcar1LRvdSKNc90';
        $pass = array(); // remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; // put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); // turn the array into a string
    }


    /**
     * Register new User
     *
     * @param string   $email           Email of user
     * @param string   $password        Password of user (SHA-512)
     * @param int   $matrikelnummer  Matrikelnumber of user
     * @param string   $name            Name of user
     * @param string   $forename        Forename of user
     * 
     * @return array
     */
    public function register($email, $password, $matrikelnummer, $name, $forename)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        $email = $db->real_escape_string($email);
        $password = $db->real_escape_string($password);
        $matrikelnummer = $db->real_escape_string($matrikelnummer);
        $name = $db->real_escape_string($name);
        $forename = $db->real_escape_string($forename);
        $tmpMatrikel = '-1';
        $email = strtolower(trim($email));
        $matrikelnummer = trim($matrikelnummer);
        $name = trim($name);
        $forename = trim($forename);
        if ($matrikelnummer != 'n/a') {
            $tmpMatrikel = $matrikelnummer;
        }
        if (strlen($name) <= 30 && strlen($forename) <= 30) {


            $sql = "SELECT * FROM user WHERE email like '" . $email . "' OR matrikelnummer like '" . $tmpMatrikel . "';";
            if ($result = $db->query($sql)) {
                $row_cnt = $result->num_rows;
                $active = 1;
                if ($row_cnt == 0) {

                    $sql = "INSERT INTO user (email, password, matrikelnummer, name, forename,activated)
                        VALUES ('" . $email . "', '" . $password . "', '" . $matrikelnummer . "', '" . $name . "', '" . $forename . "','" . $active . "'); ";
                    $logFile = "../logs/user.log";
                    $log = file_get_contents($logFile);
                    file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": User registered " . $email . "\n");
                    if ($result = $db->query($sql)) {
                        $sql = "SELECT * FROM user WHERE email like '" . $email . "'";
                        if ($result = $db->query($sql)) {
                            $row = $result->fetch_array();
                            $rowId = $row["id"];

                            $hash = $this->createHashForEmail($rowId);
                            if ($hash != "") {
                                $email = $row["email"];
                                include('../etc/signatur.php');
                                $header  = "MIME-Version: 1.0\r\n";
                                $header .= "Content-type: text/html; charset=utf-8\r\n";
                                $header .= "From: support@mikropi.de\r\n";
                                $header .= "Return-Path: support@mikropi.de\r\n"; // Return path for errors
                                $header .= "Reply-To: support@mikropi.de\r\n";
                                $header .= "X-Mailer: PHP " . phpversion();
                                $msg = "Hallo " . $forename . ",<br><br>dein Account wurde nun erstellt. <br>Bitte klicke <a href='https://mikropi.de/verify.php?hash=" . $hash . "'>hier</a> um deine Email zu bestätigen.<br>Danach kannst du dich einloggen. <br><br>Mit freundlichen Grüßen<br><br>";
                                $msg = $msg . $signatur;
                                if (mail($email, 'Mikropi - Verifiziere deine Email', $msg, $header)) {
                                    $logFile = "../logs/user.log";
                                    $log = file_get_contents($logFile);
                                    file_put_contents($logFile, $log . "INFO-" . date('d/m/Y H:i:s', time()) . ": Verify Email  " . $email . " sent\n");
                                } else {
                                    $logFile = "../logs/user.log";
                                    $log = file_get_contents($logFile);
                                    file_put_contents($logFile, $log . "Error-" . date('d/m/Y H:i:s', time()) . ": Verify Email not sent " . $email . "\n");
                                }
                                $jsonResult["success"] = true;
                                $jsonResult["info"] = "Account erstellt. Bitte bestätige deine Email. Wir haben dir eine Email geschickt.";
                                if (!$active) {
                                    $jsonResult["info"] = $jsonResult["info"] . " Dein Account wird von einem Mitarbeiter in 1 bis 2 Tagen aktiviert. Du wirst per Email benachrichtigt.";
                                }
                            }
                        }
                    } else {
                        $jsonResult["success"] = false;
                        $jsonResult["error"] = "Error by data inserting (" . $db->error . ").";
                        $jsonResult["errorCode"] = "1";
                    }
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["error"] = "Email oder Immatrikulationsnummer wird schon genutzt.";
                    $jsonResult["errorCode"] = "2";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["error"] = "Name oder Nachname zu lang (Maximal 15 Zeichen)";
                $jsonResult["errorCode"] = "1";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            $jsonResult["errorCode"] = "1";
        }

        return $jsonResult;
    }

    /**
     * Check if hash is admin
     *
     * @param string   $hash           Hash of User
     * 
     * @return array
     */
    public function isAdmin($hash)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        $hash = $db->real_escape_string($hash);
        $sql = "SELECT userId FROM hash WHERE hash like '" . $hash . "';";
        if ($result = $db->query($sql)) {
            $row_cnt = $result->num_rows;
            if ($row_cnt == 1) {
                $userId = $result->fetch_array();
                $sql = "SELECT * FROM admin WHERE userId = '" . $userId["userId"] . "';";
                if ($result = $db->query($sql)) {
                    $row_cnt = $result->num_rows;
                    if ($row_cnt == 1) {
                        $jsonResult["success"] = true;
                    } else if ($row_cnt > 1) {
                        $jsonResult["success"] = false;
                        $jsonResult["errorCode"] = 1;
                        $jsonResult["error"] = "Error by data selecting (Request error).";
                    } else {
                        $jsonResult["success"] = false;
                        $jsonResult["errorCode"] = 2;
                        $jsonResult["error"] = "No Admin.";
                    }
                } else {
                    $jsonResult["success"] = false;
                    $jsonResult["errorCode"] = 1;
                    $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["errorCode"] = 1;
                $jsonResult["error"] = "Error by data selecting (" . $row_cnt . ").";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["errorCode"] = 1;
            $jsonResult["error"] = "Error by data selecting3 (" . $db->error . ").";
        }
        return $jsonResult;
    }
    /**
     * Get list of registered users
     *
     * @param string   $hash                     Hash of User
     * @param string   $sortBy    (optional)     Value to sort list
     * 
     * @return array
     */
    public function getUserList($hash, $sortBy = 'name')
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        $hash = $db->real_escape_string($hash);

        $isAdmin = $this->isAdmin($hash);
        $isAdmin = $isAdmin["success"];
        if ($isAdmin) {
            if ($sortBy != "admin") {
                $sql = "SELECT * FROM user ORDER BY " . $sortBy . ";";
            } else {
                $sql = "SELECT * FROM user ORDER BY name;";
            }
            if ($result = $db->query($sql)) {
                $info = array();
                while ($row = $result->fetch_array()) {
                    $sql = "SELECT id FROM admin WHERE userId = '" . $row["id"] . "';";
                    $admin = false;
                    if ($isAdmin = $db->query($sql)) {
                        $count = $isAdmin->num_rows;
                        if ($count == 1) {
                            $admin = true;
                        }
                    }
                    $sql = "SELECT activated FROM verified_email WHERE userId = '" . $row["id"] . "';";
                    $verifyed = false;
                    if ($verifyResult = $db->query($sql)) {
                        $count = $verifyResult->num_rows;
                        if ($count == 1) {
                            if ($verifyResult->fetch_array()["activated"] == "1") {
                                $verifyed = true;
                            }
                        }
                    }

                    $sql = "SELECT MAX( UNIX_TIMESTAMP(timestamp)) FROM hash WHERE userId = '" . $row["id"] . "';";
                    $lastLogin = 0;
                    if ($result2 = $db->query($sql)) {
                        $count = $result2->num_rows;
                        if ($count == 1) {
                            $row2 = $result2->fetch_array();
                            $lastLogin = $row2[0];
                            $lastLogin = $lastLogin;
                        }
                    }
                    if ($sortBy == "admin") {
                        if ($admin) {
                            array_push($info, array(
                                "id" => $row["id"],
                                "name" => $row["name"],
                                "forename" => $row["forename"],
                                "activated" => filter_var($row["activated"], FILTER_VALIDATE_BOOLEAN),
                                "admin" => $admin,
                                "created" => strtotime($row["created"]),
                                "matrikelnummer" => $row["matrikelnummer"],
                                "email" => $row["email"],
                                "last_login" => $lastLogin,
                                "verifyed" => $verifyed
                            ));
                        }
                    } else {
                        array_push($info, array(
                            "id" => $row["id"],
                            "name" => $row["name"],
                            "forename" => $row["forename"],
                            "activated" => filter_var($row["activated"], FILTER_VALIDATE_BOOLEAN),
                            "admin" => $admin,
                            "created" => strtotime($row["created"]),
                            "matrikelnummer" => $row["matrikelnummer"],
                            "email" => $row["email"],
                            "last_login" => $lastLogin,
                            "verifyed" => $verifyed
                        ));
                    }
                }
                $jsonResult["success"] = true;
                $jsonResult["info"] = $info;
            } else {
                $jsonResult["success"] = false;
                $jsonResult["errorCode"] = 1;
                $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["errorCode"] = 2;
            $jsonResult["error"] = "No Admin.";
        }
        return $jsonResult;
    }


    /**
     * Update user
     *
     * @param string   $hash             Hash of User
     * @param bool  $activated        Is Activated
     * @param int   $editUserId       Id of user to edit
     * @param bool   $setAdmin         Is Admin
     * 
     * @return array
     */
    public function updateUser($hash, $activated, $editUserId, $setAdmin)
    {
        $jsonResult = array(
            'success' => false,
            'errorCode' => 0,
            'error' => null,
            'info' => null
        );
        include("../etc/db.php");
        $userId = $db->real_escape_string($hash);
        $activated = $db->real_escape_string($activated);
        $editUserId = $db->real_escape_string($editUserId);
        $setAdmin = $db->real_escape_string($setAdmin);
        $isAdmin = $this->isAdmin($hash);
        $isAdmin = $isAdmin["success"];

        if ($isAdmin) {
            $sql = "SELECT activated,forename,email FROM user WHERE id = '" . $editUserId . "';";
            $wasActivated = "0";
            $forename = "";
            $email = "";
            if ($result = $db->query($sql)) {
                $result = $result->fetch_array();
                $forename = $result["forename"];
                $wasActivated = $result["activated"];
                $email = $result["email"];
            }
            $sql = "UPDATE user SET activated = '" . $activated . "' WHERE id = '" . $editUserId . "';";
            if ($result = $db->query($sql)) {
                if ($wasActivated != $activated) {
                    $to = $email;
                    $subject = '[NICHT ANTWORTEN] Änderung in deinem Account.';
                    $value = "deaktiviert";
                    if (strcmp($activated, "0") == 1) {
                        $value = "aktiviert";
                    }
                    $message = '
                                Hallo ' . $forename . ',<br><br>

                                deine Account für mikropi wurde soeben ' . $value . '.<br><br>

                                Bei Fragen wende dich bitte an folgende Email: support@mikropi.de.<br><br>

                                Mit freundlichen Grüßen<br><br>

                                ';
                    include('../etc/signatur.php');
                    $message = $message . $signatur;
                    $header  = "MIME-Version: 1.0\r\n";
                    $header .= "Content-type: text/html; charset=utf-8\r\n";
                    $header .= "From: support@mikropi.de\r\n";
                    $header .= "Reply-To: support@mikropi.de\r\n";
                    $header .= "X-Mailer: PHP " . phpversion();
                    mail($to, $subject, $message, $header);
                }

                $sql = "SELECT id FROM admin WHERE userId='" . $editUserId . "';";
                if ($result = $db->query($sql)) {
                    $numrow = $result->num_rows;
                    if ($setAdmin == true && $numrow == 1) {
                        $jsonResult["success"] = true;
                        $jsonResult["info"] = "Benutzer wurde aktualisiert";
                    } else if ($setAdmin == true && $numrow != 1) {
                        $sql = "INSERT INTO admin (userId)
								VALUES ('" . $editUserId . "'); ";
                        if ($result = $db->query($sql)) {
                            $jsonResult["success"] = true;
                            $jsonResult["info"] = "User updated";
                        } else {
                            $jsonResult["success"] = false;
                            $jsonResult["errorCode"] = 1;
                            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                        }
                    } else if ($setAdmin == false && $numrow == 1) {
                        $sql = "DELETE FROM admin WHERE userId='" . $editUserId . "'";
                        if ($result = $db->query($sql)) {
                            $jsonResult["success"] = true;
                            $jsonResult["info"] = "Benutzer wurde aktualisiert";
                        } else {
                            $jsonResult["success"] = false;
                            $jsonResult["errorCode"] = 1;
                            $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                        }
                    } else {
                        $jsonResult["success"] = true;
                        $jsonResult["info"] = "Users updated";
                    }
                } else {

                    $jsonResult["success"] = false;
                    $jsonResult["errorCode"] = 1;
                    $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
                }
            } else {
                $jsonResult["success"] = false;
                $jsonResult["errorCode"] = 1;
                $jsonResult["error"] = "Error by data selecting (" . $db->error . ").";
            }
        } else {
            $jsonResult["success"] = false;
            $jsonResult["errorCode"] = 2;
            $jsonResult["error"] = "No Admin.";
        }
        return $jsonResult;
    }
}
