<?php

	/**************** Basiskonfiguration einlesen *****************/

	$installcheck = file_get_contents($_SERVER['DOCUMENT_ROOT']."/config.inc.php");
	if(preg_match("/@@/", $installcheck)) {
		include("lib/notfound.html");
        exit;
    } else {
		include($_SERVER['DOCUMENT_ROOT']."/config.inc.php");
	}



    /**************** Error-Reporting einstellen *****************/

    if(!DEBUG == "true"){
        error_reporting(E_ALL & E_NOTICE & E_STRICT);
        ini_set("display_errors", "on");
    }



	/**************** GZip-Komprimierung starten ******************/

	if(strpos($_SERVER['PHP_SELF'], "zipdownload.php") != true){
		//ob_start("ob_gzhandler");
	}



	/**************** Sessions initialisieren ****************/

    @session_start();



	/**************** Datenbankverbindung herstellen ****************/

	include(DOCROOT.INSTALLPATH."/secure/dbconnect.inc.php");

    $link = mysqli_connect(DB_HOST, DB_USER, DB_PWD);
    if (!$link) {
    	include("lib/notfound.html");
        exit;
    }

    $db_selected = mysqli_select_db($link, DB_NAME);
    if (!$db_selected) {
        include("lib/notfound.html");
        exit;
    }



	/**************** Konfiguration aus der Datenbank laden ****************/

	//Alle Werte aus der Konfigurationstabelle lesen
	$stmt = "SELECT * FROM konfig";
	$query = mysqli_query($link, $stmt);

	while($out = mysqli_fetch_array($query, MYSQLI_NUM)){
		$CONFIG[$out[1]] = $out[2] ?? null;
	}



	/**************** Zeitzone einstellenn ****************/

	date_default_timezone_set('Europe/Berlin');



    /**************** Allgemeine Funktionen ****************/

	function logfile($msg,$logfile)
	{
		$logname = DOCROOT.INSTALLPATH."/logs/".$logfile.".log";
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

    function secure_input($param) 
    {
        global $link;
        
        $param = strip_tags(mysqli_real_escape_string($link, trim($param)));
        return $param; 
    }


    /**************** Admin-Login absichern ***************/

    if(preg_match("/admin/", $_SERVER['PHP_SELF']) && !preg_match("/admin\/login.php/", $_SERVER['PHP_SELF'])){
	    if(!isset($_SESSION['adminlogin'])){
    	   	header("Location:http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/admin/login.php");
    	   	exit;
    	}
    }



    /**************** Access-IDs abfragen ****************/

    //Wenn ein gueltiger Hash an die URL angehaengt ist, wird eine Session mit Daten aus der Datenbank gestartet
    if(isset($_GET['accessid'])){

        if(get_magic_quotes_gpc()) {
            $accessid = stripslashes($_GET['accessid']);
        } else {
            $accessid = $_GET['accessid'];
        }

        $accessid = mysqli_real_escape_string($link, $accessid);

        $stmt_access = "SELECT * FROM accessids WHERE hash = '".$accessid."'";
        $query_access = mysqli_query($link, $stmt_access);
        $result_access = mysqli_fetch_array($query_access, MYSQLI_ASSOC);

        //Aktuelles Datum feststellen und mit hinterlegtem Datum vergleichen, um Ablauf festzustellen
        $actdate = date("Ymd");
        if($result_access['date'] < $actdate){
            echo "Abgelaufen...";
            exit;
        }

        $stmt_log = "UPDATE accessids SET lastlogin = NOW( ) WHERE hash = '".$accessid."' LIMIT 1";
        mysqli_query($link, $stmt_log);

        $_SESSION['login']          =   "Access-ID";
        $_SESSION['name']           =   $result_access['name'];
        $_SESSION['accessid']       =   $accessid;
        $_SESSION['lang'] 			=   $result_access['lang'];
        $_SESSION['resolution']		=   $result_access['resolution'];
    }



    /**************** Sprachvariablen laden ****************/

    //Fallback, falls die Sprache nicht gesetzt war
    if(!isset($_SESSION['lang'])){
        $_SESSION['lang'] = "de";
        $lang = "de";
    } else {
        $lang = $_SESSION['lang'];
    }

	include(DOCROOT.INSTALLPATH."/php/lang/$lang.inc.php");



?>