<?php

	if(!isset($_GET['q']) && !isset($_GET['date'])){

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

	    //Definitionen fuer Paginierung
	    $pre = $page;
	    $nxt = $page+2;

		if(preg_match("/uebersicht/", $_SERVER['PHP_SELF'])){
			$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, photographer, picsize FROM picture_data GROUP BY object_name ORDER BY date DESC, time DESC LIMIT ".$start.",".$anzthumbs."";
		} else {
			$stmt_thumbs = "SELECT SQL_CALC_FOUND_ROWS id, filename, headline, photographer, picsize FROM picture_data ORDER BY date DESC, time DESC LIMIT ".$start.",".$anzthumbs."";
		}

		$query_thumbs = mysql_query($stmt_thumbs);

        $stmt_count_thumbs = "Select FOUND_ROWS()";
        $query_count_thumbs = mysql_query($stmt_count_thumbs);
        $num_files = mysql_result($query_count_thumbs, 0);

	    if(isset($_GET['q'])){
	    	$q = $_GET['q'];
			preg_replace('/\+/iu',' ',$q);
			$q = htmlentities(utf8_decode($q));
	        $q_string = "&q=".$q;
	    } else {
	        $q_string = "";
	    }

	    //Anfang der Paginierung

	    $pagination = "<tr><td colspan=\"5\"><div class=\"pagination\">";

	    if($page > 0){
	        $pagination .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".$pre.$q_string."\" class=\"button\">".$TEXT['page-prev']."</a>";
	    } else {
	        $pagination .= "<span class=\"inactive\">".$TEXT['page-prev']."</span>";
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
	                $pagination .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".$i.$q_string."\" class=\"button\">".$i."</a>";
	        }
	    }

	    if($page < $num_pages-1){
	        $pagination .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".$nxt.$q_string."\" class=\"button\">".$TEXT['page-next']."</a>";
	    } else {
	        $pagination .= "<span class=\"inactive\">".$TEXT['page-next']."</span>";
	    }

	    $pagination .= "</div></td></tr>";

   		echo $pagination;

	}
?>
