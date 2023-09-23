#!@@SHEBANG@@
<?php

	/*************************************************************************
	 *
	 * ABSCHNITT KONFIGURATION: BITTE ANPASSEN FALLS NOTWENDIG
	 *
	 *************************************************************************/
	
	/****** HTTP Host angeben ************************************************/
	$host = "@@HOSTNAME@@";
	
	/****** Absolutes Document-Root angeben **********************************/
	$absdocroot	= "@@DOCROOT@@";
	
	
	/****** Installationsverzeichnis angeben *********************************/
	$installpath = "@@INSTALLPATH@@";
	
	
	/****** Mailadresse für Logfehler angeben ********************************/
	$logaddress = "@@EMAIL@@";
	
	
	/****** Datenbank-Verbindunggsdaten angeben ******************************/
	$db_name 	= "@@DBNAME@@";
	$db_user 	= "@@DBUSER@@";
	$db_pwd  	= "@@DBPASS@@";
	$db_host 	= "@@DBHOST@@";
	
	
	/****** Größe der Previewbilder angeben (längste Seite in Pixel) *********/
	$prevsize = "500";
	
	
	/****** Qualität der Previews angeben (Prozent, 100=max. Qualität) *******/
	$qual_prev = "75";
	
	
	/****** Größe der Low-Res-Bilder angeben (längste Seite in Pixel) ********/
	$lowressize = "900";
	
	
	/****** Qualität der Low-Res-Bilder angeben (Prozent, 100=max. Qualität) */
	$qual_lowres = "75";
	
	
	/****** Größe der Thumbnails angeben (längste Seite in Pixel) ************/
	$thumbsize = "170";
	
	
	/****** Qualität der Thumbnails angeben (Prozent, 100=max. Qualität) *****/
	$qual_thb = "90";



	/*************************************************************************
	 *
	 * AB HIER KEINE ÄNDERUNGEN MEHR
	 *
	 *************************************************************************/
	

	//Logging

	date_default_timezone_set('Europe/Berlin');

	function logfile($msg)
	{
		global $absdocroot;
		global $installpath;
		$logname = $absdocroot.$installpath."/logs/deamon.log";
		$fd = fopen($logname, "a+");
		$msg = "[" . date("d.m.Y H:i:s") . "] " . $msg;
		fwrite($fd, $msg . "\r\n");
		fclose($fd);
	}

	function logmail($msg)
	{
		global $logaddress;
		$msg = "[" . date("d.m.Y H:i:s") . "] " . $msg;
		if($logaddress != ""){
			mail($logaddress, "FEHLER: Bilddatenbank", $msg);
		}
	}

	
	//Verbindung zur Datenbank aufbauen

	$link = @mysql_connect($db_host, $db_user, $db_pwd);
  
    if (!$link) {
        logfile('DATENBANKFEHLER: Verbindung nicht moeglich : ' . mysql_error());
        logmail('DATENBANKFEHLER: Verbindung nicht moeglich : ' . mysql_error());
        echo "[" . date("d.m.Y h:i:s", mktime()) . "] DATENBANKFEHLER: Verbindung nicht moeglich\n";
        exit;
    }

	$db_selected = @mysql_select_db($db_name, $link);
  
    if (!$db_selected) {
        logfile('DATENBANKFEHLER: Kann Datenbank "'.$db_name.'" nicht benutzen : ' . mysql_error());
        logmail('DATENBANKFEHLER: Kann Datenbank "'.$db_name.'" nicht benutzen : ' . mysql_error());
        echo "[" . date("d.m.Y h:i:s", mktime()) . "] DATENBANKFEHLER: Kann Datenbank \"".$db_name."\" nicht benutzen\n";
        exit;
    }


	//Pfade definieren

	$watermark 	= $absdocroot.$installpath."/bin/files/watermark.png";
	$basedir 	= $absdocroot.$installpath."/uploads";
	$prevpath 	= $absdocroot.$installpath."/previews/";
	$lowrespath	= $absdocroot.$installpath."/lowres/";
	$thumbpath 	= $absdocroot.$installpath."/thumbs/";
	$datapath 	= $absdocroot.$installpath."/data/";
	

	//Verzeichnisse auf Existenz prüfen unf ggf. anlegen
	if(!is_dir($prevpath)){
		mkdir($prevpath,0777);
	}
	if(!is_dir($thumbpath)){
		mkdir($thumbpath,0777);
	}
	if(!is_dir($datapath)){
		mkdir($datapath,0777);
	}


	//Funktion: Resizing von Bildern
	function create_thb( $thb_path, $newsize, $name, $qual ){
		ini_set("allow_call_time_pass_reference", "true");
		ini_set("display_errors", "0");
		
		if (!$info = getimagesize($thb_path) )
		    return false;
		
		$aspect = $info[0] / $info[1];
		
		if ($info[0] < $info[1]) {
		    $newwidth   = round( $newsize * $aspect );
		    $newheight  = $newsize;
		}
		
		elseif ($info[0] > $info[1]) {
		    $newwidth   = $newsize;
		    $newheight  = round( $newsize / $aspect );
		}
		
		elseif ($info[0] = $info[1]) {
		    $newwidth   = $newsize;
		    $newheight  = $newsize;
		}
		
		$src = imagecreatefromjpeg($thb_path);
    if ( !$src ) return false;
		
		$tmp = imagecreatetruecolor( $newwidth, $newheight );
		imagecopyresampled( $tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $info[0], $info[1] );
		
		imagejpeg( $tmp, $name, $qual );
		imagedestroy( $src );
		imagedestroy( $tmp );
		
		return true;
	}


	//Funktion: Wasserzeichen in Bilder setzen
	function create_watermark( $previmage, $watermark ){
	
		$photoImage = ImageCreateFromJPEG( $previmage );
		ImageAlphaBlending($photoImage, true);
		
		$logoImage = ImageCreateFromPNG( $watermark );
		
		$size_watermark = getimagesize($watermark);
		$size_prev = getimagesize($previmage);
		
		$dest_x = ($size_prev[0]/2) - ($size_watermark[0]/2);
		$dest_y = ($size_prev[1]/2) - ($size_watermark[1]/2);
		
		ImageCopy($photoImage, $logoImage, $dest_x, $dest_y, 0, 0, $size_watermark[0], $size_watermark[1]);
		imagejpeg($photoImage, $previmage, 100);
	
	}

	
	//Funktion: Dateigröße vergleichen
	function check_file( $image_path ) {
	
	  do {
	    $filesize1 = filesize( $image_path );
	    clearstatcache();
	    usleep(500000);
	
	    $filesize2 = filesize( $image_path );
	    clearstatcache();
	
	    if($filesize1 < $filesize2){
	        $checksize = 0;
	        set_time_limit(30);
	
	    } else {
	        $checksize = 1;
	    }
	  } while ($checksize != 1);
	  return ($checksize);
	}


	//Funktion: IPTC-Header lesen und in die Datenbank eintragen
	function get_iptc_data($iptc_path) {
	
		global $date;
		global $headline;
	
		$size = getimagesize ($iptc_path, $info);

		if (!empty($info["APP13"])) {
		  $iptc = iptcparse($info["APP13"]);
		
		  $filepart = explode("/", $iptc_path);
		  $i = sizeof($filepart);
		  $filename = $filepart[$i-1];
		
		  $object_name = addslashes($iptc["2#005"][0]);
		  $transref = addslashes($iptc["2#103"][0]);
		  $urgency = addslashes($iptc["2#010"][0]);
		  $cat = addslashes($iptc["2#015"][0]);
		  $special_instructions = addslashes($iptc["2#040"][0]);
		  $keywords = addslashes(implode(", ",$iptc["2#025"]));
		  $date = addslashes($iptc["2#055"][0]);
		  $time = addslashes($iptc["2#060"][0]);
		  $photographer = addslashes($iptc["2#080"][0]);
		  $title = addslashes($iptc["2#085"][0]);
		  $city = addslashes($iptc["2#090"][0]);
		  $location = addslashes($iptc["2#092"][0]);
		  $state = addslashes($iptc["2#095"][0]);
		  $country_code = addslashes($iptc["2#100"][0]);
		  $country = addslashes($iptc["2#101"][0]);
		  $headline = addslashes($iptc["2#105"][0]);
		  $source = addslashes($iptc["2#115"][0]);
		  $copyright = addslashes($iptc["2#116"][0]);
		  $caption = addslashes($iptc["2#120"][0]);
		}
	
	//Bilddimensionen auslesen und festlegen.
	$width = $size[0];
	$height = $size[1];
	$picsize = $width."x".$height."px";
	
	//Check, ob die wesentlichen IPTC-Daten vorhanden sind
	if($headline != ""){
	
		//Check, ob das Bild schon in der DB vorhanden ist, oder ob es neu geschrieben wird.
		$stmt_check = "SELECT id FROM picture_data WHERE filename = '$filename'";
		$result_check = mysql_query($stmt_check);
		$check = mysql_affected_rows();
		
			if($check > 0){
				$stmt_write  = "UPDATE picture_data SET timestamp = NOW( ), filename = '$filename', caption = '$caption', headline = '$headline', special_instructions = '$special_instructions', photographer = '$photographer', title = '$title', source = '$source', object_name = '$object_name', transref = '$transref', date = '$date', city = '$city', state = '$state', country = '$country', cat = '$cat', urgency = '$urgency', keywords = '$keywords', copyright = '$copyright', time = '$time', country_code = '$country_code', location = '$location', picsize = '$picsize' WHERE filename = '$filename'";
			} else {
				$stmt_write  = "INSERT INTO picture_data VALUES (NULL , CURRENT_TIMESTAMP , '$filename', '$caption', '$headline', '$special_instructions', '$photographer', '$title', '$source', '$object_name', '$transref', '$date', '$city', '$state', '$country', '$cat', '$urgency', '$keywords', '$copyright', '$time', '$country_code', '$location', '$picsize') ";
			}
		
			if(!preg_match('/^([\x09\x0A\x0D\x20-\x7E]|[\xC2][\xA0-\xBF]|[\xC3-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF]|\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2})*$/', $stmt_write)){
				$stmt_write  = utf8_decode($stmt_write);
			}
		
			$result_write = mysql_query($stmt_write);
			if (!$result_write) {
				logfile('DATENBANKFEHLER: Ungueltige Abfrage: ' . mysql_error());
				logmail('DATENBANKFEHLER: Ungueltige Abfrage: ' . mysql_error());
				echo "[" . date("d.m.Y h:i:s", mktime()) . "] DATENBANKFEHLER: Kann Datensatz nicht schreiben\n";
				exit;
			}
		}
	}

	$bildliste = array();
	
	if($dir=opendir($basedir)){
		while($file=readdir($dir)){
		  if (!is_dir($file) && $file != "." && $file != ".."){
		      array_push($bildliste, $file);
		  }
		}
	  $number = sizeof($bildliste);
	
		if($number > 0){
			logfile('DEAMON: Es wurden '.$number.' Bilder im Upload-Verzeichnis gefunden');
			echo "[" . date("d.m.Y h:i:s", mktime()) . "] DEAMON: Es wurden $number Bilder im Upload-Verzeichnis gefunden\n";
		
		} else {
			//Keine Bilder im Upload-Verzeichnis gefunden
			exit;
		}
	  closedir($dir);
	}

	foreach($bildliste as $image_path) {
	
		//Bilddaten einlesen
		$check_path = $basedir."/".$image_path;
		check_file($check_path);
		
		//IPTC-Informationen auslesen und in DB speichern
		$iptc_path = $basedir."/".$image_path;
		get_iptc_data($iptc_path);
	
	if($headline != ""){
		echo "$image_path .";

		//Thumbnails generieren
		$thumb = $thumbpath."/".$image_path;
		$thb_path = $basedir."/".$image_path;
		create_thb( $thb_path, $thumbsize, $thumb, $qual_thb );
		
		echo ".";
		
		//Low-Res generieren
		$lowres = $lowrespath."/".$image_path;
		$thb_path = $basedir."/".$image_path;
		create_thb( $thb_path, $lowressize, $lowres, $qual_lowres );
		
		echo ".";			
		
		//Previews generieren
		$prev = $prevpath."/".$image_path;
		$thb_path = $basedir."/".$image_path;
		create_thb( $thb_path, $prevsize, $prev,$qual_prev );
		
		echo ".";
		
		//Wasserzeichen generieren
		$watermarkpath = $prevpath."/".$image_path;
		create_watermark($watermarkpath, $watermark);
		
		echo ".";
		
		//Originaldatei verschieben in den Data-Bereich
		$src = $basedir."/".$image_path;
		$dest = $datapath."/".$image_path;
		
		if(!copy($src, $dest)){
			logfile('FEHLER: Datei '.$image_path.' konnte nicht kopiert werden!');
			echo "[" . date("d.m.Y h:i:s", mktime()) . "] FEHLER: Datei $image_path konnte nicht kopiert werden!\n";
		} else {
			chmod ($dest, 0777);
			if(!unlink($src)){
				logfile('FEHLER: Datei '.$image_path.' konnte nicht geloescht werden!');
				echo "[" . date("d.m.Y h:i:s", mktime()) . "] FEHLER: Datei $image_path konnte nicht geloescht werden!\n";
			}
			logfile($image_path.' ... ok');
			echo ". fertig\n";
		}
		  } else {
		  	logfile('FEHLER: Datei '.$image_path.' fehlen IPTC-Daten, kein Import');
		      echo "[" . date("d.m.Y h:i:s", mktime()) . "] FEHLER: Datei $image_path fehlen IPTC-Daten, kein Import\n";
		      unlink($iptc_path);
		  }
	}

?>
