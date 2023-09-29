<h2><?php echo $TEXT['neue-title']?>:</h2>
<?php

		$stmt_newfiles = "SELECT DISTINCT transref, object_name, date FROM `picture_data` GROUP BY transref ORDER BY date DESC, time DESC LIMIT 0,".$CONFIG['anznewfiles'];
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
