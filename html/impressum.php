<!DOCTYPE html>
<html lang="de">
<?php
include("../classes/pagebuilder.php");

$loggedIn = false;
$isAdmin = false;
$serverUrl = "https://mikropi.de/";
$pageBuilder = new PageBuilder();

$name = "";
if (isset($_COOKIE["sessionHash"]) && $_COOKIE["sessionHash"] != -1) {
	$loggedIn = true;
	if (isset($_COOKIE["isAdmin"])) {
		$isAdmin = $_COOKIE["isAdmin"];
	}
	if (isset($_COOKIE["name"])) {
		$name = $_COOKIE["name"];
	}
} else {
	$loggedIn = false;
}
?>
<?php
echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Als Student vom Institut für klinische Pathologie Freiburg kannst du hier Mikroskopschnitte schnell und einfach einsehen."));
?>

<body>

	<!-- Navigation -->
	<?php
	echo ($pageBuilder->getNavBar($loggedIn, $isAdmin));
	?>

	<!-- Page Content -->
	<main>
		<h1>Impressum</h1>
		<div class="impressum">
			<h4>Angaben gemäß § 5 TMG:</h4>

			<h5>

				Universitätsklinikum Freiburg<br>
				Breisacher Straße 153 D<br>
				79110 Freiburg<br>

			</h5>
			<p>Das Universitätsklinikum Freiburg ist eine rechtsfähige Anstalt des
				öffentlichen Rechts der Albert-Ludwigs-Universität Freiburg.
				Vertreten durch: Vorstand: Leitender Ärztlicher Direktor: Prof. Dr.
				Frederik Wenz (Vorsitz) Kaufmännischer Direktor:
				Dipl.-Verwaltungswirt (FH) Bernd Sahner Stellvertretender Leitender
				Ärztlicher Direktor: Prof. Dr. Dr. Rainer Schmelzeisen Dekan der
				Medizinischen Fakultät: Prof. Dr. Norbert Südkamp (komm.)
				Pflegedirektor: Helmut Schiffer Aufsichtsrat: Vorsitzender:
				Ministerialdirigent Clemens Benz Stellvertretender Vorsitzender:
				Rektor Prof. Dr. Dr. h.c. Hans-Jochen Schiewer</p>
			<p>


				<h5>Kontakt:</h5>
				<ul>
					<li>Telefon: +49 761 270-0</li>
					<li>Telefax: +49 761 270-20200</li>
					<li>E-Mail: <a href="mailto:info@uniklinik-freiburg.de">info@uniklinik-freiburg.de</a></li>
				</ul>
			</p>
			<p>


				<h5>Umsatzsteuer:</h5>
				<ul>
					<li>Umsatzsteuer-Identifikationsnummer gemäß §27 a
						Umsatzsteuergesetz: DE 811506626</li>
				</ul>
			</p>
			<p>


				<h5>Aufsichtsbehörde:</h5>
				<ul>
					<li>Name: Ministerium für Wissenschaft, Forschung und Kunst</li>
					<li>Anschrift: Königstraße 46, 70173 Stuttgart</li>
					<li>Link: <a href="https://mwk.baden-wuerttemberg.de/">https://mwk.baden-wuerttemberg.de/</a></li>
				</ul>
			</p>
			<p>


				<h5>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV:</h5>
				<ul>
					<li>Benjamin Waschow, Leiter Stabsstelle Unternehmenskommunikation</li>
					<li>Breisacher Straße 153 D</li>
					<li>79110 Freiburg</li>
				</ul>
			</p>

			<p>


				<h5>Angaben zur Berufshaftpflichtversicherung</h5>
				<ul>
					<li>Name und Sitz des Versicherers: HDI Global SE, Riethorst 4, 30659
						Hannover</li>
					<li>Räumliche Geltung: : Deutschland</li>
				</ul>
			</p>
			<p>


				<h5>Haftung für Inhalte</h5>
				Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte
				auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach
				§§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht
				verpflichtet, übermittelte oder gespeicherte fremde Informationen zu
				überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige
				Tätigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der
				Nutzung von Informationen nach den allgemeinen Gesetzen bleiben
				hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem
				Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei
				Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese
				Inhalte umgehend entfernen.
			</p>
			<p>


				<h5>Haftung für Links</h5>
				Unser Angebot enthält Links zu externen Websites Dritter, auf deren
				Inhalte wir keinen Einfluss haben. Deshalb können wir für diese
				fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der
				verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der
				Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der
				Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige
				Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine
				permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne
				konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei
				Bekanntwerden von Rechtsverletzungen werden wir derartige Links
				umgehend entfernen.
			</p>
			<p>


				<h5>Urheberrecht</h5>
				Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen
				Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung,
				Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der
				Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des
				jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite
				sind nur für den privaten, nicht kommerziellen Gebrauch gestattet.
				Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt
				wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden
				Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf
				eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen
				entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen
				werden wir derartige Inhalte umgehend entfernen.
			</p>
			<p>


				<h5>Gesamtrealisation</h5>
				<ul>
					<li>Ketchum Pleon GmbH</li>
					<li>Goetheallee 23</li>
					<li>01309 Dresden</li>
					<li>E-Mail: <a href="mailto:info@ketchumpleon.com">info@ketchumpleon.com</a></li>
				</ul>
				<ul>
					<li>Amedick & Sommer GmbH</li>
					<li>Klinikmarketing</li>
					<li>Charlottenstraße 29/31</li>
					<li>70182 Stuttgart</li>
					<li>E-Mail: <a href="mailto:info@amedick-sommer.de">info@amedick-sommer.de</a></li>
					<li>Web: <a href="www.amedick-sommer.de">www.amedick-sommer.de</a></li>
				</ul>
				<ul>
					<li>Klinikrechenzentrum</li>
					<li>Agnesenstrasse 6-8</li>
					<li>79106 Freiburg</li>
					<li>E-Mail: <a href="mailto:webmaster@uniklinik-freiburg.de">webmaster@uniklinik-freiburg.de</a></li>
				</ul>
			</p>
		</div>
		<?php

		echo ($pageBuilder->getFooter());

		?>
	</main>

	<!-- Bootstrap core JavaScript -->
	<?php
	if (isset($_GET["cuts"]) && $_GET["cuts"] > 0) {
		echo ($cut->getCutImage($_GET["cuts"]));
	}
	?>

	<?php
	echo ($pageBuilder->getJsTags());



	?>

</body>








</html>