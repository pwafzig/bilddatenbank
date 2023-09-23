<html>
<head>
	<title>Installationsroutine Bilddatenbank</title>
	<meta charset="utf-8" />
	<style>
		body {
			background-color: #EBEBEB;
			padding: 30px 160px 30px 160px;
			font-family: Arial, Helvetica, Sans-Serif;
			font-size: 89%;
		}
		.okay {
			color: #00FF00;
			font-weight: bold;
		}

		.fault {
			color: #FF0000;
			font-weight: bold;
		}

		.optional {
			color: #FFFF00;
			font-weight: bold;
		}

		#main {
			border: 1px solid #C0C0C0;
			background-color: #FFF;
			padding: 20px;
		}

		#install input {
			padding: 3px;
		}

		.ruler {
			border-top: 1px solid #C0C0C0;
			margin: 30px 0px 30px 0px;
		}

		.absenden {
			border: 3px solid #47d111;
			background-color: #a3ef85;
			padding: 3px 20px 3px 20px;
			margin-left: 5px;
			font-size: 105%;
			font-weight: bold;
			cursor: pointer;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			border-radius: 5px;
		}
	</style>
</head>
<body>
<h1>Installation</h1>
<div id="main">

<?php if(!isset($_POST['submit'])) { ?>

<h3>Überprüfung der benötigten Komponenten</h3>
<p><?php

	$extensions = get_loaded_extensions();

	if (!in_array('zlib', $extensions)) {
		echo "Die Funktion ZLib ist nicht vorhanden... <span class=\"fault\">FEHLER</span><br />";
		exit;
	} else {
		echo "Die Funktion ZLib ist vorhanden... <span class=\"okay\">OK</span><br />";
	}

	if (!in_array('session', $extensions)) {
		echo "Sessions sind nicht aktiviert... <span class=\"fault\">FEHLER</span><br />";
		exit;
	} else {
		echo "Sessions sind aktiviert... <span class=\"okay\">OK</span><br />";
	}

	if (!in_array('zip', $extensions)) {
		echo "Die Funktion ZIP ist nicht vorhanden... <span class=\"fault\">FEHLER</span><br />";
		exit;
	} else {
		echo "Die Funktion ZIP ist vorhanden... <span class=\"okay\">OK</span><br />";
	}

	if (!in_array('exif', $extensions)) {
		echo "Die Bilddatenbank benötigt die Funktion EXIF... <span class=\"fault\">FEHLER</span><br />";
		exit;
	} else {
		echo "Die Funktion EXIF ist vorhanden... <span class=\"okay\">OK</span><br />";
	}

	if (!in_array('gd', $extensions)) {
		echo "Die Bilddatenbank benötigt die Funktion GDLib... <span class=\"fault\">FEHLER</span><br />";
		exit;
	} else {
		echo "Die Funktion GDLib ist vorhanden... <span class=\"okay\">OK</span><br />";
	}

	if (!function_exists("mysql_query") && !function_exists("mysqli_set_charset")) {
    	echo "Die Bilddatenbank benötigt eine MySQL Datenbank, es sind aber weder <a href=\"http://php.net/mysql\">MySQL</a> oder die <a href=\"http://php.net/mysqli\">MySQLi</a> Erweiterung installiert.";
    	exit;
    } else {
		echo "MySQL vorhanden... <span class=\"okay\">OK</span><br />";
	}

    if (!@preg_match("/^.$/u", utf8_encode("\xF1"))) {
    	echo "Die Bilddatenbank benötigt die <a href=\"http://php.net/pcre\">Perl-Compatible Regular Expression</a>-Funktion.";
    	exit;
    } else {
		echo "Die Funktion PCRE ist vorhanden... <span class=\"okay\">OK</span><br />";
	}

	if (ini_get("safe_mode")) {
    	echo "Die Bilddatenbank funktioniert nicht mit eingeschaltetem <a href=\"http://php.net/manual/en/features.safe-mode.php\">Safe Mode</a>. Bitte deaktivieren Sie den Safe Mode.";
    	exit;
    } else {
		echo "Safe Mode ist aus... <span class=\"okay\">OK</span><br />";
	}

?></p>

<hr border="0" noshade size="1" color="#FFF" class="ruler">
<h3>Prüfung der Verzeichnisse</h3>
<?php
	if (!is_writeable("../../config.inc.php")) {
    	echo "Die Datei <strong>config.inc.php</strong> muss beschreibbar sein... <span class=\"fault\">FEHLER</span>";
    	exit;
    } else {
		echo "Datei <strong>config.inc.php</strong> ist beschreibbar... <span class=\"okay\">OK</span><br />";
	}

	if (!is_writeable("../secure/dbconnect.inc.php")) {
    	echo "Die Datei <strong>/secure/dbconnect.inc.php</strong> muss beschreibbar sein... <span class=\"fault\">FEHLER</span>";
    	exit;
    } else {
		echo "Datei <strong>dbconnect.inc.php</strong> ist beschreibbar... <span class=\"okay\">OK</span><br />";
	}

	if (!is_writeable("../.htaccess")) {
    	echo "Die Datei <strong>/.htaccess</strong> muss beschreibbar sein... <span class=\"fault\">FEHLER</span>";
    	exit;
    } else {
		echo "Datei <strong>.htaccess</strong> ist beschreibbar... <span class=\"okay\">OK</span><br />";
	}

	if (!is_writeable("../bin/")) {
    	echo "Das Verzeichnis <strong>bin</strong> muss beschreibbar sein... <span class=\"fault\">FEHLER</span>";
    	exit;
    } else {
		echo "Verzeichnis <strong>bin</strong> ist beschreibbar... <span class=\"okay\">OK</span><br />";
	}

	if (!is_writeable("../logs/")) {
    	echo "Das Verzeichnis <strong>logs</strong> muss beschreibbar sein... <span class=\"fault\">FEHLER</span>";
    	exit;
    } else {
		echo "Verzeichnis <strong>logs</strong> ist beschreibbar... <span class=\"okay\">OK</span><br />";
	}

	if (!is_writeable("../admin/backup/")) {
    	echo "Das Verzeichnis <strong>admin/backup</strong> muss beschreibbar sein... <span class=\"fault\">FEHLER</span>";
    	exit;
    } else {
		echo "Verzeichnis <strong>admin/backup</strong> ist beschreibbar... <span class=\"okay\">OK</span><br />";
	}

	if (!is_writeable("../data/")) {
    	echo "Das Verzeichnis <strong>data</strong> muss beschreibbar sein... <span class=\"fault\">FEHLER</span>";
    	exit;
    } else {
		echo "Verzeichnis <strong>data</strong> ist beschreibbar... <span class=\"okay\">OK</span><br />";
	}
	
	if (!is_writeable("../lowres/")) {
    	echo "Das Verzeichnis <strong>lowres</strong> muss beschreibbar sein... <span class=\"fault\">FEHLER</span>";
    	exit;
    } else {
		echo "Verzeichnis <strong>lowres</strong> ist beschreibbar... <span class=\"okay\">OK</span><br />";
	}	

	if (!is_writeable("../previews/")) {
    	echo "Das Verzeichnis <strong>previews</strong> muss beschreibbar sein... <span class=\"fault\">FEHLER</span>";
    	exit;
    } else {
		echo "Verzeichnis <strong>previews</strong> ist beschreibbar... <span class=\"okay\">OK</span><br />";
	}

	if (!is_writeable("../thumbs/")) {
    	echo "Das Verzeichnis <strong>thumbs</strong> muss beschreibbar sein... <span class=\"fault\">FEHLER</span>";
    	exit;
    } else {
		echo "Verzeichnis <strong>thumbs</strong> ist beschreibbar... <span class=\"okay\">OK</span><br />";
	}

	if (!is_writeable("../temp/")) {
    	echo "Das Verzeichnis <strong>temp</strong> muss beschreibbar sein... <span class=\"fault\">FEHLER</span>";
    	exit;
    } else {
		echo "Verzeichnis <strong>temp</strong> ist beschreibbar... <span class=\"okay\">OK</span><br />";
	}
?>
<hr border="0" noshade size="1" color="#FFF" class="ruler">
<h3>Eingabe der Basisdaten</h3>
<p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="install" id="install">

<label for="hostname">URL der Bilddatenbank (ohne http://, nur ausfüllen, wenn abweichend)</label><br />
<input type="text" name="hostname" size="90" value="<?php echo $_SERVER['HTTP_HOST']; ?>"><br /><br />

<label for="docroot">Document-Root-Verzeichnis: (nur ausfüllen, wenn leer)</label><br />

<?php

	$docroot = $_SERVER['DOCUMENT_ROOT']."/";
	$docroot = str_replace ("//", "/", $docroot);

?>

<input type="text" name="docroot" size="90" value="<?php echo $docroot; ?>"><br /><br />

<label for="installpath">Installationsverzeichnis (nur ausfüllen, wenn leer):</label><br />

<?php

	$installpath = $_SERVER['PHP_SELF'];
	$installpath = explode("/",$installpath);
	$installpath = $installpath[1];

?>

<input type="text" name="installpath" size="90" value="<?php echo $installpath; ?>"><br /><br />

<label for="email">Admin-Emailadresse</label><br />
<input type="text" name="email" size="90"><br /><br />

<?php $shebang = @exec('which php'); ?>

<label for="shebang">Pfad zur PHP-Executable</label><br />
<input type="text" name="shebang" size="90" value="<?php echo $shebang; ?>"><br /><br />
</p>

<hr border="0" noshade size="1" color="#FFF" class="ruler">
<h3>Eingabe der Datenbankdaten</h3>
<p>
<label for="dbname">Datenbankname</label><br />
<input type="text" name="dbname" size="50" value=""><br /><br />

<label for="dbuser">Benutzer</label><br />
<input type="text" name="dbuser" size="50" value=""><br /><br />

<label for="dbpass">Passwort</label><br />
<input type="text" name="dbpass" size="50" value=""><br /><br />

<label for="dbhost">Host (nur ausfüllen, wenn abweichend)</label><br />
<input type="text" name="dbhost" size="50" value="localhost"><br /><br />

<strong>Sind alle eingaben richtig? Okay, dann <input type="submit" name="submit" value="&nbsp;&nbsp;&nbsp;Installation jetzt starten...&nbsp;&nbsp;&nbsp;" class="absenden">
</form>
</p>

<?php } else { ?>

<h3>Einrichten der Datenbank</h3>
<p>

<?php

	//Datenbankverbindung aufbauen

	$link = mysql_connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass']);
	if (!$link) {
		echo "Verbindung mit den angegebenen Daten nicht möglich... <span class=\"fault\">FEHLER</span><br >";
		exit;
	} else {
		echo "Verbindungsdaten korrekt... <span class=\"okay\">OK</span><br />";
	}

	$db_selected = mysql_select_db($_POST['dbname'], $link);
	if (!$db_selected) {
		echo "Die Datenbank ".$_POST['dbname']." exisitiert nicht... <span class=\"fault\">FEHLER</span><br />";
		exit;
	} else {
		echo "Datenbank konnektiert... <span class=\"okay\">OK</span><br />";
	}

	//Datenbank import starten

	$import = file_get_contents("templates/bilddatenbank.sql");

	$import = preg_replace ("%/\*(.*)\*/%Us", '', $import);
	$import = preg_replace ("%^--(.*)\n%mU", '', $import);
	$import = preg_replace ("%^$\n%mU", '', $import);

	mysql_real_escape_string($import);
	$import = explode (";", $import);

	foreach ($import as $imp){
	if ($imp != '' && $imp != ' '){
		$result = mysql_query($imp);

			if(!$result) {
				echo "Fehler beim Einrichten der Datenbank... <span class=\"fault\">FEHLER</span><br /><pre>".mysql_error()."</pre>";
				exit;
			} else {
				$out = "Datenbank eingerichtet... <span class=\"okay\">OK</span><br />";
			}
		}
	}

	echo $out;

?>

</p>
<h3>Dateien schreiben</h3>
<p>

<?php


	//Templates laden und Dateien schreiben
	//config.inc.php

	$config = file_get_contents("templates/config.inc.php");
	$config = preg_replace("/@@INSTALLPATH@@/", $_POST['installpath'], $config);
	$config = preg_replace("/@@DOCROOT@@/", $_POST['docroot'], $config);
	$config = preg_replace("/@@HTTPHOST@@/", $_POST['hostname'], $config);

	file_put_contents("../../config.inc.php", $config);
	@chmod ("../../config.inc.php", 0644);

	echo "Datei <strong>config.inc.php</strong> geschrieben... <span class=\"okay\">OK</span><br />";

	//dbconnect.inc.php

	$dbconnect = file_get_contents("templates/dbconnect.inc.php");
	$dbconnect = preg_replace("/@@DBHOST@@/", $_POST['dbhost'], $dbconnect);
	$dbconnect = preg_replace("/@@DBUSER@@/", $_POST['dbuser'], $dbconnect);
	$dbconnect = preg_replace("/@@DBNAME@@/", $_POST['dbname'], $dbconnect);
	$dbconnect = preg_replace("/@@DBPASS@@/", $_POST['dbpass'], $dbconnect);

	file_put_contents("../secure/dbconnect.inc.php", $dbconnect);
	@chmod ("../secure/dbconnect.inc.php", 0644);
	echo "Datei <strong>connect.inc.php</strong> geschrieben... <span class=\"okay\">OK</span><br />";

	//.htaccess

	$htaccess = file_get_contents("templates/htaccess");
	$htaccess = preg_replace("/@@INSTALLPATH@@/", $_POST['installpath'], $htaccess);

	file_put_contents("../.htaccess", $htaccess);
	@chmod ("../.htaccess", 0644);

	echo "Datei <strong>.htaccess</strong> geschrieben... <span class=\"okay\">OK</span><br />";

	//deamon.php

	$deamon = file_get_contents("templates/deamon.php");
	$deamon = preg_replace("/@@SHEBANG@@/", $_POST['shebang'], $deamon);
	$deamon = preg_replace("/@@HOSTNAME@@/", $_POST['hostname'], $deamon);
	$deamon = preg_replace("/@@DOCROOT@@/", $_POST['docroot'], $deamon);
	$deamon = preg_replace("/@@INSTALLPATH@@/", $_POST['installpath'], $deamon);
	$deamon = preg_replace("/@@EMAIL@@/", $_POST['email'], $deamon);
	$deamon = preg_replace("/@@DBHOST@@/", $_POST['dbhost'], $deamon);
	$deamon = preg_replace("/@@DBUSER@@/", $_POST['dbuser'], $deamon);
	$deamon = preg_replace("/@@DBNAME@@/", $_POST['dbname'], $deamon);
	$deamon = preg_replace("/@@DBPASS@@/", $_POST['dbpass'], $deamon);

	file_put_contents("../bin/deamon.php", $deamon);
	@chmod ("../bin/deamon.php", 0755);

	echo "Datei <strong>deamon.php</strong> geschrieben... <span class=\"okay\">OK</span><br />";

	//dbreader.php

	$dbreader = file_get_contents("templates/dbreader.php");
	$dbreader = preg_replace("/@@SHEBANG@@/", $shebang, $dbreader);
	$dbreader = preg_replace("/@@DOCROOT@@/", $_POST['docroot'], $dbreader);
	$dbreader = preg_replace("/@@INSTALLPATH@@/", $_POST['installpath'], $dbreader);
	$dbreader = preg_replace("/@@EMAIL@@/", $_POST['email'], $dbreader);
	$dbreader = preg_replace("/@@DBHOST@@/", $_POST['dbhost'], $dbreader);
	$dbreader = preg_replace("/@@DBUSER@@/", $_POST['dbuser'], $dbreader);
	$dbreader = preg_replace("/@@DBNAME@@/", $_POST['dbname'], $dbreader);
	$dbreader = preg_replace("/@@DBPASS@@/", $_POST['dbpass'], $dbreader);

	file_put_contents("../bin/dbreader.php", $dbreader);
	@chmod ("../bin/dbreader.php", 0755);

	echo "Datei <strong>dbreader.php</strong> geschrieben... <span class=\"okay\">OK</span><br />";

	echo "<br /><br /><strong>Installation abgeschlossen - weiter zur <a href=\"/".$_POST['installpath']."/index.php\">Bilddatenbank...</a></strong>";

}

?>
</div>
</body>
</html>
