<?php
    if(!isset($_SESSION[adminlogin])){
   	   	exit;
   	}
    
	//Tabelle mit existierenden Access-IDs ausgeben

	$stmt = "SELECT * FROM accessids ORDER BY id";
	$query = mysql_query($stmt);
	
	$html  = "<a href=\"/".INSTALLPATH."/admin/bin/editaccessids.php?id=".$out['id']."&action=add&KeepThis=true&TB_iframe=true&height=540&width=810\" class=\"thickbox\" id=\"add\"></a><a href=\"/".INSTALLPATH."/admin/bin/editaccessids.php?id=".$out['id']."&action=add&KeepThis=true&TB_iframe=true&height=540&width=810\" class=\"thickbox\">Neue Access-ID anlegen</a><br /><br />";

	$html .= "<table id=\"admintable\" border=\"1\" width=\"100%\">\n";
	$html .= "<thead><tr><th class=\"header\" width=\"40\">Nr.</th><th class=\"header\">Name</th><th>Bemerkung</th><th>Auflösung</th><th>Ablauf</th><th class=\"header\" width=\"100\">Downloads</th><th class=\"header\">Zuletzt eingeloggt</th><th class=\"header\">Angelegt</th><th>Test</th><th></th></tr></thead><tbody>\n";

	while($out = mysql_fetch_array($query)){
	
		$year = substr($out['date'], 0, 4);
		$mon  = substr($out['date'], 4, 2);
		$day  = substr($out['date'], 6, 2);
	
		$html .= "<tr><td align=\"center\">".$out['id']."</td><td>".$out['name']."</td><td>".$out['bemerkung']."</td><td>".$out['resolution']."</td><td align=\"center\">".$day.".".$mon.".".$year."</td><td align=\"center\">".$out['downloads']."</td><td>".$out['lastlogin']."</td><td>".$out['timestamp']."</td><td align=\"center\"><a href=\"http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/index.php?accessid=".$out['hash']."\" target=\"_blank\">Test</a></td><td><a href=\"/".INSTALLPATH."/admin/bin/editaccessids.php?id=".$out['id']."&action=edit&KeepThis=true&TB_iframe=true&height=540&width=810\" class=\"thickbox\" id=\"edit\"></a><a href=\"/".INSTALLPATH."/admin/bin/editaccessids.php?id=".$out['id']."&action=kill&KeepThis=true&TB_iframe=true&height=540&width=810\" class=\"thickbox\" id=\"delete\"></a></td></tr>\n";
	}

	$html .= "</tbody></table>\n";
	echo $html;

?>

<script language="javascript">
	$(document).ready(function()
	    {
	        $("#admintable").tablesorter({sortList: [[0,0]], headers: { 2: {sorter: false}, 3: {sorter: false}, 7: {sorter: false}, 8: {sorter: false}}});
	    }
	);
</script>