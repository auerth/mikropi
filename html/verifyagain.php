<!DOCTYPE html>


<?php
$error = null;

$classFiles = "../etc/classfiles.php";
include($classFiles);

include($file_user);
include($file_pagebuilder);
$redirect = null;
$pageBuilder = new PageBuilder();

if (isset($_POST["email"])) {
	include("../etc/recaptcha.php");
	if (isset($_POST['captcha']) && ($_POST['captcha'] != "")) {
		if (strcasecmp($_SESSION['captcha'], $_POST['captcha']) == 0) {
			$user = new User();
			$result = $user->reVerifyEmail($_POST["email"]);
			if ($result["errorCode"] == null && $result["success"]) {
				$msg = $result["info"];
			} else {
				$error = $result["error"];
			}
		} else {
			$error = "Captcha Falsch";
		}
	} else {
		$error = "Captcha Falsch";
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
	echo ($pageBuilder->getNavBar(false, false));
	?>
	<main>
		<!-- Page Content -->
		<div class="container login-container ">
			<div class="row">
				<div class="col-md-6 login-form-2">
					<h3>Email erneut verifizieren</h3>
					<?php
					if ($error != null) {
						echo ('<div class="alert alert-danger">' . $error . '</div>');
					}
					if ($msg != null) {
						echo ('<div class="alert alert-success">' . $msg . '</div>');
					}
					?>
					<form method="post" action="verifyagain.php">
						<div class="form-group">
							<input type="text" name="email" class="form-control" placeholder="Email" value="" required="true" />
						</div>
						<center>
							<div class="form-group">
								<p><br /><img src="classes/captcha.php?rand=<?php echo rand(); ?>" id='captcha_image'></p>
								<input type="text" name="captcha" />
							</div>
							<p style="color: white;">Sie können das Captcha nicht erkenne? <a href='javascript: refreshCaptcha();'>Dann hier drücken</a></p>
						</center>
						<br>
						<div class="form-group">
							<input type="submit" class="btnSubmit" value="Verifizieren" />
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