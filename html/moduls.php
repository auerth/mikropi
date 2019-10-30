<?php

$classFiles = "../etc/classfiles.php";
if (file_exists($classFiles)) {
	include($classFiles);
	include($file_cut);
	include($file_category);
	include($file_overlay);
	include($file_script);
	include($file_modul);
	include($file_pagebuilder);

	$loggedIn = false;
	$isAdmin = false;
	$serverUrl = "https://mikropi.de/";
	$name = "";
	$modul = new Modul();
	$script = new Script();
	$pageBuilder = new PageBuilder();

	if (isset($_COOKIE["sessionHash"]) && $_COOKIE["sessionHash"] != "-1") {
		$loggedIn = true;
		$sessionHash = $_COOKIE["sessionHash"];
		if (isset($_GET["id"]))
			$modulId = $_GET["id"];
		if (isset($_COOKIE["isAdmin"])) {
			$isAdmin = $_COOKIE["isAdmin"];
		}
		if (isset($_COOKIE["name"])) {
			$name = $_COOKIE["name"];
		}
		$cut = new Cut();

		if (isset($_FILES["pdf"])) {
			$pdf = $_FILES["pdf"];
			$result = $script->editModulPDF($sessionHash, $modulId, $pdf);
			if ($result["success"]) {
				$msg = $result["info"];
			} else {
				$msg = $result["error"];
			}
		}
		if (isset($_POST["cutList"])) {
			$cutList = $_POST["cutList"];
			$result = $script->addCutToModul($sessionHash, $cutList, $modulId);
			if ($result["success"]) {
				$msg = $result["info"];
			} else {
				$msg = $result["error"];
			}
		}
	} else { }
	if (!$loggedIn) {
		$actual_link = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("Location: login.php?redirect=" . $actual_link);
	}
} else {
	die("System Error! Support: admin@mikropi.de");
}
?>
<!DOCTYPE html>
<html lang="de">
<?php

echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Als Student vom Institut für klinische Pathologie Freiburg kannst du hier Mikroskopschnitte schnell und einfach einsehen."));
?>



<body>

	<!-- Navigation -->
	<?php
	$name = "";
	if (isset($_COOKIE["name"])) {
		$name = $_COOKIE["name"];
	}
	echo ($pageBuilder->getNavBar($loggedIn, $isAdmin));
	?>

	<!-- Page Content -->
	<main>
		<?php
		if (!isset($_GET["id"])) {
			echo ('	<div class="moduls">');
			$moduls = $modul->getModuls();
			$moduls = $moduls["info"];
			foreach ($moduls as $item) {
				echo ("<div class='item' id='" . $item["id"] . "'>");
				echo ($item["name"]);
				echo ("</div>");
			}
		} else {
			$modulId = $_GET["id"];
			$result = $script->getModulScript($modulId);
			$scriptHtml = "";
			$msg = "";
			if ($result["success"]) {
				$cutListf = $result["cutList"];
				$modulPath = $result["scriptPDF"];
			} else {
				$msg = $result["error"];
			}

			if ($isAdmin) {

				echo ('<form enctype="multipart/form-data" action="" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="-1" />
		Diese Datei hochladen: <input name="pdf" type="file" />
		<input type="submit" value="PDF hochladen" />
	</form>');
			}
			if ($modulPath != "") {


				echo ('<embed src= "' . $modulPath . '" width= "100%" >');
			}

			$result = $cut->getCutsFiltered(-1, -1, -1, -1, -1, -1, -1, -1);
			$result = json_decode($result, true);
			$cuts = "";
			if ($result["errorCode"] == null) {
				$cuts = $result["info"];
			} else {
				$error = $result["error"];
			}


			if ($isAdmin) {




				echo ("<div class'row col-12'><button type='submit' style='margin-left: auto; margin-top: 10px; margin-bottom: 10px; margin-right: 10px; float: right;' id='saveModul' class='btn btn-primary' >Speichern</button></div>");
				echo ('<div id="listAdmin" class="gridbox">');
				foreach ($cuts as $item) {
					echo ('
								<div id="' . $item["id"] . '" class="box">
									<img id="' . $item["id"] . '" class="mr-3" src="' . $serverUrl . $item["thumbnail"] . '"  alt="No Thumbnail">
									<p id="' . $item["id"] . '" class="mt-0">' . $item["name"] . '</p>
								</div>
							');
				}
				echo ("</div>");
			} else {
				echo ('<div id="listCutr" class="cutcontainer">');
				foreach ($cuts as $item) {
					if (in_array($item["id"], $cutListf)) {
						echo ('
								<div id="' . $item["id"] . '" class="card">
									<img id="' . $item["id"] . '" class="thumbnail" src="' . $serverUrl . $item["thumbnail"] . '"  alt="No Thumbnail">
													<h3 id="' . $item["id"] . '" class="title">' . $item["name"] . '</h3>
									
								</div>
							');
					}
				}
				echo ("</div>");
			}
		}
		?>
		</div>
		<?php

		echo ($pageBuilder->getFooter());

		?>
	</main>
	<?php

	$array = array("../js/modul.js");
	echo ($pageBuilder->getJsTags($array));


	?>
	<!-- Bootstrap core JavaScript -->


	<?php
	if ($isAdmin && isset($_GET["id"])) {
		foreach ($cutListf as $cutId) {
			echo ("<script type='text/javascript'> markCut(" . $cutId . ")</script>");
		}
		echo ("<script type='text/javascript'> var isAdmin = true;</script>");
	} else {
		echo ("<script type='text/javascript'> var isAdmin = false;</script>");
	}

	?>

</body>








</html>