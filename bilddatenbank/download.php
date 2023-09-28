<?php include("php/includes/start.inc.php"); ?>
<?php

	//Ueberpruefe, ob eine Session vorhanden ist
    if(isset($_SESSION['login'])) {

		//Ueberpruefe, ob eine id uebergeben wurde
		if(isset($_GET['id'])){

				$id = intval(@$_GET['id']);
				$id = mysqli_real_escape_string($link, $_GET['id']);

		} else {

			//Wenn keine id uebergeben wurde
			echo "ID failed...";
			exit;

		}
		
		//Abfrage ob HighRes- oder LowRes-User
		if($_SESSION['resolution'] == "lowres") { 
			$res_path = "lowres"; 
		} else {
			$res_path = "data";
		}

		//Datenbankabfrage
		$stmt_popup = "SELECT * FROM picture_data WHERE id = '$id'";
		$query_popup = mysqli_query($link, $stmt_popup);
		$result_popup = mysqli_fetch_array($query_popup, MYSQLI_ASSOC);

		//Ueberpruefe, ob die id in der Datenbank existiert
		if(isset($result_popup['id'])){

			//Sicherheitshalber den filename nochmal bereinigen
			$filename = ltrim(rtrim($result_popup['filename']));

			//Logging des Downloads
			if($_SESSION['login'] != "probezugang"){
				$stmt_log = "UPDATE users SET downloads = downloads+1 WHERE login = '".$_SESSION['login']."' LIMIT 1";
                mysqli_query($link, $stmt_log);
                $stmt_count = "INSERT INTO downloads VALUES (NULL, '".$_SESSION['login']."', '".$result_popup['filename']."', CURRENT_TIMESTAMP, '".$_SERVER['REMOTE_ADDR']."')";
                mysqli_query($link, $stmt_count);
            } else {
                $stmt_log = "UPDATE accessids SET downloads = downloads+1 WHERE hash = '".$_SESSION['accessid']."' LIMIT 1";
                mysqli_query($link, $stmt_log);
                $stmt_count = "INSERT INTO downloads VALUES (NULL, '".$_SESSION['name']."', '".$result_popup['filename']."', CURRENT_TIMESTAMP, '".$_SERVER['REMOTE_ADDR']."')";
                mysqli_query($link, $stmt_count);
            }

			//Ausgabe der Datei an den Browser
			$filesize = filesize(DOCROOT.INSTALLPATH."/".$res_path."/".$filename);

			header("Content-Type: image/jpeg");
			header("Content-Disposition: attachment; filename=".$result_popup['filename']);
			header("Content-Transfer-Encoding: binary");
			header("Cache-Control: post-check=0, pre-check=0");
			header("Content-Length: ".$filesize."");
			readfile(DOCROOT.INSTALLPATH."/".$res_path."/".$filename);

			//Infomail senden
			$file	 = "http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/thumbs/".$filename;
			$to      = $CONFIG['email'];
			$subject = "Download Bilddatenbank";
			$headers = "From: ".$CONFIG['name']." <".$CONFIG['sendemail'].">" . "\r\n" .
	    				"Reply-To: ".$CONFIG['sendemail']."" . "\r\n" .
	    				"X-Mailer: PHP/" . phpversion();

	    	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

			$message  = "Art: Download\r\n";
			$message .= "Remote-IP: " . $_SERVER['REMOTE_ADDR'] . "\r\n";
			$message .= "Remote-Host: " . $hostname . "\r\n";
			$message .= "Remote-Browser: " . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
			$message .= "Uhrzeit: " . date("d.m.Y, H:i:s") . "\r\n";
			$message .= "Datei: " . $file . "\r\n";
			$message .= "Name: " . $_SESSION['name'] . "\r\n";
			$message .= "Aufloesung: " . $_SESSION['resolution'] . "\r\n";

			mail($to, $subject, $message, $headers, "-f ".$CONFIG['sendemail']."");

		} else {

			//Wenn id nicht in der Datenbank vorhanden
			echo "ID failed...";
			exit;

		}

	} else {

		//Wenn keine Session vorhanden war
		echo "Nice try...";
		exit;

	}

?>
<?php include(DOCROOT.INSTALLPATH."/php/includes/analytics.inc.php"); ?>