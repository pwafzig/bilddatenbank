<?php include("../../php/includes/start.inc.php"); ?>
<?php

	$f = fopen(DOCROOT.INSTALLPATH."/admin/backup/".date('YmdHis')."_backup.sql", "w");

	$tables = mysql_list_tables(DB_NAME);

	while ($cells = mysql_fetch_array($tables)) {

		$table = $cells[0];
		fwrite($f,"DROP TABLE `$table`;\n");
		$res = mysql_query("SHOW CREATE TABLE `$table`");
		if ($res) {
		$create = mysql_fetch_array($res);
		$create[1] .= ";";
		$line = str_replace("\n", "", $create[1]);
		fwrite($f, $line."\n");
		$data = mysql_query("SELECT * FROM `$table`");
		$num = mysql_num_fields($data);
		while ($row = mysql_fetch_array($data)){
		$line = "INSERT INTO `$table` VALUES(";
		for ($i=1;$i<=$num;$i++) {
		$line .= "'".mysql_real_escape_string($row[$i-1])."', ";
		}
		$line = substr($line,0,-2);
		fwrite($f, $line.");\n");
		}
		}
	}

	fclose($f);

	header("Location:http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/admin/index.php?sect=backup");

?>