<?php
    if(!isset($_SESSION['adminlogin'])){
   	   	exit;
   	}

	//Tabelle mit existierenden Benutzern ausgeben

	$stmt = "SELECT * FROM users ORDER BY id";
	$query = mysqli_query($link, $stmt);
	
	$html  = "<a href=\"/".INSTALLPATH."/admin/bin/edituser.php?action=add&KeepThis=true&TB_iframe=true&height=540&width=810\" class=\"thickbox\" id=\"add\"></a><a href=\"/".INSTALLPATH."/admin/bin/edituser.php?action=add&KeepThis=true&TB_iframe=true&height=540&width=810\" class=\"thickbox\">Neuen Benutzer anlegen</a><br /><br />";

	$html .= "<table id=\"admintable\" border=\"1\" width=\"100%\">\n";
	$html .= "<thead><tr><th class=\"header\" width=\"40\">Nr.</th><th class=\"header\">Vorname</th><th class=\"header\">Nachname</th><th class=\"header\">Organisation</th><th>Email</th><th class=\"header\">Login</th><th class=\"header\">Download</th><th class=\"header\" width=\"100\">Downloads</th><th class=\"header\">Zuletzt eingeloggt</th><th class=\"header\">Angelegt</th><th></th></tr></thead><tbody>\n";

	while($out = mysqli_fetch_array($query, MYSQLI_ASSOC)){
		$html .= "<tr><td align=\"center\">".$out['id']."</td><td>".$out['prename']."</td><td>".$out['name']."</td><td>".$out['organisation']."</td><td>".$out['email']."</td><td>".$out['login']."</td><td>".$out['resolution']."</td><td align=\"center\">".$out['downloads']."</td><td>".$out['lastlogin']."</td><td>".$out['timestamp']."</td><td><a href=\"/".INSTALLPATH."/admin/bin/edituser.php?id=".$out['id']."&action=edit&KeepThis=true&TB_iframe=true&height=540&width=810\" class=\"thickbox\" id=\"edit\"></a><a href=\"/".INSTALLPATH."/admin/bin/edituser.php?id=".$out['id']."&action=kill&KeepThis=true&TB_iframe=true&height=540&width=810\" class=\"thickbox\" id=\"delete\"></a></td></tr>\n";
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