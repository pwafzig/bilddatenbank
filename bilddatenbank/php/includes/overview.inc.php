<?php

	mysql_query("SET CHARACTER SET 'utf8'");

	//Seitenzahl festlegen bzw. am Anfang nullen
	if(!isset($_GET['page'])){
			$page = 0;
	} else {
			$page = $_GET['page']-1;
	}

	//Anzahl der Bilder pro Seite
	$anzthumbs = $CONFIG['anzthumbs'];

	//Startcounter fuer MySQL-Statement festlegen
	$start = $page*$anzthumbs;

	//Abfrage, ob hier gerade eine Suche laeuft

	$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, transref, photographer, picsize, city, date FROM picture_data GROUP BY transref, city, date ORDER BY date DESC, time DESC LIMIT ".$start.",".$anzthumbs."";
	$query_thumbs = mysql_query($stmt_thumbs);

	$stmt_count_thumbs = "Select FOUND_ROWS()";
	$query_count_thumbs = mysql_query($stmt_count_thumbs);
	$num_files = mysql_fetch_array($query_count_thumbs);

	//Ausgabestring initialisieren

	$thumbs = "";

	//Produktion der Thumbnails
	if($num_files == 0){
		$thumbs .= "<tr><td colspan=\"5\" height=\"350\">".$TEXT['search-noresults']."</td>\n";
		logfile($_GET['q'],"suchergebnis_null");
	} else {
		$thumbs .= "<tr><td colspan=\"5\">\n";
		$thumbs .= "<div>\n";
		while ($out = mysql_fetch_array($query_thumbs)){

			$stmt_details = "SELECT COUNT(id) FROM picture_data where transref = '".$out['transref']."' AND date = '".$out['date']."'";
			$query_details = mysql_query($stmt_details);
			$num_files_details = mysql_fetch_array($query_details);

			$thumbs .= "<div class=\"thumbcontainer\">\n";
			$thumbs .= "<div class=\"thumbimg\"><a href=\"/".INSTALLPATH."/".$out['transref']."\" title=\"".$out['headline']." (&copy; ".$out['photographer'].")\">\n";
			$thumbs .= "<img src=\"/".INSTALLPATH."/thumbs/".$out['filename']."\" border=\"0\" title=\"".$out['headline']." (&copy; ".$out['photographer'].")\" /></a></div>\n";
			$thumbs .= "<div class=\"thumbfunc\"><a href=\"/".INSTALLPATH."/".$out['transref']."\" title=\"".$out['headline']." (&copy; ".$out['photographer'].")\">".$out['headline']."</a><br />";
			$thumbs .= substr($out['date'],6,2).".".substr($out['date'],4,2).".".substr($out['date'],0,4).", ";
			$thumbs .= $num_files_details[0]." Bilder";
			$thumbs .= "</div></div>\n";
		}
		$thumbs .= "</div>\n";
		$thumbs .= "</td>";
	}

	$thumbs .= "</tr>\n";
	echo $thumbs;

?>
