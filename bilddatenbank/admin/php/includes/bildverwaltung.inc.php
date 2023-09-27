<?php
    if(!isset($_SESSION['adminlogin'])){
   	   	exit;
   	}
   	
?>
<script language="JavaScript">
// Markierungen umkehren
function MarkierungUmkehren (){
	for(var i = 0;i < document.forms['bildverwaltung'].elements.length;i++) {
		if (document.forms['bildverwaltung'].elements[i].type == "checkbox"){
			if (document.forms['bildverwaltung'].elements[i].checked == true){
				document.forms['bildverwaltung'].elements[i].checked = false;
			} else {
				document.forms['bildverwaltung'].elements[i].checked = true;
			}
		}
	}
}
// Alles Markieren
function AllesMarkieren(){
	for(i=0;i<document.forms['bildverwaltung'].elements.length;++i) {
		if (document.forms['bildverwaltung'].elements[i].type == "checkbox"){
			document.forms['bildverwaltung'].elements[i].checked = true;
		}
	}
}
// Alles Markierungen entfernen
function NixMarkieren(){
	for(i=0;i<document.forms['bildverwaltung'].elements.length;++i) {
		if (document.forms['bildverwaltung'].elements[i].type == "checkbox"){
			document.forms['bildverwaltung'].elements[i].checked = false;
		}
	}
}
</script>
<?php

    mysqli_query($link, "SET CHARACTER SET 'utf8'");

    //Seitenzahl festlegen bzw. am Anfang nullen
    if(!isset($_GET['page'])){
            $page = 0;
    } else {
            $page = $_GET['page']-1;
    }

    //Anzahl der Bilder pro Seite
    $anzthumbs = 12;

    //Startcounter für MySQL-Statement festlegen
    $start = $page*$anzthumbs;

    //Abfrage, ob hier gerade eine Suche läuft
    if(!isset($_POST['q'])){

            $stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, photographer, picsize, timestamp FROM picture_data ORDER BY filename DESC LIMIT ".$start.",".$anzthumbs."";
            $query_thumbs = mysqli_query($link, $stmt_thumbs);

            $stmt_count_thumbs = "Select FOUND_ROWS()";
            $query_count_thumbs = mysqli_query($link, $stmt_count_thumbs);
            $num_files = mysqli_num_rows($query_count_thumbs);

    //Wenn ja, dann Suchstatement zusammenbauen
    } else {
            $stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, photographer, picsize, timestamp FROM picture_data WHERE MATCH (filename, caption, headline, photographer, city, state, country, keywords, location) AGAINST ('*".$_POST['q']."*' IN BOOLEAN MODE) ORDER BY filename DESC LIMIT ".$start.",".$anzthumbs."";
            $query_thumbs = mysqli_query($link, $stmt_thumbs);

            $stmt_count_thumbs = "Select FOUND_ROWS()";
            $query_count_thumbs = mysqli_query($link, $stmt_count_thumbs);
            $num_files = mysqli_num_rows($query_count_thumbs);

    }

	$html = "<table id=\"admintable\" border=\"1\" width=\"100%\">\n";
	$html .= "<form action=\"/".INSTALLPATH."/admin/bin/killfiles.php\" method=\"post\" name=\"bildverwaltung\" id=\"bildverwaltung\">";
	$html .= "<thead><tr><th>Bild ID</th><th>Vorschau</th><th>Bildtitel</th><th>Dateiname</th><th>Datum</th><th>Bildgröße</th><th>Löschen?</th></tr></thead>";

    //Produktion der Thumbnails
    if($num_files == 0){
        $html .= "<tr><td colspan=\"5\" height=\"350\">".$no_search_results."</td></tr>";
    } else {
        while ($out = mysqli_fetch_array($query_thumbs, MYSQLI_ASSOC)){
        	$html .= "<tr><td align=\"center\">".$out['id']."</td>";
        	$html .= "<td align=\"center\"><a href=\"/".INSTALLPATH."/admin/bin/showimgdetails.php?id=".$out['id']."&width=170\" class=\"jTip\" id=\"".$out['id']."\" name=\"Vorschau:\"><img src=\"/".INSTALLPATH."/thumbs/".$out['filename']."\" border=\"0\" width=\"33\" height=\"22\"/></a></td>";
        	$html .= "<td>".$out['headline']."</td>";
        	$html .= "<td><a href=\"/".INSTALLPATH."/download.php?id=".$out['id']."\">".$out['filename']."</a></td>";
        	$html .= "<td>".$out['timestamp']."</td>";
        	$html .= "<td align=\"center\">".$out['picsize']."</td>";
        	$html .= "<td align=\"center\"><input type=\"checkbox\" name=\"killpath[]\" value=\"".$out['filename']."\"></td>";
        }
    }
    $html .= "<tr><td colspan=\"7\"><input type=\"submit\" value=\"Bilder endg&uuml;ltig löschen\"><div style=\"float:right\"><a onclick=\"AllesMarkieren()\" href=\"#\">Alle ausw&auml;hlen</a> | <a href=\"javascript:NixMarkieren()\">Keine</a> | <a href=\"javascript:MarkierungUmkehren()\">Markierung Umkehren</a></div></td></tr>";
    $html .= "</table>";
    $html .= "</form>";

    //Definitionen für Paginierung
    $pre = $page;
    $nxt = $page+2;

    if(isset($_GET['q'])){
    	$q = $_GET['q'];
		preg_replace('/\+/iu',' ',$q);
		$q = htmlentities(utf8_decode($q));
        $q_string = "&q=".$q;
    } else {
        $q_string = "";
    }

    //Anfang der Paginierung
    if($page > 0){
        $pagination = "<a href=\"".$_SERVER['PHP_SELF']."?sect=bildverwaltung&page=".$pre.$q_string."\" class=\"button\">".$TEXT['page-prev']."</a>";
    } else {
        $pagination = "<span class=\"inactive\">".$TEXT['page-prev']."</span>";
    }

    $num_pages = ceil($num_files / $anzthumbs);

    $min = $page-5;
    if($min <=1){
    	$min = 1;
    	}

    $max = $page+6;
    if($max >= $num_pages){
    	$max = $num_pages;
    	}


    for($i=$min;$i<=$max;$i++){
        if($i == $page+1){
                $pagination .= "<span class=\"inactive\">".$i."</span>";
        } else {
                $pagination .= "<a href=\"".$_SERVER['PHP_SELF']."?sect=bildverwaltung&page=".$i.$q_string."\" class=\"button\">".$i."</a>";
        }
    }

    if($page < $num_pages-1){
        $pagination .= "<a href=\"".$_SERVER['PHP_SELF']."?sect=bildverwaltung&page=".$nxt.$q_string."\" class=\"button\">".$TEXT['page-next']."</a>";
    } else {
        $pagination .= "<span class=\"inactive\">".$TEXT['page-next']."</span>";
    }
    
	echo "<div class=\"pagination\">".$pagination."</div><br /><br />";
	echo $html;

?>
