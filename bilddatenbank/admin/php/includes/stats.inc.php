<?php
    if(!isset($_SESSION[adminlogin])){
   	   	exit;
   	}
	
	$stmt = "SELECT DATE_FORMAT(downloads.timestamp, '%Y-%m-%d') AS datestr, downloads.ip AS ip, DATE_FORMAT(downloads.timestamp, '%d.%m.%Y, %H:%i') AS dateshow, downloads.user, downloads.filename AS filename, picture_data.id AS id FROM downloads, picture_data WHERE picture_data.filename = downloads.filename ORDER BY downloads.timestamp DESC LIMIT 0,50";
	
	$query = mysql_query($stmt);

	$html  = "<h3>Aktuelle Downloads (Top 50)</h3><br /><table id=\"admintable\" border=\"1\" width=\"100%\">\n";
	$html .= "<thead><tr><th>Datum</th><th>Benutzer</th><th>Dateiname</th><th>Vorschau</th><th>IP-Adresse</th></tr></thead><tbody>\n";

	while($out = mysql_fetch_array($query)){
		
		$filename = substr($out['filename'], 0, 8)."/".$out['filename'];

		$html .= "<tr><td align=\"center\">".$out['dateshow']."</td>";	
		$html .= "<td align=\"center\">".$out['user']."</td>";
		$html .= "<td align=\"center\">".$out['filename']."</td>";
		$html .= "<td align=\"center\"><a href=\"/".INSTALLPATH."/admin/bin/showimgdetails.php?id=".$out['id']."&width=500\" class=\"jTip\" id=\"".$out['id']."\" name=\"Vorschau:\"><img src=\"/".INSTALLPATH."/thumbs/".$out['filename']."\" border=\"0\" width=\"33\" height=\"22\"/></a></td>";
		$html .= "<td align=\"center\">$out[ip]</td></tr>";
	}

	$html .= "</tbody></table>\n";
	echo $html;
	
	
	$stmt = "SELECT COUNT(downloads.filename) AS hits, downloads.filename AS filename, picture_data.id AS id FROM downloads, picture_data WHERE (picture_data.filename = downloads.filename) GROUP BY filename ORDER BY hits DESC LIMIT 0,10";

	$query = mysql_query($stmt);

	$html  = "<br /><br /><h3>Top 10 der beliebtesten Dateien</h3><br /><table id=\"admintable\" border=\"1\" width=\"100%\">\n";
	$html .= "<thead><tr><th>Downloads</th><th>Datei</th><th>Vorschau</th></tr></thead><tbody>\n";

	while($out = mysql_fetch_array($query)){
		
		$filename = substr($out['filename'], 0, 8)."/".$out['filename'];

		$html .= "<tr><td align=\"center\">".$out['hits']."</td>";	
		$html .= "<td align=\"center\">".$out['filename']."</td>";
		$html .= "<td align=\"center\"><a href=\"/".INSTALLPATH."/admin/bin/showimgdetails.php?id=".$out['id']."&width=170\" class=\"jTip\" id=\"".$out['id']."_1\" name=\"Vorschau:\"><img src=\"/".INSTALLPATH."/thumbs/".$out['filename']."\" border=\"0\" width=\"33\" height=\"22\"/></a></td></tr>";
	}

	$html .= "</tbody></table>\n";
	echo $html;	
	

?>