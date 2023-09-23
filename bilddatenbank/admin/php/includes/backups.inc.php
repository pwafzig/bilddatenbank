<?php
    if(!isset($_SESSION[adminlogin])){
   	   	exit;
   	}
    
	$backups = array();
	
	if ($handle = opendir('backup/')) {
	    while (false !== ($file = readdir($handle))) {
	        if ($file != "." && $file != ".." && $file != "index.html") {
	            array_push($backups, $file);
	        }
	    }
	    closedir($handle);
	}

	$html  = "<a href=\"/".INSTALLPATH."/admin/bin/backupdb.php\" id=\"add\"></a><a href=\"/".INSTALLPATH."/admin/bin/backupdb.php\">Neues Backup anlegen</a><br /><br />";

	$html .= "<table id=\"admintable\" border=\"1\" width=\"100%\">\n";
	$html .= "<thead><tr><th class=\"header\" width=\"40\">Nr.</th><th class=\"header\">Datum</th><th class=\"header\">Dateigr&ouml;&szlig;e</th><th>Download</th></tr></thead><tbody>\n";
	
	$i = 1;
	
	foreach ($backups as &$value) {
		$year  = substr($value, 0, 4);
		$month = substr($value, 4, 2);
		$day   = substr($value, 6, 2);
		$hour  = substr($value, 8, 2);
		$min   = substr($value, 10,2);
		
		$filesize = filesize(DOCROOT.INSTALLPATH."/admin/backup/".$value);
		$filesize = $filesize / 1024;
		$filesize = round($filesize, 2);
		
    	$html .= "<tr><td align=\"center\">".$i."</td><td align=\"center\">".$day.".".$month.".".$year.", ".$hour.":".$min." Uhr</td><td align=\"center\">".$filesize." kb</td><td align=\"center\"><a href=\"/".INSTALLPATH."/admin/backup/".$value."\" target=\"_blank\">".$value."</a></td></tr>\n";
    	$i++;
	}

	$html .= "</tbody></table>\n";
	echo $html;
	
?>

<script language="javascript">
	$(document).ready(function()
	    {
	        $("#admintable").tablesorter({sortList: [[0,0]], headers: { 3:{sorter: false}}});
	    }
	);
</script>