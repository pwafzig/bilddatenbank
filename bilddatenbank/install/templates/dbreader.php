#!@@SHEBANG@@
<?php

/*************************************************************************
 *
 * ABSCHNITT KONFIGURATION: BITTE ANPASSEN FALLS NOTWENDIG
 *
 *************************************************************************/


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
		$logname = $absdocroot.$installpath."/logs/dbreader.log";
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
        echo "DATENBANKFEHLER: Verbindung nicht moeglich\n";
        exit;
    }

    $db_selected = @mysql_select_db($db_name, $link);
    if (!$db_selected) {
        logfile('DATENBANKFEHLER: Kann Datenbank "'.$db_name.'" nicht benutzen : ' . mysql_error());
        logmail('DATENBANKFEHLER: Kann Datenbank "'.$db_name.'" nicht benutzen : ' . mysql_error());
        echo "DATENBANKFEHLER: Kann Datenbank \"".$db_name."\" nicht benutzen\n";
        exit;
    }

    //Pfade definieren

    $datapath 	= $absdocroot.$installpath."/data/";

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

    if($dir=opendir($datapath)){
        while($file=readdir($dir)){
            if (!is_dir($file) && $file != "." && $file != ".." && preg_match("/jpg/i", $file)){
                array_push($bildliste, $file);
            }
        }

    $number = sizeof($bildliste);

	if($number > 0){
		logfile('DEAMON: Es wurden '.$number.' Bilder im Upload-Verzeichnis gefunden');
		echo "[" . date("d.m.Y h:i:s", mktime()) . "] DEAMON: Es wurden $number Bilder im Upload-Verzeichnis gefunden\n";

	} else {
		//echo "DEAMON: Keine Bilder im Upload-Verzeichnis gefunden.\n";
		exit;
	}

    closedir($dir);
    }

    foreach($bildliste as $image_path) {

        //IPTC-Informationen auslesen und in DB speichern
        $iptc_path = $datapath.$image_path;
        get_iptc_data($iptc_path);

    	if($headline != ""){
	        echo "$image_path .";

        } else {
        	logfile('FEHLER: Datei '.$image_path.' fehlen IPTC-Daten, kein Import');
            echo "[" . date("d.m.Y h:i:s", mktime()) . "] FEHLER: Datei $image_path fehlen IPTC-Daten, kein Import\n";
        }

        echo ". fertig\n";

	}

?>
