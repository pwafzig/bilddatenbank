<?php include("php/includes/start.inc.php"); ?>
<?php

	//Ueberpruefe, ob eine Session vorhanden ist
	if(isset($_SESSION['login'])) {
		
		//Abfrage ob HighRes- oder LowRes-User
		if(isset($_SESSION['resolution'])){
			if($_SESSION['resolution'] == "highres"){
				$data = "data";
			} else {
				$data = "lowres";
			} 
		} else {
			exit;
		}
		
		//Filename für die ZIP-Datei festlegen
		$filename = INSTALLPATH."/temp/".date('Ymd-His')."_".$_SESSION['login'].".zip";

		$zip = new ZipArchive;
		$res = $zip->open(DOCROOT.$filename, ZipArchive::CREATE);

		if ($res === TRUE) {
	    	foreach ($_POST['zip'] as &$value) {
			    $zip->addFile(DOCROOT.INSTALLPATH."/".$data."/".$value, $value);
			}
			$zip->close();
			$filesize = filesize(DOCROOT.$filename);
		} else {
			echo "Fehler";
		}

		//Logging des Downloads

		foreach ($_POST['zip'] as &$value) {

			if($_SESSION['login'] != "probezugang"){
				$stmt_log = "UPDATE users SET downloads = downloads+1 WHERE login = '".$_SESSION['login']."' LIMIT 1";
				mysql_query($stmt_log);
				$stmt_count = "INSERT INTO downloads VALUES (NULL, '".$_SESSION['login']."', '".$value."', CURRENT_TIMESTAMP, '".$_SERVER['REMOTE_ADDR']."')";
				mysql_query($stmt_count);
			} else {
				$stmt_log = "UPDATE accessids SET downloads = downloads+1 WHERE hash = '".$_SESSION['accessid']."' LIMIT 1";
				mysql_query($stmt_log);
				$stmt_count = "INSERT INTO downloads VALUES (NULL, '".$_SESSION['name']."', '".$value."', CURRENT_TIMESTAMP, '".$_SERVER['REMOTE_ADDR']."')";
				mysql_query($stmt_count);
			}
		}

		//Infomail senden
		$file	 = "http://".$_SERVER['HTTP_HOST'].$filename;
		$to      = $CONFIG['email'];
		$subject = "Download Bilddatenbank";
		$headers = "From: ".$CONFIG['name']." <".$CONFIG['sendemail']."" . "\r\n" .
    				"Reply-To: ".$CONFIG['sendemail']."" . "\r\n" .
    				"X-Mailer: PHP/" . phpversion();

    	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

		$message  = "Art: ZIP-Download\r\n";
		$message .= "Remote-IP: " . $_SERVER['REMOTE_ADDR'] . "\r\n";
		$message .= "Remote-Host: " . $hostname . "\r\n";
		$message .= "Remote-Browser: " . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
		$message .= "Uhrzeit: " . date("d.m.Y, H:i:s") . "\r\n";
		$message .= "Datei: " . $file . "\r\n";
		$message .= "Aufloesung: " . $_SESSION['resolution'] . "\r\n";

		mail($to, $subject, $message, $headers, "-f ".$CONFIG['sendemail']."");


		//Ausgabe der Datei an den Browser

		@apache_setenv('no-gzip', 1);
		@ini_set('zlib.output_compression', 0);
		set_time_limit(300);

		header("Content-type: application/force-download");
		header('Content-Type: application/octet-stream');
		header("Content-Length: ".$filesize."");
		header("Content-type: application/zip;\n");
		header("Content-Disposition: attachment; filename=".date('Ymd-His')."_".$_SESSION['login'].".zip");
		header("Content-Transfer-Encoding: binary");
		header("Cache-Control: post-check=0, pre-check=0");

		$chunksize = 1 * (1024 * 1024); // how many bytes per chunk
		if ($filesize > $chunksize) {
		  $handle = fopen(DOCROOT.$filename, 'rb');
		  $buffer = '';
		  while (!feof($handle)) {
		    $buffer = fread($handle, $chunksize);
		    echo $buffer;
		    ob_flush();
		    flush();
		  }
		  fclose($handle);
		} else {
		  readfile(DOCROOT.$filename);
		}
		exit;

	} else {
		//Wenn keine Session vorhanden war
		echo "Nice try...";
		exit;
	}

?>
