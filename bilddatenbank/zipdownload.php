<?php include("php/includes/start.inc.php"); ?>
<?php

	//Ueberpruefe, ob eine Session vorhanden ist
	if(isset($_SESSION['login'])) {

		//Filename für die ZIP-Datei festlegen
		$filename = INSTALLPATH."/temp/".date('Ymd-His')."_".$_SESSION['login'].".zip";

		//Auflösung für die ZIP-Datei festlegen
		if($_SESSION['resolution'] == "lowres") { 
			$res_path = "lowres"; 
		} else {
			$res_path = "data";
		}


		$zip = new ZipArchive();

		if ($zip->open($_SERVER['DOCUMENT_ROOT']."/".$filename, ZipArchive::CREATE)!==TRUE) {
		    exit("cannot open <$filename>\n");
		} else {
	    	foreach ($_POST['zip'] as &$value) {

	    		if(!file_exists($_SERVER['DOCUMENT_ROOT']."/".INSTALLPATH."/".$res_path."/".$value)){
	    			echo "File $value doesn't exist<br />";
	    		} elseif (!is_readable($_SERVER['DOCUMENT_ROOT']."/".INSTALLPATH."/".$res_path."/".$value)) {
	    			echo "File $value isn't readable<br />";
	    		} else {
	    			//echo "File $value exists and is readable<br />";
	    		}

				$zip->addFile($_SERVER['DOCUMENT_ROOT']."/".INSTALLPATH."/".$res_path."/".$value, $value);
			}	

			$zip->close();
		}

		$filesize = filesize($_SERVER['DOCUMENT_ROOT']."/".$filename);

		//Logging des Downloads

		foreach ($_POST['zip'] as &$value) {

			if($_SESSION['login'] != "probezugang"){
				$stmt_log = "UPDATE users SET downloads = downloads+1 WHERE login = '".$_SESSION['login']."' LIMIT 1";
				mysqli_query($link, $stmt_log);
				$stmt_count = "INSERT INTO downloads VALUES (NULL, '".$_SESSION['login']."', '".$value."', CURRENT_TIMESTAMP, '".$_SERVER['REMOTE_ADDR']."')";
				mysqli_query($link, $stmt_count);
			} else {
				$stmt_log = "UPDATE accessids SET downloads = downloads+1 WHERE hash = '".$_SESSION['accessid']."' LIMIT 1";
				mysqli_query($link, $stmt_log);
				$stmt_count = "INSERT INTO downloads VALUES (NULL, '".$_SESSION['name']."', '".$value."', CURRENT_TIMESTAMP, '".$_SERVER['REMOTE_ADDR']."')";
				mysqli_query($link, $stmt_log);
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
		

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=".date('Ymd-His')."_".$_SESSION['login'].".zip");
		header("Content-Length: ".$filesize."");
		header("Pragma: no-cache"); 
		header("Expires: 0"); 

		$chunksize = 1 * (1024 * 1024); // how many bytes per chunk
		if ($filesize > $chunksize) {
		  $handle = fopen($_SERVER['DOCUMENT_ROOT']."/".$filename, 'rb');
		  $buffer = '';
		  while (!feof($handle)) {
		    $buffer = fread($handle, $chunksize);
		    echo $buffer;
		    ob_flush();
		    flush();
		  }
		  fclose($handle);
		} else {
		  readfile($_SERVER['DOCUMENT_ROOT']."/".$filename);
		}
		exit;
	
	} else {
		//Wenn keine Session vorhanden war
		echo "Nice try...";
		exit;
	}

?>

