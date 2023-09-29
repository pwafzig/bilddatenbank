<h2><?php echo $TEXT['neue-title']?>:</h2>
<?php

		$stmt_newfiles = "SELECT t1.object_name, t1.transref, t1.date FROM picture_data t1 INNER JOIN ( SELECT MIN(id) AS min_id FROM picture_data GROUP BY transref ) t2 ON t1.id = t2.min_id ORDER BY t2.min_id ASC LIMIT 0,".$CONFIG['anznewfiles'];



		$query_newfiles = mysqli_query($link, $stmt_newfiles);

		$newfiles = "";

		while ($out_newfiles = mysqli_fetch_array($query_newfiles, MYSQLI_ASSOC)){

			$year = substr($out_newfiles['date'],0,4);
			$month = substr($out_newfiles['date'],4,2);
			$day = substr($out_newfiles['date'],6,2);

			$object_name = rawurlencode($out_newfiles['object_name']);

			$newfiles .= "<hr><strong>$day.$month.$year:</strong><br /><a href=\"/".INSTALLPATH."/".$out_newfiles['transref']."\" class=\"textlink\">$out_newfiles[object_name]</a><br />";
		}

	echo $newfiles;
?>
