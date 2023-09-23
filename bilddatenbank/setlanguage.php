<?php
	@session_start();
	
	if($_GET['lang'] == "de" || $_GET['lang'] == "en"){
	
	    $_SESSION['lang'] = $_GET['lang'];
    	header("Location:".$_SERVER['HTTP_REFERER']);
    	exit;
    	
    } else {
    
    	echo "Nice try...";
    	exit;
    
    }
?>