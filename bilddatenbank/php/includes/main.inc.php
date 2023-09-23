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

		//Maximale Anzahl von Suchergebnissen
		$maxfiles = $CONFIG['maxfiles'];

		//Startcounter fuer MySQL-Statement festlegen
		$start = $page*$anzthumbs;

		//View festlegen
		$view = "single";

		//Rewrite-Suche
 	    if(isset($_GET['rewrite'])){
			$q = mysql_real_escape_string($_GET['q']);
			$q = preg_replace("/\/bilddatenbank\//", "", $q);

			$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, caption, transref, photographer, date, city, picsize FROM picture_data WHERE transref = '$q' ORDER BY date DESC, time DESC LIMIT 0,".$maxfiles."";
			$query_thumbs = mysql_query($stmt_thumbs);
 	    }

 	    //Volltext-Suche 	    
		elseif(isset($_GET['q'])){

			//Ergaenzung durch eine Jahreszahl…
 	    	if(isset($_GET['year'])){
				$year = mysql_real_escape_string(trim($_GET['year']));
				$dateselect = " AND (date LIKE '".$year."%')";
			}

			$q = strtolower($_GET['q']);
			$q = preg_replace("/\//", "", $q);
			$q = preg_replace("/".INSTALLPATH."/", "", $q);

			$where = "(headline LIKE '%@@q@@%') OR (caption LIKE '%@@q@@%') OR (photographer LIKE '%@@q@@%') OR (location LIKE '%@@q@@%') OR (city LIKE '%@@q@@%') OR (keywords LIKE '%@@q@@%')";

			//Suchstring anpassen
			$stopwords = array();
			$stopwordfile = $_SERVER['DOCUMENT_ROOT'].INSTALLPATH."/lib/stoppwords_en.txt";

			$handle = fopen ($stopwordfile, "r");
			while (!feof($handle)) {
				$buffer = fgets($handle, 512);
				array_push($stopwords, ltrim(rtrim(strtolower($buffer))));
			}
			fclose ($handle);

			$stopwordfile = $_SERVER['DOCUMENT_ROOT'].INSTALLPATH."/lib/stoppwords_de.txt";

			$handle = fopen ($stopwordfile, "r");
			while (!feof($handle)) {
				$buffer = fgets($handle, 512);
				array_push($stopwords, ltrim(rtrim(strtolower($buffer))));
			}
			fclose ($handle);

			$q = preg_replace("/\"/iu", "", $q);
			$condition = preg_replace("/@@q@@/", $q, $where);
			$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, transref, photographer, picsize, city, date FROM picture_data WHERE (".$condition.")".$dateselect." ORDER BY date DESC, time DESC LIMIT 0,".$maxfiles."";		

			$query_thumbs = mysql_query($stmt_thumbs);
	
		}

		//Datumssuche
 	    elseif(isset($_GET['date'])){
	  		$date = explode("-", mysql_real_escape_string($_GET['date']));
	  		$startdate = $date[0];
	  		$enddate   = $date[1];

	  		if(isset($enddate)){
	  			$datestring = "date >= ".$enddate." AND date <= ".$startdate."";
	  		} else {
	  			$datestring = "date = ".$startdate."";
	  		}

	  		$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, transref, photographer, city, date, picsize FROM picture_data WHERE (".$datestring.") ORDER BY date DESC, time DESC LIMIT 0,".$maxfiles."";
	  		$query_thumbs = mysql_query($stmt_thumbs);
 	    }

			//Keyword-Suche
 	    elseif(isset($_GET['key'])){
			$q = mysql_real_escape_string($_GET['key']);
    		$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, transref, photographer, city, date, picsize FROM picture_data WHERE keywords LIKE '%".$q."%' ORDER BY date DESC, time DESC LIMIT 0,".$maxfiles."";
			$query_thumbs = mysql_query($stmt_thumbs);
 	    }

			//Collection-Suche
 	    elseif(isset($_GET['collection'])){
			$q = mysql_real_escape_string($_GET['collection']);
			$q = preg_replace("/\/bilddatenbank\//", "", $q);
			$q = preg_replace("/_/", " ", $q);
			$query_collection = mysql_query("SELECT * FROM collections WHERE name = '".$q."'");
			$out_collection = mysql_fetch_array($query_collection);

			$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, transref, photographer, city, date, picsize FROM picture_data WHERE id IN (".$out_collection['ids'].") ORDER BY object_name ASC LIMIT 0,".$maxfiles."";
			$query_thumbs = mysql_query($stmt_thumbs);
 	    }

		//Datenfeld-Suche
 	    elseif(isset($_GET['dbfield'])){
			$q = explode(":",$_GET['dbfield']);
			$type = $q[0];
			$value = $q[1];

			$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, transref, photographer, picsize FROM picture_data WHERE (".$type." LIKE '%".$value."%') ORDER BY date DESC, time DESC LIMIT 0,".$maxfiles."";
			$query_thumbs = mysql_query($stmt_thumbs);
 	    }

		//Darstellung aller Galerien mit Paginierung
		else {
			$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, transref, photographer, picsize, city, date FROM picture_data GROUP BY transref, city, date ORDER BY date DESC, time DESC LIMIT ".$start.",".$anzthumbs."";
			$query_thumbs = mysql_query($stmt_thumbs);
			//View ueberschreiben
			$view = "gallery";
		}

		$stmt_count_thumbs = "Select FOUND_ROWS()";
		$query_count_thumbs = mysql_query($stmt_count_thumbs);
		$num_files = mysql_result($query_count_thumbs, 0);

		//Wenn keine Ergebnisse vorhanden sind, auf die Fehlerseite weiterleiten
		if($num_files == 0){
			header("Location: ".INSTALLPATH."/404.html?q=".$_GET['q']);
			logfile($_GET['q'],"suchergebnis_null");
			exit;
		}

		if($_GET['debug'] == TRUE){
			echo $stmt_thumbs;
		}

		//Ausgabestring initialisieren
		$thumbs = "";

		if($view == "single"){
			
			//Überschrift erzeugen
			$query_title = "SELECT object_name FROM picture_data WHERE transref = '$q'";
			$result_title = mysql_query($query_title);
			$out_title = mysql_fetch_row($result_title);
			$title_gallery = $out_title[0];
			
			$thumbs .= "<h1>".$title_gallery."</h1>";
		
			//Bei eingeloggten Usern innerhalb der Galerien und Collections den Button fuer den ZIP-Download anzeigen
			if(isset($_GET['rewrite'])){

				if(isset($_SESSION['accessid']) OR isset($_SESSION['login'])){
					$query_zips = mysql_query($stmt_thumbs);

					$thumbs .= "<form action=\"/".INSTALLPATH."/zipdownload.php\" method=\"POST\">";
					$thumbs .= "<tr><td colspan=\"5\" valign=\"top\" align=\"left\">";

						while ($zipout = mysql_fetch_array($query_zips)){
							$thumbs .= "<input type=\"hidden\" name=\"zip[]\" value=\"".$zipout['filename']."\">";
						}

					$thumbs .= "<div class=\"zipbox\">";
					$thumbs .= "<input type=\"submit\" value=\"Alle Bilder als ZIP-Datei herunterladen...\" style=\"cursor:pointer\"></div>";
					$thumbs .= "</td></tr>";
					$thumbs .= "</form>";

				}
			}

		//Produktion der Thumbnails

		$thumbs .= "<tr><td colspan=\"5\">\n";
		$thumbs .= "<div>\n";

		while ($out = mysql_fetch_array($query_thumbs)){

			$size = @getimagesize($_SERVER['DOCUMENT_ROOT']."/".INSTALLPATH."/thumbs/".$out['filename']);

					$thumbs .= "<div class=\"thumbcontainer\">\n";
					$thumbs .= "<div class=\"thumbimg\"><a href=\"/".INSTALLPATH."/detail.php?id=".$out['id']."\" title=\"".$out['object_name'].", ".$out['city']." ".substr($out['date'],0,4)."\"";
					$thumbs .= " onclick=\"detail('/".INSTALLPATH."/detail.php?id=".$out['id']."');return false;\">\n";
					$thumbs .= "<img src=\"/".INSTALLPATH."/thumbs/".$out['filename']."\" width=\"".$size[0]."\" height=\"".$size[1]."\" border=\"0\" alt=\"".$out['headline']." (&copy; ".$out['photographer'].")\" /></a></div>\n";
					$thumbs .= "<div class=\"thumbfunc\"><a href=\"/".INSTALLPATH."/detail.php?id=".$out['id']."\" title=\"".$out['object_name'].", ".$out['city']." ".substr($out['date'],0,4)."\"";
					$thumbs .= " onclick=\"detail('/".INSTALLPATH."/detail.php?id=".$out['id']."');return false;\" title=\"".$out['object_name']."\">".$out['object_name']."</a> (Nr. ".$out['id'].")<br />";

					//Download-Button
					if(isset($_SESSION['login'])){
						$thumbs .= "<a href=\"/".INSTALLPATH."/download.php?id=".$out['id']."\"><img src=\"/".INSTALLPATH."/images/icons/save.gif\" width=\"24\" height=\"22\" class=\"imgicon\" title=\"Bild herunterladen\"></a>";
					} else {
						$thumbs .= "<a href=\"/".INSTALLPATH."/register.php?KeepThis=true&TB_iframe=true&height=460&width=760\" class=\"thickbox\"><img src=\"/".INSTALLPATH."/images/icons/save.gif\" width=\"24\" height=\"22\" class=\"imgicon\"></a>";
					}

					//Lightbox-Button
					if(isset($_SESSION['login'])){
						$thumbs .= "<a href=\"/".INSTALLPATH."/addto.php?action=add&id=".$out['id']."\"><img src=\"/".INSTALLPATH."/images/icons/lightbox.gif\" width=\"24\" height=\"22\" class=\"imgicon\" title=\"Bild in die Lightbox legen\"></a>";
					} else {
						$thumbs .= "<a href=\"/".INSTALLPATH."/register.php?KeepThis=true&TB_iframe=true&height=460&width=760\" class=\"thickbox\"><img src=\"/".INSTALLPATH."/images/icons/lightbox.gif\" width=\"24\" height=\"22\" class=\"imgicon\"></a>";
					}

					//Email-Button
					if(isset($_SESSION['login'])){
						if($_SESSION['login'] != "newsletter"){
							$thumbs .= "<a href=\"/".INSTALLPATH."/mailer.php?id=".$out['id']."\"><img src=\"/".INSTALLPATH."/images/icons/sendmail.gif\" width=\"24\" height=\"22\" class=\"imgicon\" title=\"Bild per Email zusenden\"></a>";
						} else {
							$thumbs .= "<a href=\"/".INSTALLPATH."/mailinfo.php?KeepThis=true&TB_iframe=true&height=460&width=760\" class=\"thickbox\"><img src=\"/".INSTALLPATH."/images/icons/sendmail.gif\" width=\"24\" height=\"22\" class=\"imgicon\"></a>";
						}
					} else {
						$thumbs .= "<a href=\"/".INSTALLPATH."/register.php?KeepThis=true&TB_iframe=true&height=460&width=760\" class=\"thickbox\"><img src=\"/".INSTALLPATH."/images/icons/sendmail.gif\" width=\"24\" height=\"22\" class=\"imgicon\"></a>";
					}

					//Detailansicht-Button
					$thumbs .= "<a href=\"/".INSTALLPATH."/detail.php?id=".$out['id']."\" title=\"".$out['object_name'].", ".$out['city']." ".substr($out['date'],0,4)."\" onclick=\"detail('/".INSTALLPATH."/detail.php?id=".$out['id']."');return false;\">";
					$thumbs .= "<img src=\"/".INSTALLPATH."/images/icons/info.gif\" width=\"24\" height=\"22\" class=\"imgicon\" title=\"Detailansicht &ouml;ffnen\"></a></div>";
					$thumbs .= "</div>\n";
		}
		$thumbs .= "</div>\n";
		$thumbs .= "</td>";

		$thumbs .= "</tr>\n";

	  } elseif($view == "gallery") {

			$thumbs .= "<tr><td colspan=\"5\">\n";
			$thumbs .= "<div>\n";

			while ($out = mysql_fetch_array($query_thumbs)){
				$stmt_details = "SELECT COUNT(id) FROM picture_data where (transref = '".$out['transref']."')";
				$query_details = mysql_query($stmt_details);
				$num_files_details = mysql_fetch_array($query_details);
				
				if(strlen($out['object_name']) > 50){
					$object_name = substr($out['object_name'], 0, 50)."...";
				} else {
					$object_name = $out['object_name'];
				}

				$thumbs .= "<div class=\"thumbcontainer\">\n";
				$thumbs .= "<div class=\"thumbimg\"><a href=\"/".INSTALLPATH."/".$out['transref']."\" title=\"".$out['object_name']."\">\n";
				$thumbs .= "<img src=\"/".INSTALLPATH."/thumbs/".$out['filename']."\" border=\"0\" alt=\"".$out['headline']." (&copy; ".$out['photographer'].")\" /></a></div>\n";
				$thumbs .= "<div class=\"thumbfunc\"><a href=\"/".INSTALLPATH."/".$out['transref']."\" title=\"".$out['object_name']."\"><h3>".$object_name."</h3></a>";
				$thumbs .= substr($out['date'],6,2).".".substr($out['date'],4,2).".".substr($out['date'],0,4).", ";
				$thumbs .= $num_files_details[0]." Bilder";

				$thumbs .= "</div></div>\n";
			}

			$thumbs .= "</div>\n";
			$thumbs .= "</td>";
			$thumbs .= "</tr>\n";

			}

	echo $thumbs;

?>
