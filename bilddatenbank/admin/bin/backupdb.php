<?php 
	include("../../php/includes/start.inc.php");

	$backupfile = $_SERVER['DOCUMENT_ROOT']."/".INSTALLPATH."/admin/backup/".date('YmdHis')."_backup.sql";

	exec("mysqldump --user=".DB_USER." --password=".DB_PWD." --host=".DB_HOST." ".DB_NAME." > ".$backupfile."");
	
	header("Location:http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/admin/index.php?sect=backup");

?>