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
	$maxfiles = $CONFIG['maxfiles'];

	//Startcounter fuer MySQL-Statement festlegen
	$start = $page*$anzthumbs;

	//Wenn die Anfrage ueber das Rewriting kommt, dann hier weitermachen...
	if (isset($_GET['rewrite']) && $_GET['rewrite'] == "true"){

		//Hier wird der vom rewrite uebergebene Query umgeformt, damit die Datenbank das versteht...
		//Abfrage fuer die Keywords/Tags

		if (isset($_GET['k'])){

			if($debug == true) { echo "DEBUG: Keyword-Suche"; }

			$q = mysql_real_escape_string($_GET['q']);

			$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, object_name, caption, photographer, picsize FROM picture_data WHERE keywords LIKE '%".$q."%' ORDER BY date DESC, time DESC LIMIT 0,".$maxfiles."";

			$query_thumbs = mysql_query($stmt_thumbs);
			$stmt_count_thumbs = "Select FOUND_ROWS()";
			$query_count_thumbs = mysql_query($stmt_count_thumbs);
			$num_files = mysql_result($query_count_thumbs, 0);

		}

		//Abfrage fuer die Collections

		elseif (isset($_GET['c'])){

			if($debug == true) { echo "DEBUG: Collection-Suche"; }

			$q = mysql_real_escape_string($_GET['q']);
			$q = preg_replace("/_/", " ", $q);

			$query_collection = mysql_query("SELECT * FROM collections WHERE name = '".$q."'");
			$out_collection = mysql_fetch_array($query_collection);

			$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, photographer, city, date, picsize FROM picture_data WHERE id IN (".$out_collection['ids'].") ORDER BY date DESC, time DESC LIMIT 0,".$maxfiles."";

			$query_thumbs = mysql_query($stmt_thumbs);
			$stmt_count_thumbs = "Select FOUND_ROWS()";
			$query_count_thumbs = mysql_query($stmt_count_thumbs);
			$num_files = mysql_result($query_count_thumbs, 0);

		//Abfrage fuer die Generierung der Galerien

		} else {

			if($debug == true) { echo "DEBUG: Galerie-Ansicht"; }

			$q = $_GET['q'];
			$q = preg_replace("/\/".INSTALLPATH."\//", "", $q);
			$q = mysql_real_escape_string($q);

			$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, object_name, caption, photographer, picsize FROM picture_data WHERE transref = '".$q."' ORDER BY date DESC, time DESC";

			$query_thumbs = mysql_query($stmt_thumbs);
			$stmt_count_thumbs = "Select FOUND_ROWS()";
			$query_count_thumbs = mysql_query($stmt_count_thumbs);
			$num_files = mysql_result($query_count_thumbs, 0);

		}

	//Wenn kein Rewriting vorliegt...
	} else {

		//...und keine Suchanfrage
		if(!(isset($_GET['q']) && $_GET['q'] != '')){

			//Datums-Suche?
			if (isset($_GET['date'])){

				$date = explode("-", mysql_real_escape_string($_GET['date']));
				$startdate = $date[0];
				$enddate   = $date[1];

				if(isset($enddate)){
					$datestring = "date >= ".$enddate." AND date <= ".$startdate."";
				} else {
					$datestring = "date = ".$startdate."";
				}

				$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, photographer, city, date, picsize FROM picture_data WHERE (".$datestring.") ORDER BY date DESC, time DESC LIMIT 0,".$maxfiles."";
				$query_thumbs = mysql_query($stmt_thumbs);

				$stmt_count_thumbs = "Select FOUND_ROWS()";
				$query_count_thumbs = mysql_query($stmt_count_thumbs);
				$num_files = mysql_result($query_count_thumbs, 0);

			} else {
			//Ganz normale Galerieansicht in chronologischer Reihenfolge

				$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, photographer, city, date, picsize FROM picture_data ORDER BY date DESC, time DESC LIMIT ".$start.",".$anzthumbs."";
				$query_thumbs = mysql_query($stmt_thumbs);

				$stmt_count_thumbs = "Select FOUND_ROWS()";
				$query_count_thumbs = mysql_query($stmt_count_thumbs);
				$num_files = mysql_result($query_count_thumbs, 0);

			}

		} else {

			//Abfrage fuer die Suche nach bestimmten Datenbankfelder
			if (preg_match("/:/",$_GET['q'])){

				if($debug == true) { echo "DEBUG: Datenbankfeld-Suche"; }

				$q = explode(":",$_GET['q']);
				$type = $q[0];
				$value = $q[1];

				$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, photographer, picsize FROM picture_data WHERE (".$type." LIKE '%".$value."%') ORDER BY date DESC, time DESC LIMIT 0,".$maxfiles."";

				$query_thumbs = mysql_query($stmt_thumbs);

				$stmt_count_thumbs = "Select FOUND_ROWS()";
				$query_count_thumbs = mysql_query($stmt_count_thumbs);
				$num_files = mysql_result($query_count_thumbs, 0);

			//Hier der Einstieg in die richtige Suche inkl. Stopwoerter...
			} else {

				if($debug == true) { echo "DEBUG: Volltext-Suche"; }

				$q = strtolower($_GET['q']);

				$where = "((headline LIKE '%@@q@@%') OR (caption LIKE '%@@q@@%') OR (photographer LIKE '%@@q@@%') OR (location LIKE '%@@q@@%') OR (city LIKE '%@@q@@%') OR (keywords LIKE '%@@q@@%'))";

				$stopwords = array();
				$stopwordfile = DOCROOT.INSTALLPATH."/lib/stoppwords_en.txt";

				$handle = fopen ($stopwordfile, "r");
				while (!feof($handle)) {
					$buffer = fgets($handle, 512);
					array_push($stopwords, ltrim(rtrim(strtolower($buffer))));
				}
				fclose ($handle);

				$stopwordfile = DOCROOT.INSTALLPATH."/lib/stoppwords_de.txt";

				$handle = fopen ($stopwordfile, "r");
				while (!feof($handle)) {
					$buffer = fgets($handle, 512);
					array_push($stopwords, ltrim(rtrim(strtolower($buffer))));
				}
				fclose ($handle);

				$condition = "";
				if(preg_match("/\"/",$q)){
					$q = preg_replace("/\"/iu", "", $q);
					$q = mysql_real_escape_string($q);
					$condition = preg_replace("/@@q@@/", $q, $where);
				} else {
					$q_arr = explode(' ', $q);
					$s_arr = array_diff($q_arr, $stopwords);
					for ($i=0;$i<=sizeof($s_arr);$i++) {
						if(!empty($s_arr[$i])){
							$condition .= preg_replace("/@@q@@/", mysql_real_escape_string($s_arr[$i]), $where)." AND ";
						}
					}
					$condition = substr($condition, 0, -4);
				}

				$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, keywords, object_name, photographer, picsize, date FROM picture_data WHERE (".$condition.") ORDER BY date DESC, time DESC LIMIT 0,".$maxfiles."";

				$query_thumbs = mysql_query($stmt_thumbs);

				$stmt_count_thumbs = "Select FOUND_ROWS()";
				$query_count_thumbs = mysql_query($stmt_count_thumbs);
				$num_files = mysql_result($query_count_thumbs, 0);

			}

		}

	}

	//Ausgabestring initialisieren

	$thumbs = "";

	if(isset($_GET['rewrite']) && $_GET['rewrite'] == "true"){

		$query_zips = mysql_query($stmt_thumbs);

		if(isset($_SESSION['accessid']) OR isset($_SESSION['login'])){

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
	if($num_files == 0){
		$thumbs .= "<tr><td colspan=\"5\" height=\"350\">".$TEXT['search-noresults']."</td>\n";
		if(($q != "") AND (!preg_match("/Suche/",$q))) logfile($q,"suchergebnis_null");
	} else {
		$thumbs .= "<tr><td colspan=\"5\">\n";
		$thumbs .= "<div>\n";
		while ($out = mysql_fetch_array($query_thumbs)){

			$thumbs .= "<div class=\"thumbcontainer\">\n";
			$thumbs .= "<div class=\"thumbimg\"><a href=\"/".INSTALLPATH."/detail.php?id=".$out['id']."\" onclick=\"detail('/".INSTALLPATH."/detail.php?id=".$out['id']."');return false;\">\n";
			$thumbs .= "<img src=\"/".INSTALLPATH."/thumbs/".$out['filename']."\" border=\"0\" alt=\"".$out['headline']." (&copy; ".$out['photographer'].")\" /></a></div>\n";
			$thumbs .= "<div class=\"thumbfunc\">".$out['object_name']." (Bild Nr. ".$out['id'].")<br />";

			//Download-Button
			if(isset($_SESSION['login'])){
				$thumbs .= "<a href=\"/".INSTALLPATH."/download.php?id=".$out['id']."\"><img src=\"/".INSTALLPATH."/images/icons/save.gif\" class=\"imgicon\" title=\"Bild herunterladen\"></a>";
			} else {
				$thumbs .= "<a href=\"/".INSTALLPATH."/register.php?KeepThis=true&TB_iframe=true&height=460&width=760\" class=\"thickbox\"><img src=\"/".INSTALLPATH."/images/icons/save.gif\" class=\"imgicon\"></a>";
			}

			//Lightbox-Button
			if(isset($_SESSION['login'])){
				$thumbs .= "<a href=\"/".INSTALLPATH."/addto.php?action=add&id=".$out['id']."\"><img src=\"/".INSTALLPATH."/images/icons/lightbox.gif\" class=\"imgicon\" title=\"Bild in die Lightbox legen\"></a>";
			} else {
				$thumbs .= "<a href=\"/".INSTALLPATH."/register.php?KeepThis=true&TB_iframe=true&height=460&width=760\" class=\"thickbox\"><img src=\"/".INSTALLPATH."/images/icons/lightbox.gif\" class=\"imgicon\"></a>";
			}

			//Email-Button
			if(isset($_SESSION['login'])){
				if($_SESSION['login'] != "Access-ID"){
					$thumbs .= "<a href=\"/".INSTALLPATH."/mailer.php?id=".$out['id']."\"><img src=\"/".INSTALLPATH."/images/icons/sendmail.gif\" class=\"imgicon\" title=\"Bild per Email zusenden\"></a>";
				} else {
					$thumbs .= "<a href=\"/".INSTALLPATH."/mailinfo.php?KeepThis=true&TB_iframe=true&height=460&width=760\" class=\"thickbox\"><img src=\"/".INSTALLPATH."/images/icons/sendmail.gif\" class=\"imgicon\"></a>";
				}
			} else {
				$thumbs .= "<a href=\"/".INSTALLPATH."/register.php?KeepThis=true&TB_iframe=true&height=460&width=760\" class=\"thickbox\"><img src=\"/".INSTALLPATH."/images/icons/sendmail.gif\" class=\"imgicon\"></a>";
			}

			//Detailansicht-Button
			$thumbs .= "<a href=\"/".INSTALLPATH."/detail.php?id=".$out['id']."\" onclick=\"detail('/".INSTALLPATH."/detail.php?id=".$out['id']."');return false;\"><img src=\"/".INSTALLPATH."/images/icons/info.gif\" class=\"imgicon\" title=\"Detailansicht &ouml;ffnen\"></a></div>";
			$thumbs .= "</div>\n";
		}
		$thumbs .= "</div>\n";
		$thumbs .= "</td>";
	}

   	$thumbs .= "</tr>\n";
	echo $thumbs;

?>
