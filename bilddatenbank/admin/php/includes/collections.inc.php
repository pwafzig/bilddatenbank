<?php
    if(!isset($_SESSION[adminlogin])){
   	   	exit;
   	}

	//Tabelle mit existierenden Access-IDs ausgeben

	$stmt = "SELECT * FROM collections ORDER BY id";
	$query = mysql_query($stmt);

	$html  = "<a href=\"/".INSTALLPATH."/admin/bin/editcollections.php?id=".$out['id']."&action=add&KeepThis=true&TB_iframe=true&height=540&width=810\" class=\"thickbox\" id=\"add\"></a><a href=\"/".INSTALLPATH."/admin/bin/editcollections.php?id=".$out['id']."&action=add&KeepThis=true&TB_iframe=true&height=540&width=810\" class=\"thickbox\">Neue Kollektion anlegen</a><br /><br />";

	$html .= "<table id=\"admintable\" border=\"1\" width=\"100%\">\n";
	$html .= "<thead><tr><th class=\"header\" width=\"40\">Nr.</th><th class=\"header\">Name</th><th>Bemerkung</th><th>Enthaltene Bilder</th><th>Test</th><th></th></tr></thead><tbody>\n";

	while($out = mysql_fetch_array($query)){

		$slug = preg_replace("/ /","_",$out['name']);

		$html .= "<tr><td align=\"center\">".$out['id']."</td><td>".$out['name']."</td><td>".$out['comment']."</td><td align=\"center\">".$out['ids']."</td><td align=\"center\"><a href=\"http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/collection/".$slug."\" target=\"_blank\">Test</a></td><td><a href=\"/".INSTALLPATH."/admin/bin/editcollections.php?id=".$out['id']."&action=edit&KeepThis=true&TB_iframe=true&height=540&width=810\" class=\"thickbox\" id=\"edit\"></a><a href=\"/".INSTALLPATH."/admin/bin/editcollections.php?id=".$out['id']."&action=kill&KeepThis=true&TB_iframe=true&height=540&width=810\" class=\"thickbox\" id=\"delete\"></a></td></tr>\n";
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