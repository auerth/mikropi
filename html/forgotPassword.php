<!DOCTYPE html>


<?php
$error = null;
$info = null;
$classFiles = "../etc/classfiles.php";
include($classFiles);


include($file_user);
include($file_pagebuilder);

$pageBuilder = new PageBuilder();

if (isset($_POST["email"]) && isset($_POST["matrikelnummer"])) {
	$user = new User();
	include("../etc/recaptcha.php");
	$json = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretkey.'&response=' . $_POST['g-recaptcha-response']);
	$data = json_decode($json, true);
	if ($data["success"]) {
		$result = $user->forgotPassword($_POST["email"], $_POST["matrikelnummer"]);
		if ($result["errorCode"] == null && $result["success"]) {
			$info = $result["info"];
			header("Location: login.php?msg=forgot");
		} else {
			$error = $result["error"];
		}
	}else{
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
								<div class="g-recaptcha" data-sitekey="6LcTf70UAAAAAGGOlOjgmHts4Sr0LbAsdnsnk1wZ"></div>
							
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

<script src='https://www.google.com/recaptcha/api.js'></script>
<!-- Bootstrap core JavaScript -->
<?php

echo ($pageBuilder->getJsTags());


?>





</html>