<?php include("../../php/includes/start.inc.php"); ?>
<html>
<?php include(DOCROOT.INSTALLPATH."/php/includes/styles.inc.php"); ?>
<?php include(DOCROOT.INSTALLPATH."/admin/php/includes/adminstyles.inc.php"); ?>
<?php include(DOCROOT.INSTALLPATH."/php/includes/scripts.inc.php"); ?>
<?php include(DOCROOT.INSTALLPATH."/admin/php/includes/adminscripts.inc.php"); ?>
<body style="margin:0px;width:550px;text-align:center;">
<?php
	$stmt = "SELECT filename FROM picture_data WHERE id = '".$_GET['id']."'";
	$query = mysql_query($stmt);
	$out = mysql_fetch_row($query);
?>
<img src="/<?php echo INSTALLPATH; ?>/previews/<?php echo $out[0];?>" border="0">
</body>
</html>