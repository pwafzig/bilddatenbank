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

    $link = mysqli_connect($db_host, $db_user, $db_pwd);
  
    if (!$link) {
        logfile('DATENBANKFEHLER: Verbindung nicht moeglich : ' . mysqli_error($link));
        logmail('DATENBANKFEHLER: Verbindung nicht moeglich : ' . mysqli_error($link));
        echo "[" . date("d.m.Y h:i:s") . "] DATENBANKFEHLER: Verbindung nicht moeglich\n";
        exit;
    }

    $db_selected = mysqli_select_db($link, $db_name);
  
    if (!$db_selected) {
        logfile('DATENBANKFEHLER: Kann Datenbank "'.$db_name.'" nicht benutzen : ' . mysqli_error($link));
        logmail('DATENBANKFEHLER: Kann Datenbank "'.$db_name.'" nicht benutzen : ' . mysqli_error($link));
        echo "[" . date("d.m.Y h:i:s") . "] DATENBANKFEHLER: Kann Datenbank \"".$db_name."\" nicht benutzen\n";
        exit;
    }

    //Pfade definieren

    $datapath   = $absdocroot.$installpath."/data/";

    //Funktion: URL sanitize für die Generierung von sprechenden Verzeichnissen
    function sanitize_url($url) {
        $url = strtolower(trim($url));
        $url=replace_accents($url);
        $url = html_entity_decode($url,ENT_QUOTES,'UTF8');
     
        $find = array(' ', '&', '\r\n', '\n', '+',',');
        $url = str_replace ($find, '-', $url);
     
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace ($find, $repl, $url);
     
        return $url; 
    }

    function replace_accents($var){ //replace for accents catalan spanish and more
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'); 
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o'); 
        $var= str_replace($a, $b,$var);
        return $var; 
    }

    //Funktion: IPTC-Header lesen und in die Datenbank eintragen
    function get_iptc_data($iptc_path) {
    
        global $date;
        global $headline;
        global $link;
        global $logaddress;
        global $basedir;
    
        $size = getimagesize ($iptc_path, $info);

        if (!empty($info["APP13"])) {
            $iptc = iptcparse($info["APP13"]);

            $filepart = explode("/", $iptc_path);
            $i = sizeof($filepart);
            $filename = $filepart[$i-1];

            if(empty($iptc["2#055"][0]) OR !isset($iptc["2#055"][0])){
                mail($logaddress, "FEHLER: Bilddatenbank", "$filename hat kein gueltiges Datum und konnte nicht importiert werden.", "From: ".$logaddress."", "-f ".$logaddress."");
                echo "$filename hat kein gültiges Datum und konnte nicht importiert werden.";
                unlink($iptc_path);
                exit;
            } else {
                $date = addslashes($iptc["2#055"][0]);    
            }

            if(empty($iptc["2#005"][0]) OR !isset($iptc["2#005"][0])){
                mail($logaddress, "FEHLER: Bilddatenbank", "$filename hat keinen gueltigen Object Name und konnte nicht importiert werden.", "From: ".$logaddress."", "-f ".$logaddress."");
                echo "$filename hat keinen gültigen Object Name und konnte nicht importiert werden.";
                unlink($iptc_path);
                exit;
            } else {
                $object_name = addslashes($iptc["2#005"][0]);
            }

            $id = md5($object_name.$date).md5($date.$object_name);
            $id = preg_replace ('/[^0-9]/','',$id);
            $id = substr($id, 0,12);
            $transref = sanitize_url($object_name)."-".$id;

            $urgency = addslashes($iptc['2#010']['0'] ?? '');
            $cat = addslashes($iptc["2#015"][0] ?? '');
            $special_instructions = addslashes($iptc["2#040"][0] ?? '');

            $keywords = "";
            if(isset($iptc["2#025"])){
                $keywords = addslashes(implode(", ",$iptc["2#025"] ?? ''));
            }

            $time = addslashes($iptc["2#060"][0] ?? '');
            $photographer = addslashes($iptc["2#080"][0] ?? '');
            $title = addslashes($iptc["2#085"][0] ?? '');
            $city = addslashes($iptc["2#090"][0] ?? '');
            $location = addslashes($iptc["2#092"][0] ?? '');
            $state = addslashes($iptc["2#095"][0] ?? '');
            $country_code = addslashes($iptc["2#100"][0] ?? '');
            $country = addslashes($iptc["2#101"][0] ?? '');
            $headline = addslashes($iptc["2#105"][0] ?? '');
            $source = addslashes($iptc["2#115"][0] ?? '');
            $copyright = addslashes($iptc["2#116"][0] ?? '');
            $caption = addslashes($iptc["2#120"][0] ?? '');
        }
    
    //Bilddimensionen auslesen und festlegen.
    $width = $size[0];
    $height = $size[1];
    $picsize = $width."x".$height."px";
    
    //Check, ob die wesentlichen IPTC-Daten vorhanden sind
    if($headline != ""){
    
            if(mb_detect_encoding($headline, 'UTF-8, ISO-8859-1') === 'ISO-8859-1'){
                $headline               = utf8_encode($headline);
                $caption                = utf8_encode($caption);
                $city                   = utf8_encode($city);
                $location               = utf8_encode($location);
                $special_instructions   = utf8_encode($special_instructions);
                $copyright              = utf8_encode($copyright);
                $source                 = utf8_encode($source);
                $title                  = utf8_encode($title);
                $object_name            = utf8_encode($object_name);
            }

            //Check, ob das Bild schon in der DB vorhanden ist, oder ob es neu geschrieben wird.
            $stmt_check = "SELECT id FROM picture_data WHERE filename = '$filename'";
            $result_check = mysqli_query($link, $stmt_check);
            $check = mysqli_affected_rows($link);
        
            if($check > 0){
                $stmt_write  = "UPDATE picture_data SET timestamp = NOW( ), filename = '$filename', caption = '$caption', headline = '$headline', special_instructions = '$special_instructions', photographer = '$photographer', title = '$title', source = '$source', object_name = '$object_name', transref = '$transref', date = '$date', city = '$city', state = '$state', country = '$country', cat = '$cat', urgency = '$urgency', keywords = '$keywords', copyright = '$copyright', time = '$time', country_code = '$country_code', location = '$location', picsize = '$picsize' WHERE filename = '$filename'";
            } else {
                $stmt_write  = "INSERT INTO picture_data VALUES (NULL , CURRENT_TIMESTAMP , '$filename', '$caption', '$headline', '$special_instructions', '$photographer', '$title', '$source', '$object_name', '$transref', '$date', '$city', '$state', '$country', '$cat', '$urgency', '$keywords', '$copyright', '$time', '$country_code', '$location', '$picsize') ";
            }
        
            if(!preg_match('/^([\x09\x0A\x0D\x20-\x7E]|[\xC2][\xA0-\xBF]|[\xC3-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF]|\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2})*$/', $stmt_write)){
                $stmt_write  = utf8_decode($stmt_write);
            }
        
            $result_write = mysqli_query($link, $stmt_write);
            if (!$result_write) {
                logfile('DATENBANKFEHLER: Ungueltige Abfrage: ' . mysqli_error($link));
                logmail('DATENBANKFEHLER: Ungueltige Abfrage: ' . mysqli_error($link));
                echo "[" . date("d.m.Y h:i:s") . "] DATENBANKFEHLER: Kann Datensatz nicht schreiben\n";
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
        logfile('DBREADER: Es wurden '.$number.' Bilder im Upload-Verzeichnis gefunden');
        echo "[" . date("d.m.Y h:i:s") . "] DBREADER: Es wurden $number Bilder im Upload-Verzeichnis gefunden\n";

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
            echo "[" . date("d.m.Y h:i:s") . "] FEHLER: Datei $image_path fehlen IPTC-Daten, kein Import\n";
        }

        echo ". fertig\n";

    }

?>
