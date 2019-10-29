<!DOCTYPE html>


<?php
$error = null;
include("../classes/user.php");
include("../classes/pagebuilder.php");
$redirect = null;
$pageBuilder = new PageBuilder();

if (isset($_POST["email"])) {
	include("../etc/recaptcha.php");
    $json = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretkey.'&response=' . $_POST['g-recaptcha-response']);
	$data = json_decode($json, true);
	if ($data["success"]) {
	$user = new User();
	$result = $user->reVerifyEmail($_POST["email"]);
	if ($result["errorCode"] == null && $result["success"]) {
		$msg = $result["info"];
	} else {
		$error = $result["error"];
    }
}else{
    $error ="Captcha Falsch";

}
}


?>
<html lang="de">
<?php
echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Als Student vom Institut für klinische Pathologie Freiburg kannst du hier Mikroskopschnitte schnell und einfach einsehen.",array("../css/login.css")));
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
								<div class="g-recaptcha" data-sitekey="6LcTf70UAAAAAGGOlOjgmHts4Sr0LbAsdnsnk1wZ"></div>
							
						</center>
						<div class="form-group">
							<input type="submit" class="btnSubmit" value="Verifizieren" />
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
<!-- Bootstrap core JavaScript -->
<script src='https://www.google.com/recaptcha/api.js'></script>
<?php

echo ($pageBuilder->getJsTags());

?>





</html>