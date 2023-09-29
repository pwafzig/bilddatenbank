<?php

	if(!isset($_GET['q']) && !isset($_GET['rewrite'])){

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

		$stmt_galleries = "SELECT transref FROM picture_data GROUP BY transref";
		$query_galleries = mysqli_query($link, $stmt_galleries);
        $num_files = mysqli_num_rows($query_galleries);

	    //Anfang der Paginierung

	    $pagination = "<tr><td colspan=\"5\"><div class=\"pagination\">";

	    if($page > 0){
	        $pagination .= "<a href=\"/".INSTALLPATH."/?page=".$pre."\" class=\"button\">".$TEXT['page-prev']."</a>";
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
	                $pagination .= "<a href=\"/".INSTALLPATH."/?page=".$i."\" class=\"button\">".$i."</a>";
	        }
	    }

	    if($page < $num_pages-1){
	        $pagination .= "<a href=\"/".INSTALLPATH."/?page=".$nxt."\" class=\"button\">".$TEXT['page-next']."</a>";
	    } else {
	        $pagination .= "<span class=\"inactive\">".$TEXT['page-next']."</span>";
	    }

	    $pagination .= "</div></td></tr>";

   		echo $pagination;

	}
?>