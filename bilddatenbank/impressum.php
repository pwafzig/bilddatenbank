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
	<td valign="top" width="100%" style="min-width: 41em;">
		<table width="100%">
			<tr>
				<td colspan="5" align="left">
				<h3><?php echo $TEXT['nav-impressum']; ?></h3><br />
				<p><?php echo nl2br($CONFIG['impressum_text']); ?></p>
				</td>
			</tr>
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

		<div id="lightbox" class="box">
			<?php include(DOCROOT."/".INSTALLPATH."/php/includes/lightbox.inc.php"); ?>
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