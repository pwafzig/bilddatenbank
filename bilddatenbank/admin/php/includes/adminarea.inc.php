<?php
    if(!isset($_SESSION['adminlogin'])){
   	   	exit;
   	}
    
	$sect = "";

	if(!isset($_GET['sect'])) {
		include(DOCROOT."/".INSTALLPATH."/admin/php/includes/home.inc.php");
	} else {
		$sect = $_GET['sect'];
	}

	if($sect == "bildverwaltung") {
		include(DOCROOT."/".INSTALLPATH."/admin/php/includes/bildverwaltung.inc.php");
	}

	if($sect == "benutzer") {
		include(DOCROOT."/".INSTALLPATH."/admin/php/includes/benutzer.inc.php");
	}

	if($sect == "accessids") {
		include(DOCROOT."/".INSTALLPATH."/admin/php/includes/accessids.inc.php");
	}

	if($sect == "collections") {
		include(DOCROOT."/".INSTALLPATH."/admin/php/includes/collections.inc.php");
	}

	if($sect == "backup") {
		include(DOCROOT."/".INSTALLPATH."/admin/php/includes/backups.inc.php");
	}

	if($sect == "stats") {
		include(DOCROOT."/".INSTALLPATH."/admin/php/includes/stats.inc.php");
	}

	if($sect == "konfiguration") {
		include(DOCROOT."/".INSTALLPATH."/admin/php/includes/konfiguration.inc.php");
	}


?>