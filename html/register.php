<!DOCTYPE html>


<?php
session_start();

$classFiles = "../etc/classfiles.php";
include($classFiles);
$error = null;
$info = null;
if (file_exists($file_pagebuilder) && file_exists($file_user) && file_exists($file_captcha)) {
	include($file_user);
	include($file_pagebuilder);
	$pageBuilder = new PageBuilder();
	if (isset($_POST["email"]) && isset($_POST["password"])) {
		$user = new User();
		if (isset($_POST['captcha']) && ($_POST['captcha'] != "")) {
			if (strcasecmp($_SESSION['captcha'], $_POST['captcha']) == 0) {
				$result = $user->register($_POST["email"], hash('sha256', $_POST["password"]), $_POST["matrikelnummer"], $_POST["name"], $_POST["forename"]);
				if ($result["errorCode"] == null) {
					$info = $result["info"];
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
} else {
	die("System Error! Support: admin@mikropi.de");
}




?>
<html lang="de">

<?php
/**
 * Get Header from PageBuilder Class
 * 
 * @see ../classes/pagebuilder.php
 */
echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Hier Registrieren und als Student vom Institut für Klinische Pathologie Freiburg Mikroskopschnitte schnell und einfach einsehen.", array("../css/login.css")));
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
					<h3>Registrieren</h3>

					<?php
					if ($error != null) {
						echo ('<div class="alert alert-danger">' . $error . '
</div>');
					}
					if ($info != null) {
						echo ('<div class="alert alert-success">' . $info . '
</div>');
					}
					?>
					<form method="post" id="register" action="register.php">
						<div class="form-group">
							<input type="text" name="name" id="name" maxLength="15" class="form-control" placeholder="Name" value="" required="true" />
						</div>
						<div class="form-group">
							<input type="text" name="forename" id="forename" maxLength="15" class="form-control" placeholder="Vorname" value="" required="true" />
						</div>
						<div class="form-group">
							<input type="text" name="email" id="email" class="form-control" placeholder="Email" value="" required="true" />
						</div>
						<div class="form-group">
							<input type="password" id="password" name="password" class="form-control" placeholder="Passwort" value="" required="true" autocomplete="off" />
						</div>
						<div class="form-group">
							<input type="password" id="password_repeat" name="" class="form-control" placeholder="Passwort wiederholen" value="" required="true" />
						</div>
						<div class="form-group">
							<input type="text" name="matrikelnummer" id="matrikelnummer"  maxLength="10" class="form-control" placeholder="Immatrikulationsnummer" value="" />
							<p style="color: white;">Falls du keine Immatrikulationsnummer besitzt, kannst du auch einfach "n/a" angeben.</p>

						</div>
						<div class="form-group left">
							<p><input type="checkbox" id="checkbox-agb" name="datenschutz" required /><label for="datenschutz" onclick="check('checkbox-agb')" style="color: white;"><span></span> Ich habe die Nutzungsbedingungen und Hinweise zum Datenschutz gelesen und akzeptiere diese.</label> </p>
						</div>
						<center>
							<div class="form-group">
								<p><br /><img src="classes/captcha.php?rand=<?php echo rand(); ?>" id='captcha_image'></p>
								<input type="text" style=" width: 25% !important;" class="form-control" name="captcha" />
							</div>
							<p style="color: white;">Sie können das Captcha nicht erkenne? <a href='javascript: refreshCaptcha();'>Dann hier drücken</a></p>
						</center>
						<div class="form-group center">
							<input type="button" class="btnSubmit" onclick="postForm()" value="Registrieren" />
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

<?php
$array = array("../js/register.js");
echo ($pageBuilder->getJsTags($array));

?>
<!-- Bootstrap core JavaScript -->

<script>
	//Refresh Captcha
	$('input[type="text"]').blur(function() {
		$(window).scrollTop(0, 0);
	});

	function refreshCaptcha() {
		var img = document.images['captcha_image'];
		img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
	}
</script>




</html>