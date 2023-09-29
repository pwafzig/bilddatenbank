<?php include("php/includes/start.inc.php"); ?>
<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;

	//Ueberpruefe, ob eine Session vorhanden ist
    if(isset($_SESSION['login'])) {

		//Ueberpruefe, ob eine id uebergeben wurde
		if(isset($_GET['id'])){
				$id = intval(@$_GET['id']);
		} else {
			//Wenn keine id uebergeben wurde
			echo "ID failed...";
			exit;
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

			require $_SERVER['DOCUMENT_ROOT']."/".INSTALLPATH.'/lib/PHPMailer/src/Exception.php';
			require $_SERVER['DOCUMENT_ROOT']."/".INSTALLPATH.'/lib/PHPMailer/src/PHPMailer.php';
			require $_SERVER['DOCUMENT_ROOT']."/".INSTALLPATH.'/lib/PHPMailer/src/SMTP.php';

			//Auflösung für die ZIP-Datei festlegen
			if($_SESSION['resolution'] == "lowres") { 
				$res_path = "lowres"; 
			} else {
				$res_path = "data";
			}

			//Create a new PHPMailer instance
			$mail = new PHPMailer;
			$mail->isSMTP(); 

			$mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
			$mail->Host = $CONFIG['smtphost'];
			$mail->Port = $CONFIG['smtpport']; 
			$mail->SMTPSecure = 'tls'; // ssl is depracated
			$mail->SMTPAuth = true;
			$mail->Username = $CONFIG['smtplogin'];
			$mail->Password = $CONFIG['smtppass'];
		    
		    //Recipients
		    $mail->setFrom($CONFIG['sendemail'], $CONFIG['firma']);
		    $mail->addAddress($_SESSION['email'], $_SESSION['name']);
		    $mail->addReplyTo($CONFIG['sendemail'], $CONFIG['firma']);
		    
		    //Attachments
		    $mail->addAttachment($_SERVER['DOCUMENT_ROOT'].'/bilddatenbank/'.$res_path.'/'.$filename);

		    //Content
		    $mail->Subject = 'Bildzusendung Bild Nr. '.$result_popup['id'].'';
		    $mail->msgHTML(file_get_contents($_SERVER['DOCUMENT_ROOT']."/".INSTALLPATH.'/lib/PHPMailer/contents.html'), __DIR__);
			$mail->AltBody = 'Bildzusendung Bild Nr. '.$result_popup['id'].'';

		    if (!$mail->send()) {
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
			    //echo 'Message sent!';
			}
				
			//Infomail senden
		
			if(isset($_SERVER['HTTP_REFERER'])) {
				if(preg_match("/detail/",$_SERVER['HTTP_REFERER'])){
					header("Location:".$_SERVER['HTTP_REFERER']."?mail_ok=yes");
				} else {
					header("Location:".$_SERVER['HTTP_REFERER']);
				} 				
			} else {
				header("Location:".$_SERVER['HTTP_REFERER']."?mail_ok=yes");
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