<?php include("../../php/includes/start.inc.php"); ?>
<?php
	$killpath = DOCROOT.INSTALLPATH."/logs/suchergebnis_null.log";
    if(!unlink($killpath)){
    	logfile("Deletion of suchergebnis_null.log failed", "error");
	    header("Location:/".INSTALLPATH."/admin/index.php");
	    exit;
	} else {
	    logfile("Deleted suchergebnis_null.log", "bilddatenbank");
	    fclose(fopen($killpath, 'a'));
	}

	header("Location:/".INSTALLPATH."/admin/index.php");

?>