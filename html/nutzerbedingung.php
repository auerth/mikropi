<!DOCTYPE html>
<html lang="de">
<?php
include("../classes/pagebuilder.php");

$loggedIn = false;
$isAdmin = false;
$serverUrl = "https://mikropi.de/";
$name = "";
$pageBuilder = new PageBuilder();

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
echo ($pageBuilder->getHead("Mikropi - Das Online Mikroskop", "Mikropi - Das Online Mikroskop. Unsere Nutzerbedingungen und Datenschutzerklärung findest du hier.", array("../css/datenschutz.css")));
?>





<body>

    <!-- Navigation -->
    <?php
    echo ($pageBuilder->getNavBar($loggedIn, $isAdmin));
    ?>

    <!-- Page Content -->
    <main>
        <div class="datenschutz">
        <h1>Datenschutzerklärung</h1>
<p>Verantwortlicher im Sinne der Datenschutzgesetze, insbesondere der EU-Datenschutzgrundverordnung (DSGVO), ist:</p>
<p>Universitätsklinikum Freiburg
<br>Breisacher Straße 153 D
<br>79110 Freiburg</p>
<h2>Ihre Betroffenenrechte</h2>
<p>Unter den angegebenen Kontaktdaten unseres Datenschutzbeauftragten können Sie jederzeit folgende Rechte ausüben:</p>
<ul>
<li>Auskunft über Ihre bei uns gespeicherten Daten und deren Verarbeitung (Art. 15 DSGVO),</li>
<li>Berichtigung unrichtiger personenbezogener Daten (Art. 16 DSGVO),</li>
<li>Löschung Ihrer bei uns gespeicherten Daten (Art. 17 DSGVO),</li>
<li>Einschränkung der Datenverarbeitung, sofern wir Ihre Daten aufgrund gesetzlicher Pflichten noch nicht löschen dürfen (Art. 18 DSGVO),</li>
<li>Widerspruch gegen die Verarbeitung Ihrer Daten bei uns (Art. 21 DSGVO) und</li>
<li>Datenübertragbarkeit, sofern Sie in die Datenverarbeitung eingewilligt haben oder einen Vertrag mit uns abgeschlossen haben (Art. 20 DSGVO).</li>
</ul>
<p>Sofern Sie uns eine Einwilligung erteilt haben, können Sie diese jederzeit mit Wirkung für die Zukunft widerrufen.</p>
<p>Sie können sich jederzeit mit einer Beschwerde an eine Aufsichtsbehörde wenden, z. B. an die zuständige Aufsichtsbehörde des Bundeslands Ihres Wohnsitzes oder an die für uns als verantwortliche Stelle zuständige Behörde.</p>
<p>Eine Liste der Aufsichtsbehörden (für den nichtöffentlichen Bereich) mit Anschrift finden Sie unter: <a href="https://www.bfdi.bund.de/DE/Infothek/Anschriften_Links/anschriften_links-node.html" target="_blank" rel="nofollow noopener">https://www.bfdi.bund.de/DE/Infothek/Anschriften_Links/anschriften_links-node.html</a>.</p>
<p></p><h2>Erfassung allgemeiner Informationen beim Besuch unserer Website</h2>
<h3>Art und Zweck der Verarbeitung:</h3>
<p>Wenn Sie auf unsere Website zugreifen, d.h., wenn Sie sich nicht registrieren oder anderweitig Informationen übermitteln, werden automatisch Informationen allgemeiner Natur erfasst. Diese Informationen (Server-Logfiles) beinhalten etwa die Art des Webbrowsers, das verwendete Betriebssystem, den Domainnamen Ihres Internet-Service-Providers, Ihre IP-Adresse und ähnliches. </p>
<p>Sie werden insbesondere zu folgenden Zwecken verarbeitet:</p>
<ul>
<li>Sicherstellung eines problemlosen Verbindungsaufbaus der Website,</li>
<li>Sicherstellung einer reibungslosen Nutzung unserer Website,</li>
<li>Auswertung der Systemsicherheit und -stabilität sowie</li>
<li>zu weiteren administrativen Zwecken.</li>
</ul>
<p>Wir verwenden Ihre Daten nicht, um Rückschlüsse auf Ihre Person zu ziehen. Informationen dieser Art werden von uns ggfs. statistisch ausgewertet, um unseren Internetauftritt und die dahinterstehende Technik zu optimieren.</p>
<h3>Rechtsgrundlage:</h3>
<p>Die Verarbeitung erfolgt gemäß Art. 6 Abs. 1 lit. f DSGVO auf Basis unseres berechtigten Interesses an der Verbesserung der Stabilität und Funktionalität unserer Website.</p>
<h3>Empfänger:</h3>
<p>Empfänger der Daten sind ggf. technische Dienstleister, die für den Betrieb und die Wartung unserer Webseite als Auftragsverarbeiter tätig werden.</p>
<h3>Speicherdauer:</h3>
<p>Die Daten werden gelöscht, sobald diese für den Zweck der Erhebung nicht mehr erforderlich sind. Dies ist für die Daten, die der Bereitstellung der Webseite dienen, grundsätzlich der Fall, wenn die jeweilige Sitzung beendet ist.</p>
<h3>Bereitstellung vorgeschrieben oder erforderlich:</h3>
<p>Die Bereitstellung der vorgenannten personenbezogenen Daten ist weder gesetzlich noch vertraglich vorgeschrieben. Ohne die IP-Adresse ist jedoch der Dienst und die Funktionsfähigkeit unserer Website nicht gewährleistet. Zudem können einzelne Dienste und Services nicht verfügbar oder eingeschränkt sein. Aus diesem Grund ist ein Widerspruch ausgeschlossen. </p>
<p></p><h2>Cookies</h2>
<h3>Art und Zweck der Verarbeitung:</h3>
<p>Wie viele andere Webseiten verwenden wir auch so genannte „Cookies“. Bei Cookies handelt es sich um kleine Textdateien, die auf Ihrem Endgerät (Laptop, Tablet, Smartphone o.ä.) gespeichert werden, wenn Sie unsere Webseite besuchen. </p>
<p>Hierdurch erhalten wir bestimmte Daten wie z. B. IP-Adresse, verwendeter Browser und Betriebssystem.</p>
<p>Cookies können nicht verwendet werden, um Programme zu starten oder Viren auf einen Computer zu übertragen. Anhand der in Cookies enthaltenen Informationen können wir Ihnen die Navigation erleichtern und die korrekte Anzeige unserer Webseiten ermöglichen.</p>
<p>In keinem Fall werden die von uns erfassten Daten an Dritte weitergegeben oder ohne Ihre Einwilligung eine Verknüpfung mit personenbezogenen Daten hergestellt.</p>
<p>Natürlich können Sie unsere Website grundsätzlich auch ohne Cookies betrachten. Internet-Browser sind regelmäßig so eingestellt, dass sie Cookies akzeptieren. Im Allgemeinen können Sie die Verwendung von Cookies jederzeit über die Einstellungen Ihres Browsers deaktivieren. Bitte verwenden Sie die Hilfefunktionen Ihres Internetbrowsers, um zu erfahren, wie Sie diese Einstellungen ändern können. Bitte beachten Sie, dass einzelne Funktionen unserer Website möglicherweise nicht funktionieren, wenn Sie die Verwendung von Cookies deaktiviert haben.</p>
<h3>Speicherdauer und eingesetzte Cookies:</h3>
<p>Soweit Sie uns durch Ihre Browsereinstellungen oder Zustimmung die Verwendung von Cookies erlauben, können folgende Cookies auf unseren Webseiten zum Einsatz kommen:</p>
<p>creationDate,matrikelnummer,userId,isAdmin,name,sessionHash</p>
<p>Soweit diese Cookies (auch) personenbezogene Daten betreffen können, informieren wir Sie darüber in den folgenden Abschnitten.</p>
<p>Sie können über Ihre Browsereinstellungen einzelne Cookies oder den gesamten Cookie-Bestand löschen. Darüber hinaus erhalten Sie Informationen und Anleitungen, wie diese Cookies gelöscht oder deren Speicherung vorab blockiert werden können. Je nach Anbieter Ihres Browsers finden Sie die notwendigen Informationen unter den nachfolgenden Links:</p>
<ul>
<li>Mozilla Firefox: <a href="https://support.mozilla.org/de/kb/cookies-loeschen-daten-von-websites-entfernen" target="_blank" rel="nofollow noopener">https://support.mozilla.org/de/kb/cookies-loeschen-daten-von-websites-entfernen</a></li>
<li>Internet Explorer: <a href="https://support.microsoft.com/de-de/help/17442/windows-internet-explorer-delete-manage-cookies" target="_blank" rel="nofollow noopener">https://support.microsoft.com/de-de/help/17442/windows-internet-explorer-delete-manage-cookies</a></li>
<li>Google Chrome: <a href="https://support.google.com/accounts/answer/61416?hl=de" target="_blank" rel="nofollow noopener">https://support.google.com/accounts/answer/61416?hl=de</a></li>
<li>Opera: <a href="http://www.opera.com/de/help" target="_blank" rel="nofollow noopener">http://www.opera.com/de/help</a></li>
<li>Safari: <a href="https://support.apple.com/kb/PH17191?locale=de_DE&viewlocale=de_DE" target="_blank" rel="nofollow noopener">https://support.apple.com/kb/PH17191?locale=de_DE&viewlocale=de_DE</a></li>
</ul>
<p></p><h2>Registrierung auf unserer Website</h2>
<h3>Art und Zweck der Verarbeitung:</h3>
<p>Bei der Registrierung für die Nutzung unserer personalisierten Leistungen werden einige personenbezogene Daten erhoben, wie Name, Anschrift, Kontakt- und Kommunikationsdaten (z. B. Telefonnummer und E-Mail-Adresse). Sind Sie bei uns registriert, können Sie auf Inhalte und Leistungen zugreifen, die wir nur registrierten Nutzern anbieten. Angemeldete Nutzer haben zudem die Möglichkeit, bei Bedarf die bei Registrierung angegebenen Daten jederzeit zu ändern oder zu löschen. Selbstverständlich erteilen wir Ihnen darüber hinaus jederzeit Auskunft über die von uns über Sie gespeicherten personenbezogenen Daten.</p>
<h3>Rechtsgrundlage:</h3>
<p>Die Verarbeitung der bei der Registrierung eingegebenen Daten erfolgt auf Grundlage einer Einwilligung des Nutzers (Art. 6 Abs. 1 lit. a DSGVO).</p>
<p>Dient die Registrierung der Erfüllung eines Vertrages, dessen Vertragspartei die betroffene Person ist oder der Durchführung vorvertraglicher Maßnahmen, so ist zusätzliche Rechtsgrundlage für die Verarbeitung der Daten Art. 6 Abs. 1 lit. b DSGVO.</p>
<h3>Empfänger:</h3>
<p>Empfänger der Daten sind ggf. technische Dienstleister, die für den Betrieb und die Wartung unserer Website als Auftragsverarbeiter tätig werden.</p>
<h3>Speicherdauer:</h3>
<p>Daten werden in diesem Zusammenhang nur verarbeitet, solange die entsprechende Einwilligung vorliegt. Danach werden sie gelöscht, soweit keine gesetzlichen Aufbewahrungspflichten entgegenstehen. Zur Kontaktaufnahme in diesem Zusammenhang nutzen Sie bitte die am Ende dieser Datenschutzerklärung angegebenen Kontaktdaten.</p>
<h3>Bereitstellung vorgeschrieben oder erforderlich:</h3>
<p>Die Bereitstellung Ihrer personenbezogenen Daten erfolgt freiwillig, allein auf Basis Ihrer Einwilligung. Ohne die Bereitstellung Ihrer personenbezogenen Daten können wir Ihnen keinen Zugang auf unsere angebotenen Inhalte und Leistungen gewähren.</p>
<p></p><h2>Kontaktformular</h2>
<h3>Art und Zweck der Verarbeitung:</h3>
<p>Die von Ihnen eingegebenen Daten werden zum Zweck der individuellen Kommunikation mit Ihnen gespeichert. Hierfür ist die Angabe einer validen E-Mail-Adresse sowie Ihres Namens erforderlich. Diese dient der Zuordnung der Anfrage und der anschließenden Beantwortung derselben. Die Angabe weiterer Daten ist optional.</p>
<h3>Rechtsgrundlage:</h3>
<p>Die Verarbeitung der in das Kontaktformular eingegebenen Daten erfolgt auf der Grundlage eines berechtigten Interesses (Art. 6 Abs. 1 lit. f DSGVO).</p>
<p>Durch Bereitstellung des Kontaktformulars möchten wir Ihnen eine unkomplizierte Kontaktaufnahme ermöglichen. Ihre gemachten Angaben werden zum Zwecke der Bearbeitung der Anfrage sowie für mögliche Anschlussfragen gespeichert.</p>
<p>Sofern Sie mit uns Kontakt aufnehmen, um ein Angebot zu erfragen, erfolgt die Verarbeitung der in das Kontaktformular eingegebenen Daten zur Durchführung vorvertraglicher Maßnahmen (Art. 6 Abs. 1 lit. b DSGVO).</p>
<h3>Empfänger:</h3>
<p>Empfänger der Daten sind ggf. Auftragsverarbeiter.</p>
<h3>Speicherdauer:</h3>
<p>Daten werden spätestens 6 Monate nach Bearbeitung der Anfrage gelöscht.</p>
<p>Sofern es zu einem Vertragsverhältnis kommt, unterliegen wir den gesetzlichen Aufbewahrungsfristen nach HGB und löschen Ihre Daten nach Ablauf dieser Fristen. </p>
<h3>Bereitstellung vorgeschrieben oder erforderlich:</h3>
<p>Die Bereitstellung Ihrer personenbezogenen Daten erfolgt freiwillig. Wir können Ihre Anfrage jedoch nur bearbeiten, sofern Sie uns Ihren Namen, Ihre E-Mail-Adresse und den Grund der Anfrage mitteilen.</p>
<p></p><h2>Verwendung von Google Analytics</h2>
<h3>Art und Zweck der Verarbeitung:</h3>
<p>Diese Website benutzt Google Analytics, einen Webanalysedienst der Google LLC, 1600 Amphitheatre Parkway, Mountain View, CA 94043 USA (nachfolgend: „Google“). Google Analytics verwendet sog. „Cookies“, also Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Webseite durch Sie ermöglichen. Die durch das Cookie erzeugten Informationen über Ihre Benutzung dieser Webseite werden in der Regel an einen Server von Google in den USA übertragen und dort gespeichert. Aufgrund der Aktivierung der IP-Anonymisierung auf diesen Webseiten, wird Ihre IP-Adresse von Google jedoch innerhalb von Mitgliedstaaten der Europäischen Union oder in anderen Vertragsstaaten des Abkommens über den Europäischen Wirtschaftsraum zuvor gekürzt. Nur in Ausnahmefällen wird die volle IP-Adresse an einen Server von Google in den USA übertragen und dort gekürzt. Im Auftrag des Betreibers dieser Website wird Google diese Informationen benutzen, um Ihre Nutzung der Webseite auszuwerten, um Reports über die Webseitenaktivitäten zusammenzustellen und um weitere mit der Websitenutzung und der Internetnutzung verbundene Dienstleistungen gegenüber dem Webseitenbetreiber zu erbringen. Die im Rahmen von Google Analytics von Ihrem Browser übermittelte IP-Adresse wird nicht mit anderen Daten von Google zusammengeführt.</p>
<p>Die Zwecke der Datenverarbeitung liegen in der Auswertung der Nutzung der Website und in der Zusammenstellung von Reports über Aktivitäten auf der Website. Auf Grundlage der Nutzung der Website und des Internets sollen dann weitere verbundene Dienstleistungen erbracht werden.</p>
<h3>Rechtsgrundlage:</h3>
<p>Die Verarbeitung der Daten erfolgt auf Grundlage einer Einwilligung des Nutzers (Art. 6 Abs. 1 lit. a DSGVO).</p>
<h3>Empfänger:</h3>
<p>Empfänger der Daten ist Google als Auftragsverarbeiter. Hierfür haben wir mit Google den entsprechenden Auftragsverarbeitungsvertrag abgeschlossen.</p>
<h3>Speicherdauer:</h3>
<p>Die Löschung der Daten erfolgt, sobald diese für unsere Aufzeichnungszwecke nicht mehr erforderlich sind.</p>
<h3>Drittlandtransfer:</h3>
<p>Google verarbeitet Ihre Daten in den USA und hat sich dem EU_US Privacy Shield unterworfen <a href="https://www.privacyshield.gov/EU-US-Framework">https://www.privacyshield.gov/EU-US-Framework</a>. </p>
<h3>Bereitstellung vorgeschrieben oder erforderlich:</h3>
<p>Die Bereitstellung Ihrer personenbezogenen Daten erfolgt freiwillig, allein auf Basis Ihrer Einwilligung. Sofern Sie den Zugriff unterbinden, kann es hierdurch zu Funktionseinschränkungen auf der Website kommen.</p>
<h3>Widerruf der Einwilligung:</h3>
<p>Sie können die Speicherung der Cookies durch eine entsprechende Einstellung Ihrer Browser-Software verhindern; wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht sämtliche Funktionen dieser Website vollumfänglich werden nutzen können. Sie können darüber hinaus die Erfassung der durch das Cookie erzeugten und auf Ihre Nutzung der Webseite bezogenen Daten (inkl. Ihrer IP-Adresse) an Google sowie die Verarbeitung dieser Daten durch Google verhindern, indem sie das unter dem folgenden Link verfügbare Browser-Plugin herunterladen und installieren: <a href="http://tools.google.com/dlpage/gaoptout?hl=de">Browser Add On zur Deaktivierung von Google Analytics</a>.</p>
<p>Zusätzlich oder als Alternative zum Browser-Add-On können Sie das Tracking durch Google Analytics auf unseren Seiten unterbinden, indem Sie <a title="Google Analytics Opt-Out-Cookie setzen" onClick="gaOptout();alert('Google Analytics wurde deaktiviert');" href="#">diesen Link anklicken</a>. Dabei wird ein Opt-out-Cookie auf Ihrem Gerät installiert. Damit wird die Erfassung durch Google Analytics für diese Website und für diesen Browser zukünftig verhindert, so lange das Cookie in Ihrem Browser installiert bleibt.</p>
<h3>Profiling:</h3>
<p>Mit Hilfe des Tracking-Tools Google Analytics kann das Verhalten der Besucher der Webseite bewertet und die Interessen analysiert werden. Hierzu erstellen wir ein pseudonymes Nutzerprofil.</p>
<p></p><h2>SSL-Verschlüsselung</h2>
<p>Um die Sicherheit Ihrer Daten bei der Übertragung zu schützen, verwenden wir dem aktuellen Stand der Technik entsprechende Verschlüsselungsverfahren (z. B. SSL) über HTTPS.</p>
<p></p><h2>Änderung unserer Datenschutzbestimmungen</h2>
<p>Wir behalten uns vor, diese Datenschutzerklärung anzupassen, damit sie stets den aktuellen rechtlichen Anforderungen entspricht oder um Änderungen unserer Leistungen in der Datenschutzerklärung umzusetzen, z.B. bei der Einführung neuer Services. Für Ihren erneuten Besuch gilt dann die neue Datenschutzerklärung.</p>
<h2>Fragen an den Datenschutzbeauftragten</h2>
<p>Wenn Sie Fragen zum Datenschutz haben, schreiben Sie uns bitte eine E-Mail oder wenden Sie sich direkt an die für den Datenschutz verantwortliche Person in unserer Organisation:</p>
<p>Universitätsklinikum Freiburg
<br>Breisacher Straße 153 D
<br>79110 Freiburg
<br>Vertretungsberechtigte Personen: Maximilian Seidl</p>
<p><em>Die Datenschutzerklärung wurde mithilfe der activeMind AG erstellt, den Experten für <a href="https://www.activemind.de/datenschutz/datenschutzhinweis-generator/" target="_blank" rel="noopener">externe Datenschutzbeauftragte</a> (Version #2019-04-10).</em></p>

        </div>

        <div class="datenschutz">

        <h1>Nutzungsbedingungen</h1>

        <p>Quelle Geltungsbereich, Zugang und Nutzung, Gew&auml;hrleistung; modifiziert nach: Nutzungsbedingungen Bundesverband Deutscher Pathologen e.V.</p>
<p>Quelle &Auml;nderungen, Salvatorische Klausel; modifiziert nach: Nutzungsbedingungen Asklepios Kliniken GmbH vom 25.02.2015</p>
<p>Quelle Datenschutzerkl&auml;rung; modifiziert nach: <a href="http://www.itrecht-hannover.de/kostenlose-muster-datenschutzerklaerung/">http://www.itrecht-hannover.de/kostenlose-muster-datenschutzerklaerung/</a></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Das Programm mikropi ist eine webbasierte eLearning-Anwendung des Instituts f&uuml;r Klinische Pathologie des Universit&auml;tsklinikums Freiburg zum Zwecke der strukturierten Lehre. Die Nutzerin / der Nutzer erkl&auml;rt sich durch Anmeldung bei mikropi mit den Nutzungsbedingungen einverstanden. Eine Verwendung von mikropi ohne Einverst&auml;ndnis der Nutzungsbedingungen ist nicht gestattet.</p>
<h2>Geltungsbereich</h2>
<ul>
<li>Diese Nutzungsbedingungen gelten f&uuml;r die Nutzung der eLearning-Anwendung mikropi (im Folgenden nur mikropi genannt).</li>
<li>Mit dem Login erkennt das Mitglied die Geltung dieser Nutzungsbedingungen an.</li>
<li>Die Nutzung von mikropi ist unentgeltlich.</li>
<li>Etwaige &uuml;ber Links verkn&uuml;pfte Inhalte externer Anbieter (z.B. elektronische Lehrb&uuml;cher) sind nicht Teil von mikropi.</li>
</ul>
<p>&nbsp;</p>
<h2>Zugang und Nutzung von mikropi</h2>
<ul>
<li>Die pers&ouml;nlichen Zugangsdaten berechtigen nur das Mitglied pers&ouml;nlich zur Nutzung der jeweiligen Pr&auml;sentationsplattform.</li>
<li>Das Mitglied ist verpflichtet, die pers&ouml;nlichen Zugangsdaten vertraulich zu behandeln und gegen missbr&auml;uchliche Verwendung zu sch&uuml;tzen.</li>
<li>Bei Verlust erh&auml;lt das Mitglied neue pers&ouml;nliche Zugangsdaten</li>
<li>Mitgliedern ist es nicht gestattet, Lehrinhalte und Leistungen ohne ausdr&uuml;ckliche, ggf. vertraglich geregelte Genehmigung des Instituts f&uuml;r Klinische Pathologie Freiburg (schriftlich oder per Mail) gewerblich zu nutzen.</li>
<li>Die nicht gewerbliche Nutzung der Inhalte von mikropi ist unter sichtbarer Nennung der Quelle (bei Internetlink unter Angabe des Zugriffsdatums) erlaubt.</li>
<li>Bei Verst&ouml;&szlig;en hiergegen kann das Mitglied von der weiteren Nutzung ausgeschlossen werden, unter Vorbehalt ggf. weiterer rechtlicher Schritte.</li>
<li>Die Mitgliedschaft kann jederzeit und ohne Angaben von Gr&uuml;nden gek&uuml;ndigt werden.</li>
</ul>
<p>&nbsp;</p>
<h2>Gew&auml;hrleistung, Haftungsausschluss</h2>
<ul>
<li>Die Inhalte von mikropi werden nach bestem Wissen und Gewissen erstellt und gepflegt. Es besteht jedoch keine Gew&auml;hr f&uuml;r die Richtigkeit der Inhalte.</li>
<li>F&uuml;r &uuml;ber Links verkn&uuml;pfte Inhalte externer Anbieter (z.B. elektronische Lehrb&uuml;cher) wird keine Haftung &uuml;bernommen.</li>
</ul>
<p>&nbsp;</p>
<h2>&Auml;nderungen von Inhalten und der Nutzungsbedingungen</h2>
<ul>
<li>Das Institut f&uuml;r Klinische Pathologie der Universit&auml;tsklinik Freiburg beh&auml;lt sich vor, den Internetauftritt von mikropi ohne gesonderte Ank&uuml;ndigung ganz oder teilweise jederzeit zu erweitern, zu k&uuml;rzen, oder Funktionalit&auml;ten zu &auml;ndern.</li>
<li>Dies umfasst auch das Recht zur &Auml;nderung und Anpassung der Nutzerbedingungen.</li>
<li>&Auml;nderungen der Inhalte werden den Mitgliedern nach M&ouml;glichkeit kommuniziert.</li>
<li>&Auml;nderungen der Nutzungsbedingungen werden den Mitgliedern &uuml;ber die von ihnen bei der Registrierung hinterlegte Emailadresse kommuniziert.</li>
</ul>
<p>&nbsp;</p>

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

    echo ($pageBuilder->getJsTags());
    ?>

</body>








</html>