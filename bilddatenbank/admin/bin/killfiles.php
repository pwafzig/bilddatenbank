<?php include("../../php/includes/start.inc.php"); ?>
<?php

	if(!$_GET){
		if(isset($_POST['killpath'])){
			for ($i="0"; $i<count($_POST['killpath']); $i++) {

				//Unlink auf Data, Preview und Thumbs
		    	$prevpath 	= DOCROOT."/".INSTALLPATH."/previews/".$_POST['killpath'][$i];
		    	$lowrespath = DOCROOT."/".INSTALLPATH."/lowres/".$_POST['killpath'][$i];
		   		$thumbpath 	= DOCROOT."/".INSTALLPATH."/thumbs/".$_POST['killpath'][$i];
		    	$datapath 	= DOCROOT."/".INSTALLPATH."/data/".$_POST['killpath'][$i];

			    if((!unlink($prevpath)) OR (!unlink($lowrespath) OR (!unlink($thumbpath)) OR (!unlink($datapath)))){

			    	logfile("Deletion of ".$_POST['killpath'][$i]." failed", "error");
			    	header("Location:/".INSTALLPATH."/admin/index.php?sect=bildverwaltung&err=1");
			    	exit;

			    } else {

			    	logfile("Deleted ".$_POST['killpath'][$i]." from filesystem", "bilddatenbank");

			      	//Eintrag aus Datenbank loeschen
					$stmt = "DELETE FROM picture_data WHERE filename = '".$_POST['killpath'][$i]."'";
					if (!mysqli_query($link, $stmt)) {
    					logfile(mysql_errno($link) . ": " . mysql_error($link), "error");
					} else {
						logfile("Deleted ".$_POST['killpath'][$i]." from database", "bilddatenbank");
					}

				}

			}

			header("Location:/".INSTALLPATH."/admin/index.php?sect=bildverwaltung");
		} else {
			header("Location:/".INSTALLPATH."/admin/index.php?sect=bildverwaltung");
		}
	}

?>