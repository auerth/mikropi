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
echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Hier Registrieren und als Student vom Institut für Klinische Pathologie Freiburg Mikroskopschnitte schnell und einfach einsehen.", array("../css/login.css",'../css/form.css')));
?>

<body>
	<!-- Navigation -->
	<?php
	echo ($pageBuilder->getNavBar(false, false));
	?>
	<main class="login">
		<!-- Page Content -->
		<div class="container">
			<div class="wrapper wrapper--w790">
				<div class="card card-5">

				<div class="card-heading">
                    <h2 class="title">Registrieren</h2>
                </div>
				
					<div class="card-body">
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
						<form method="POST" id="register"  action="register.php">
							<div class="form-row">
								<div class="name">Vorname</div>
								<div class="value">
									<div class="input-group">
										<input class="input--style-5" type="text" id="forename" name="forename">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="name">Name</div>
								<div class="value">
									<div class="input-group">
										<input class="input--style-5" type="text" id="name" name="name">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="name">Email</div>
								<div class="value">
									<div class="input-group">
										<input class="input--style-5" type="email" id="email" name="email">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="name">Passwort</div>
								<div class="value">
									<div class="input-group">
										<input class="input--style-5" type="password" id="password" name="password">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="name">Passwort wiederholen</div>
								<div class="value">
									<div class="input-group">
										<input class="input--style-5" type="password" id="password_repeat" name="password_repeat">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="name">Matrikelnummer</div>
								<div class="value">
									<div class="input-group">
										<input class="input--style-5" type="text" id="matrikelnummer" name="matrikelnummer">
									</div>
								</div>
								<p style="color: white;">Falls du keine Immatrikulationsnummer besitzt, kannst du auch einfach "n/a" angeben.</p>

							</div>

							<div class="form-row p-t-20">
								<p><input type="checkbox" id="checkbox-agb" name="datenschutz" required /><label  style="color: white;" for="datenschutz" onclick="check('checkbox-agb')"><span></span> Ich habe die Nutzungsbedingungen und Hinweise zum Datenschutz gelesen und akzeptiere diese.</label> </p>
							</div>
							<div class="form-group center">
								<br /><img src="classes/captcha.php?rand=<?php echo rand(); ?>" id='captcha_image'>
							</div>
							<div class="form-group center">
								<input type="text" style=" width: 25% !important;" class="form-control" placeholder="Captcha" name="captcha" />
							</div>
							<div class="form-group center">
								<input type="button" class="btnSubmit" onclick="postForm()" value="Registrieren" style="  font-weight: 600;"/>
							</div>
						</form>
					</div>
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