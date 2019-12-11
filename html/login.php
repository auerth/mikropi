<!DOCTYPE html>


<?php
$error = null;
$classFiles = "../etc/classfiles.php";
include($classFiles);

if (file_exists($file_pagebuilder) && file_exists($file_user)) {
	include($file_user);
	include($file_pagebuilder);
	$redirect = null;
	$pageBuilder = new PageBuilder();
	$user = new User();

	$msg = null;
	if (isset($_COOKIE["loggedin_salt"]) && isset($_COOKIE["email"])) {
		$pwd = $_COOKIE["loggedin_salt"];
		$email = $_COOKIE["email"];
		if (strlen($email) > 1 && strlen($pwd) > 1) {
			$result = $user->login($email, $pwd);
			if ($result["errorCode"] == null && $result["success"]) {
				$expireTime = 3600 * 2;
				setcookie("name", $result["info"]["forename"] . " " . $result["info"]["name"], time() + $expireTime);
				setcookie("isAdmin", $result["info"]["isAdmin"], time() + $expireTime);
				setcookie("sessionHash", $result["info"]["sessionHash"], time() + $expireTime);
				setcookie("matrikelnummer", $result["info"]["matrikelnummer"], time() + $expireTime);
				setcookie("creationDate", $result["info"]["creationDate"], time() + $expireTime);
				header('Location: index.php?dash');
			} else {
				$error = $result["error"];
			}
		}
	}
	if (isset($_GET["redirect"])) {
		$redirect = $_GET["redirect"];
	}
	if (isset($_POST["email"]) && isset($_POST["password"])) {
		$keepLogin = $_POST["keep"];

		$result = $user->login($_POST["email"], hash('sha256', $_POST["password"]));
		if ($result["errorCode"] == null && $result["success"]) {
			$expireTime = 3600 * 2;
			$emailTime = time() + $expireTime;
			if ($keepLogin == "on") {
				setcookie("loggedin_salt", hash('sha256', $_POST["password"]), 2147483647);
				$emailTime = 2147483647;
			}
			setcookie("name", $result["info"]["forename"] . " " . $result["info"]["name"], time()+ $expireTime);
			setcookie("isAdmin", $result["info"]["isAdmin"], time() + $expireTime);
			setcookie("sessionHash", $result["info"]["sessionHash"], time() + $expireTime);
			setcookie("matrikelnummer", $result["info"]["matrikelnummer"], time() + $expireTime);
			setcookie("email", $result["info"]["email"], $emailTime);
			setcookie("creationDate", $result["info"]["creationDate"], time() + $expireTime);
			if (isset($_POST["redirect"])) {
				$redirect = $_POST["redirect"];
			}
			if ($redirect != null) {
				header('Location: ' . $redirect);
			} else {
				header('Location: index.php?dash');

			}
		} else {
			$error = $result["error"];
		}
	}
	if (isset($_GET["msg"])) {
		if ($_GET["msg"] == "email") {
			$msg = "Email wurde verifiziert.";
		}
		if ($_GET["msg"] == "forgot") {
			$msg = "Du findest dein neues Passwort in deinen Emails.";
		}
	}
} else {
	die("System Error! Support: admin@mikropi.de");
}



?>
<html lang="de">
<?php
echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Als Student vom Institut für klinische Pathologie Freiburg kannst du hier Mikroskopschnitte schnell und einfach einsehen.", array("../css/login.css")));
?>


<body>

	<!-- Navigation -->
	<?php
	echo ($pageBuilder->getNavBar(false, false));
	?>
	<main class="login">
		<!-- Page Content -->
		<div class="container login-container ">
			<div class="row">
				<div class="col-md-6 login-form-2">
					<h3>Login</h3>

					<?php
					if ($error != null) {
						echo ('<div class="alert alert-danger">' . $error . '</div>');
					}
					if ($msg != null) {
						echo ('<div class="alert alert-success">' . $msg . '</div>');
					}
					?>
					<form method="post" action="login.php">
						<div class="form-group">
							<input type="email" name="email" class="form-control" placeholder="Email" value="" required="true" />
						</div>
						<div class="form-group">
							<input type="password" name="password" class="form-control" placeholder="Passwort" value="" required="true" />
						</div>
						<?php
						echo ('<input name="redirect" value="' . $redirect . '" type="hidden" />');
						?>
						<div class="form-group left">
							<p><input type="checkbox" id="keep" name="keep"/><label for="keep" onclick="check('keep')" style="color: white;"><span></span> Eingeloggt bleiben</label> </p>
						</div>
						<div class="form-group">
							<input type="submit" class="btnSubmit" value="Login" />
						</div>
						<div class="form-group">
							<a href="forgotPassword.php" class="ForgetPwd" value="Login">Password
								vergessen?</a>
						</div>

					</form>
					<p style="color: white;">Bei technischen Fragen wende dich bitte an den Administrator: <a href="mailto: admin@mikropi.de">admin@mikropi.de</a></p>

				</div>
			</div>
		</div>

		<?php

		echo ($pageBuilder->getFooter());

		?>
	</main>


</body>
<!-- Bootstrap core JavaScript -->

<?php

echo ($pageBuilder->getJsTags());

?>





</html>