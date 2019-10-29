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
$email = "Test";
$matrikelnummer = "";
$erstelltAm = "01.01.1970";
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

	if (isset($_POST["newEmail"]) && isset($_POST["password"]) && $_POST["newEmail"] != null) {
		$pw = $user->checkPassword($userId, hash("sha256", $_POST["password"]));
		if ($pw) {
			$newEmail = $_POST["newEmail"];
			$changeEmail = $user->changeEmail($userId, $newEmail);
			if ($changeEmail) {
				$emailChangeInfo = "Email wurde geändert. Die Änderungen werden erst beim nächsten Login sichtbar.";
				$alertType = "alert-success";
			}
		} else {
			$emailChangeInfo = "Das eingegebene Passwort stimmt nicht.";
			$alertType = "alert-danger";
		}
	}
	if (isset($_POST["newPassword"]) && isset($_POST["password"]) && isset($_POST["newPasswordRepeate"])) {
		$pw = $user->checkPassword($userId, hash("sha256", $_POST["password"]));
		if ($pw) {
			$newPassword = $_POST["newPassword"];
			$newPasswordRepeate = $_POST["newPasswordRepeate"];
			if (strcmp($newPassword, $newPasswordRepeate) == 0) {
				if ($user->changePassword($userId, hash("sha256", $newPassword))) {
					$newPasswordInfo = "Passwort wurde geändert.";
					$alertType = "alert-success";
				}
			} else {
				$newPasswordInfo = "Die eingegebenen Passwörter stimmen nicht überein.";
				$alertType = "alert-danger";
			}
		} else {
			$newPasswordInfo = "Das eingegebene Passwort stimmt nicht.";
			$alertType = "alert-danger";
		}
	}
	if (isset($_POST["password"]) && isset($_POST["matrikelnummer"])) {

		$passwordd = $_POST["password"];
		$matrikelnummer = $_POST["matrikelnummer"];
		if ($user->deleteUser($userId, $matrikelnummer, hash("sha256", $newPassword))) {
			header("Location: login.php");
		} else {
			$newPasswordInfo = "Das eingegebene Passwort stimmt nicht.";
			$alertType = "alert-danger";
		}
	}
}
if (!$loggedIn) {
    header("Location: login.php?redirect=settings.php");
}
?>
<?php
/**
 * Get Header from PageBuilder Class
 * 
 * @see ../classes/pagebuilder.php
 */

$array = array("../css/login.css");
echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Als Student vom Institut für klinische Pathologie Freiburg kannst du hier Mikroskopschnitte schnell und einfach einsehen.",$array));
?>

<body>

	<!-- Navigation -->
	<?php
	echo ($pageBuilder->getNavBar($loggedIn, $isAdmin));
	?>

	<!-- Page Content -->
	<main>
		<div class="card">
			<h5 class="card-header bg-2nd text-white"><?php echo ($name); ?></h5>
			<div class="card-body">
				<ul class="list-group">
					<li class="list-group-item">Email: <?php echo ($email); ?></li>
					<li class="list-group-item">Immatrikulationsnummer: <?php echo ($matrikelnummer); ?></li>
					<li class="list-group-item">Erstellt am: <?php echo (date("d.m.Y", $erstelltAm)); ?></li>
				</ul>
			</div>
		</div>

		<div class="card">
			<h5 class="card-header bg-2nd text-white">Email ändern</h5>
			<div class="card-body">
				<?php
				if ($emailChangeInfo != null) {
					echo ('<div class="alert ' . $alertType . '">' . $emailChangeInfo . '
                      </div>');
				}
				?>
				<form method="post" action="settings.php">
					<div class="form-group">
						<input type="text" readonly onfocus="this.removeAttribute('readonly');" name="newEmail" class="form-control" placeholder="Neue Email" value="" required="true" />
					</div>
					<div class="form-group">
						<input type="password" readonly onfocus="this.removeAttribute('readonly');" name="password" class="form-control" placeholder="Aktuelles Passwort" value="" required="true" />
					</div>
					<div class="form-group">
						<input type="submit" class="btnSubmit" value="Email ändern" />
					</div>
				</form>
			</div>
		</div>
		<div class="card">
			<h5 class="card-header bg-2nd text-white">Passwort ändern</h5>
			<div class="card-body">
				<?php
				if ($newPasswordInfo != null) {
					echo ('<div class="alert ' . $alertType . '">' . $newPasswordInfo . '
											</div>');
				}
				?>
				<form method="post" action="settings.php">
					<div class="form-group">
						<input type="password" readonly onfocus="this.removeAttribute('readonly');" name="password" class="form-control" placeholder="Altes Passwort" value="" required="true" />
					</div>
					<div class="form-group">
						<input type="password" readonly onfocus="this.removeAttribute('readonly');" name="newPassword" class="form-control" placeholder="Neues Passwort" value="" required="true" />
					</div>
					<div class="form-group">
						<input type="password" readonly onfocus="this.removeAttribute('readonly');" name="newPasswordRepeate" class="form-control" placeholder="Neues Passwort wiederholen" value="" required="true" />
					</div>
					<div class="form-group">
						<input type="submit" class="btnSubmit" value="Passwort ändern" />
					</div>
				</form>
			</div>
		</div>
		<div class="card">
			<h5 class="card-header bg-2nd text-white">Account löschen</h5>
			<div class="card-body">

				<form method="post" autocomplete="off" action="settings.php">
					<div class="form-group">
						<input type="text" name="matrikelnummer" readonly onfocus="this.removeAttribute('readonly');" id="matrikelnummer" class="form-control" placeholder="Immatrikulationsnummer" value="" required="true" />
					</div>
					<div class="form-group">
						<input type="password" name="newPassword" readonly onfocus="this.removeAttribute('readonly');" class="form-control" placeholder="Aktuelles Passwort" value="" required="true" />
					</div>

					<div class="form-group">
						<input type="submit" class="btnSubmit bg-danger text-white" value="Account löschen?" />
					</div>
				</form>
			</div>
		</div>
		<?php

		echo ($pageBuilder->getFooter());

		?>
	</main>

</body>
<!-- Bootstrap core JavaScript -->
<?php
if (isset($_GET["cuts"]) && $_GET["cuts"] > 0) {
	echo ($cut->getCutImage($_GET["cuts"]));
}

$array = array("../js/list.js");
echo ($pageBuilder->getJsTags($array));
?>



</html>