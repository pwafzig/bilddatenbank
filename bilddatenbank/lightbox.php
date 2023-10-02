<?php include("php/includes/start.inc.php"); ?>
<?php include(DOCROOT."/".INSTALLPATH."/php/includes/doctype.inc.php"); ?>
<?php include(DOCROOT."/".INSTALLPATH."/php/includes/headsection.inc.php"); ?>
<?php include(DOCROOT."/".INSTALLPATH."/php/includes/styles.inc.php"); ?>
<?php include(DOCROOT."/".INSTALLPATH."/php/includes/scripts.inc.php"); ?>
<body>
<table border="0" cellspacing="20" cellpadding="0" width="100%">
<tr>
	<td colspan="3" valign="top">
		<?php include(DOCROOT."/".INSTALLPATH."/php/includes/logo.inc.php"); ?>
		<?php include(DOCROOT."/".INSTALLPATH."/php/includes/servicenav.inc.php"); ?>
	</td>
</tr>
<tr>
	<td colspan="3">
		<hr class="line"><br />
		<div id="suchbox">
			<?php include(DOCROOT."/".INSTALLPATH."/php/includes/searchbox.inc.php"); ?>
		</div>
	</td>
</tr>
<tr>
	<form action="zipdownload.php" method="POST">
	<td valign="top" width="100%" style="min-width: 41em;">
	<table border="0" cellspacing="0" cellpadding="0" id="lightboxtable" width="100%">
	<thead><tr><th><?php echo $TEXT['def-bild']?> <?php echo $TEXT['def-nummer']?></th><th><?php echo $TEXT['def-vorschau']?></th><th><?php echo $TEXT['def-title']?></th><th><?php echo $TEXT['def-download']?></th><th><?php echo $TEXT['def-loeschen']?></th><th><?php echo $TEXT['def-auswahl']?></th></tr></thead>
	<tbody>

	<?php
		$html = "";
		$or = "";
		$ids = explode("|",$_SESSION['lightboxids']);
		$anz_ids = sizeof($ids)-1;
		for($i=0;$i<$anz_ids;$i++){
			$or .= "id = '".$ids[$i]."' OR ";
		}
		$stmt = "SELECT * FROM picture_data WHERE ".$or." id = '99999999'";
		$query = mysqli_query($link, $stmt);
		while($out = mysqli_fetch_array($query, MYSQLI_ASSOC)){
			$html .= "<tr><td align=\"center\">".$out['id']."</td><td align=\"center\"><a href=\"#\" onclick=\"detail('/".INSTALLPATH."/detail.php?id=".$out['id']."');return false;\"><img src=\"/".INSTALLPATH."/thumbs/".$out['filename']."\" width=\"33\" height=\"22\" alt=\"\" title=\"\" border\"0\"></a></td><td>".$out['headline']."</td><td align=\"center\"><a href=\"/".INSTALLPATH."/download.php?id=".$out['id']."\" alt=\"\" title=\"".$TEXT['lightbox-herunterladen']."\">".$out['filename']."</a></td><td align=\"center\"><a href=\"/".INSTALLPATH."/addto.php?action=delete&id=".$out['id']."\" id=\"delete\" style=\"text-decoration:none;text-align:center;\">&nbsp;</a></td><td align=\"center\"><input type=\"checkbox\" name=\"zip[]\" value=\"".$out['filename']."\"></td></tr>\n";
		}
		echo $html;
	?>
	<tr><td colspan="6"><input type="submit" value="Alle ausgew&auml;hlten Dateien herunterladen"></td></tr>
	</form>
	</tbody>
	</table>

	</td>
	<td width="2" class="vertline">
		<div id="trenner"></div>
	</td>
	<td width="220px" valign="top">
		<div id="loginbox" class="box">
			<?php include(DOCROOT."/".INSTALLPATH."/php/includes/loginbox.inc.php"); ?>
		</div>

		<div id="newfilesbox" class="box">
			<?php include(DOCROOT."/".INSTALLPATH."/php/includes/newfiles.inc.php"); ?>
		</div>
	</td>
</tr>
<tr>
	<td colspan="3" height="22px" valign="top">
		<hr class="line">
		<div id="footer">
			<?php include(DOCROOT."/".INSTALLPATH."/php/includes/footer.inc.php"); ?>
		</div>

	</td>
</tr>
</table>
<?php include(DOCROOT."/".INSTALLPATH."/php/includes/analytics.inc.php"); ?>
</body>
</html>