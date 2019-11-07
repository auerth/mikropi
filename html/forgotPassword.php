<!DOCTYPE html>


<?php
session_start();
$error = null;
$info = null;
$classFiles = "../etc/classfiles.php";
include($classFiles);


include($file_user);
include($file_pagebuilder);

$pageBuilder = new PageBuilder();

if (isset($_POST["email"]) && isset($_POST["matrikelnummer"])) {
	$user = new User();
	if (isset($_POST['captcha']) && ($_POST['captcha'] != "")) {
		if (strcasecmp($_SESSION['captcha'], $_POST['captcha']) == 0) {
			$result = $user->forgotPassword($_POST["email"], $_POST["matrikelnummer"]);
			if ($result["errorCode"] == null && $result["success"]) {
				$info = $result["info"];
				header("Location: login.php?msg=forgot");
			} else {
				$error = $result["error"];
			}
		} else {
			$error = "Captcha nicht richtig.";
		}
	} else {
		$error = "Captcha nicht richtig.";
	}
}

?>
<html lang="de">

<?php
echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Als Student vom Institut für klinische Pathologie Freiburg kannst du hier Mikroskopschnitte schnell und einfach einsehen.", array("../css/login.css")));
?>

<body>

	<!-- Navigation -->
	<?php
	echo ($pageBuilder->getNavBar($loggedIn, $isAdmin));
	?>
	<main>
		<!-- Page Content -->
		<div class="container login-container ">
			<div class="row">
				<div class="col-md-6 login-form-2">
					<h3>Passwort vergessen</h3>
					<?php
					if ($error != null) {
						echo ('<div class="alert alert-danger">' . $error . '
</div>');
					} else if ($info != null) {
						echo ('<div class="alert alert-success">' . $info . '
</div>');
					}
					?>
					<form method="post" action="forgotPassword.php">
						<div class="form-group">
							<input type="text" name="email" class="form-control" placeholder="Email" value="" required="true" />
						</div>
						<div class="form-group">
							<input type="text" name="matrikelnummer" class="form-control" placeholder="Immatrikulationsnummer" value="" required="true" />
						</div>
						<center>
							<div class="form-group">
								<p><br /><img src="classes/captcha.php?rand=<?php echo rand(); ?>" id='captcha_image'></p>
								<input type="text" style=" width: 25% !important;" class="form-control" name="captcha" />
							</div>
							<p style="color: white;">Sie können das Captcha nicht erkenne? <a href='javascript: refreshCaptcha();'>Dann hier drücken</a></p>
						</center><br>
						<div class="form-group">
							<input type="submit" class="btnSubmit" value="Neues Passwort generieren" />
						</div>

						<a href="login.php" class="ForgetPwd" value="">Zurück zum Login?</a>

					</form>
				</div>
			</div>
		</div>


	</main>
	<?php

	echo ($pageBuilder->getFooter());

	?>

</body>
<script>
	//Refresh Captcha
	function refreshCaptcha() {
		var img = document.images['captcha_image'];
		img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
	}
</script>
<?php

echo ($pageBuilder->getJsTags());


?>





</html>