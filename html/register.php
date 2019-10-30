<!DOCTYPE html>


<?php

$classFiles = "../etc/classfiles.php";
include($classFiles);
$error = null;
$info = null;
if (file_exists($file_pagebuilder) && file_exists($file_user) && file_exists($file_captcha)) {
	include($file_user);
	include($file_pagebuilder);
	include($file_captcha);
	$pageBuilder = new PageBuilder();
	if (isset($_POST["email"]) && isset($_POST["password"])) {
		$user = new User();
		$json = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretkey . '&response=' . $_POST['g-recaptcha-response']);
		$data = json_decode($json, true);
		if ($data["success"]) {
			$result = $user->register($_POST["email"], hash('sha256', $_POST["password"]), $_POST["matrikelnummer"], $_POST["name"], $_POST["forename"]);
			if ($result["errorCode"] == null) {
				$info = $result["info"];
			} else {
				$error = $result["error"];
			}
		} else {
			$error = "Captcha nicht richtig.";
		}
	}
}else{
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
	<main>
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
							<input type="text" name="name" id="name" class="form-control" placeholder="Name" value="" required="true" />
						</div>
						<div class="form-group">
							<input type="text" name="forename" id="forename" class="form-control" placeholder="Vorname" value="" required="true" />
						</div>
						<div class="form-group">
							<input type="text" name="email" id="email" class="form-control" placeholder="Email" value="" required="true" />
						</div>
						<div class="form-group">
							<input type="password" id="password" name="password" class="form-control" placeholder="Password" value="" required="true" autocomplete="off" />
						</div>
						<div class="form-group">
							<input type="password" id="password_repeat" name="" class="form-control" placeholder="Password wiederholen" value="" required="true" />
						</div>
						<div class="form-group">
							<input type="text" name="matrikelnummer" id="matrikelnummer" class="form-control" placeholder="Immatrikulationsnummer" value="" />
							<p style="color: white;">Falls du keine Immatrikulationsnummer besitzt, kannst du auch einfach "n/a" angeben.</p>

						</div>
						<div class="form-group left">
							<p><input type="checkbox" id="checkbox-agb" name="datenschutz" required /><label for="datenschutz" onclick="check('checkbox-agb')" style="color: white;"><span></span> Ich habe die Nutzungsbedingungen und Hinweise zum Datenschutz gelesen und akzeptiere diese.</label> </p>
						</div>
						<center>
							<div class="form-group">
								<div class="g-recaptcha" data-sitekey="6LcTf70UAAAAAGGOlOjgmHts4Sr0LbAsdnsnk1wZ"></div>
							</div>
						</center>
						<div class="form-group center">
							<input type="button" class="btnSubmit" onclick="postForm()" value="Registrieren" />
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
		echo ($pageBuilder->getFooter());

		?>
	</main>



</body>

<script src='https://www.google.com/recaptcha/api.js'></script>
<?php
$array = array("../js/register.js");
echo ($pageBuilder->getJsTags($array));

?>
<!-- Bootstrap core JavaScript -->






</html>