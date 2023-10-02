<?php
	if(!is_file("config.inc.php")){
		header("Location:/bilddatenbank/install");	
	} else {
		header("Location:/bilddatenbank");	
	}	
?>