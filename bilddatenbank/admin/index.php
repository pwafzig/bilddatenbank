<?php include("../php/includes/start.inc.php"); ?>
<?php include(DOCROOT."/".INSTALLPATH."/php/includes/doctype.inc.php"); ?>
<html>
<?php include(DOCROOT."/".INSTALLPATH."/php/includes/headsection.inc.php"); ?>
<?php include(DOCROOT."/".INSTALLPATH."/php/includes/styles.inc.php"); ?>
<?php include(DOCROOT."/".INSTALLPATH."/admin/php/includes/adminstyles.inc.php"); ?>
<?php include(DOCROOT."/".INSTALLPATH."/php/includes/scripts.inc.php"); ?>
<?php include(DOCROOT."/".INSTALLPATH."/admin/php/includes/adminscripts.inc.php"); ?>
<body>
<table border="0" cellspacing="20" cellpadding="0" width="100%">
<tr>
	<td colspan="3" valign="top">
		<?php include(DOCROOT."/".INSTALLPATH."/php/includes/logo.inc.php"); ?>
		<?php include(DOCROOT."/".INSTALLPATH."/admin/php/includes/servicenav.inc.php"); ?>
	</td>
</tr>
<tr>
	<td colspan="3">
		<hr class="line"><br />
		<div id="suchbox">
			<?php include(DOCROOT."/".INSTALLPATH."/admin/php/includes/searchbox.inc.php"); ?>
		</div>
	</td>
</tr>
<tr>
	<td valign="top" width="100%">
		<table width="100%" id="adminarea">
			<tr>
				<td valign="top">
					<?php include(DOCROOT."/".INSTALLPATH."/admin/php/includes/adminarea.inc.php"); ?>
				</td>
			</tr>
		</table>
	</td>
	<td width="2" class="vertline">
		<div id="trenner"></div>
	</td>
	<td width="220px" valign="top">
		<div id="adminbox" class="box">
			<?php include(DOCROOT."/".INSTALLPATH."/admin/php/includes/adminbox.inc.php"); ?>
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
</body>
</html>