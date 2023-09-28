<?php include("php/includes/start.inc.php"); ?>
<?php

	//Ueberpruefe, ob eine Session vorhanden ist

    if(isset($_SESSION['login'])) {

		//Ueberpruefe, ob eine id uebergeben wurde

		if(isset($_GET['id'])){
				$id = intval(@$_GET['id']);
		} else {
			echo $_SESSION['lightboxids'];
		}

		if($_GET['action'] == "add"){

	        //Ueberpruefen, ob schon IDs in der Session vorhanden sind...

	        if(isset($_SESSION['lightboxids'])) {

	        	if(!preg_match("/".$id."/",$_SESSION['lightboxids'])){
		        	$_SESSION['lightboxids'] = $_SESSION['lightboxids'].$id."|";
		        }
	        } else {
	        	$_SESSION['lightboxids'] = $id."|";
	        }
	    }

	     if($_GET['action'] == "delete") {

	        if(isset($_SESSION['lightboxids'])) {
	        	if(preg_match("/".$id."/",$_SESSION['lightboxids'])){
	        		$_SESSION['lightboxids'] = str_replace($id."|", "", $_SESSION['lightboxids']);
		        }
	        } else {
	        	$_SESSION['lightboxids'] = "";
	        }

	     }

	     header("Location:".$_SERVER['HTTP_REFERER']);

	} else {
		header("Location:".INSTALLPATH."/index.html?err=12");
		exit;
	}

?>