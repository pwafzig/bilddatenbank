<?php
    if(!isset($_SESSION['adminlogin'])){
   	   	exit;
   	}
?>
<h1>Übersicht</h1><br />
<?php
	//Statistiken ausgeben
	$stmt = mysqli_query($link, "SELECT id FROM picture_data");
	$num = mysqli_num_rows($stmt);

	echo "<p>Die Datenbank enthält derzeit <strong>$num Dateien</strong>.";

?>
<br /><br />

<div id="suchlog" style="float:left; margin-right: 50px;padding:20px;background-color:#efefef;width:600px">
<h3>Suchergebnisse ohne Treffer:</h3><br />
<p><?php

	$suchlogs = array();

	$logfile = DOCROOT."/".INSTALLPATH."/logs/suchergebnis_null.log";
	if (!file_exists($logfile))
		touch($logfile);

	$handle = fopen ($logfile, "r");

	while (!feof($handle)) {
		$buffer = fgets($handle, 512);
		array_push($suchlogs, ltrim(rtrim(strtolower($buffer))));
	}

	fclose ($handle);

	$suchlogs = array_reverse($suchlogs);

	foreach($suchlogs as $logentry) {
		if($logentry != "") {
			echo $logentry."<br />";
		}
	}

?></p><br /><br />
<a href="/<?php echo INSTALLPATH; ?>/admin/bin/killlog.php" style="cursor:pointer" class="button">&nbsp;Logfile löschen&nbsp;</a>
</div>