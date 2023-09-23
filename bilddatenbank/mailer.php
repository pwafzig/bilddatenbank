<?php include("php/includes/start.inc.php"); ?>
<?php

	//Ueberpruefe, ob eine Session vorhanden ist
    if(isset($_SESSION['login'])) {

		//Ueberpruefe, ob eine id uebergeben wurde
		if(isset($_GET['id'])){

				$id = intval(@$_GET['id']);
				$id = mysql_real_escape_string($_GET['id']);

		} else {

			//Wenn keine id uebergeben wurde
			echo "ID failed...";
			exit;

		}

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
		
		//Datenbankabfrage
		$stmt_popup = "SELECT * FROM picture_data WHERE id = '$id'";
		$query_popup = mysql_query($stmt_popup);
		$result_popup = mysql_fetch_array($query_popup);

		//Ueberpruefe, ob die id in der Datenbank existiert
		if(isset($result_popup['id'])){

			//Sicherheitshalber den filename nochmal bereinigen
			$filename = ltrim(rtrim($result_popup['filename']));

			//Logging des Downloads
			if($_SESSION['login'] != "probezugang"){
				$stmt_log = "UPDATE users SET downloads = downloads+1 WHERE login = '".$_SESSION['login']."' LIMIT 1";
	            mysql_query($stmt_log);
	            $stmt_count = "INSERT INTO downloads VALUES (NULL, '".$_SESSION['login']."', '".$result_popup['filename']."', CURRENT_TIMESTAMP, '".$_SERVER['REMOTE_ADDR']."')";
	            mysql_query($stmt_count);
	        } else {
	            $stmt_log = "UPDATE accessids SET downloads = downloads+1 WHERE hash = '".$_SESSION['accessid']."' LIMIT 1";
	            mysql_query($stmt_log);
	            $stmt_count = "INSERT INTO downloads VALUES (NULL, '".$_SESSION['name']."', '".$result_popup['filename']."', CURRENT_TIMESTAMP, '".$_SERVER['REMOTE_ADDR']."')";
	            mysql_query($stmt_count);
	        }

			require_once(DOCROOT.INSTALLPATH."/lib/PHPMailer/class.phpmailer.php");

			$mail = new PHPMailer();

			$body = nl2br($CONFIG['mailer_text']);

			$mail->CharSet = "utf8";
			$mail->AddReplyTo($CONFIG['email'],$CONFIG['name']);
			$mail->SetFrom($CONFIG['sendemail'], $CONFIG['name']);

			$address = $_SESSION['email'];
			$mail->AddAddress($address, $_SESSION['name']);

			$mail->Subject    = "Bildzusendung Bild Nr. ".$result_popup['id']."";
			$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

			$mail->MsgHTML($body);

			$mail->AddAttachment(DOCROOT.INSTALLPATH."/".$data."/".$filename);

			if(!$mail->Send()) {
				echo "Tut uns leid, die Email konnte nicht zugestellt werden. Bitte wenden Sie sich an den Administrator: " . $mail->ErrorInfo;
			} else {

				//Infomail senden
				$file	 = "http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/thumbs/".$filename;
				$to      = $CONFIG['email'];
				$subject = "Download Bilddatenbank";
				$headers = "From: ".$CONFIG['name']." <".$CONFIG['sendemail'].">" . "\r\n" .
		    				"Reply-To: ".$CONFIG['sendemail']."" . "\r\n" .
		    				"X-Mailer: PHP/" . phpversion();

		    	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

				$message  = "Art: Mailversand\r\n";
				$message .= "Remote-IP: " . $_SERVER['REMOTE_ADDR'] . "\r\n";
				$message .= "Remote-Host: " . $hostname . "\r\n";
				$message .= "Remote-Browser: " . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
				$message .= "Uhrzeit: " . date("d.m.Y, H:i:s") . "\r\n";
				$message .= "Datei: " . $file . "\r\n";
				$message .= "Aufloesung: " . $_SESSION['resolution'] . "\r\n";

				mail($to, $subject, $message, $headers, "-f ".$CONFIG['sendemail']."");

				//echo "Message sent!";
				if(ereg("detail",$_SERVER['HTTP_REFERER'])){
					header("Location:".$_SERVER['HTTP_REFERER']."&mail=sent");
				} else {
					header("Location:".$_SERVER['HTTP_REFERER']);
				}
			}

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