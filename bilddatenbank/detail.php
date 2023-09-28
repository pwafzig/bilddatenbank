<?php include("php/includes/start.inc.php"); ?>
<?php include(DOCROOT.INSTALLPATH."/php/includes/doctype.inc.php"); ?>
<html>
<?php
	mysqli_query($link, "SET CHARACTER SET 'utf8'");

	if(isset($_GET['id'])){
		if(preg_match("/[0-9]/",$_GET['id'])){
			$id = mysqli_real_escape_string($link, $_GET['id']);
		} else {
			exit;
		}
	}

	$stmt_popup = "SELECT * FROM picture_data WHERE id = '$id'";
	$query_popup = mysqli_query($link, $stmt_popup);
	$result_popup = mysqli_fetch_array($query_popup, MYSQLI_ASSOC);

	if(mysqli_num_rows($query_popup) == 0){
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
		header("Status: 404 Not Found");
		exit;
	}

	//Keywords formatieren
	$keywords = explode(", ", $result_popup['keywords']);
	$keywords_html = "";

	for($i=0;$i<count($keywords);$i++) {
	    $keywords_html .= "<a href=\"/".INSTALLPATH."/?key=".$keywords[$i]."\" onclick=\"javascript:window.opener.parent.location.href = '/".INSTALLPATH."/?key=".$keywords[$i]."';self.close()\" class=\"textlink\">".$keywords[$i]."</a>, ";
	}

	//Dateigroesse ausrechnen
	$pxsize = explode("x", preg_replace("/px/", "", $result_popup['picsize']));
	$xsize = $pxsize[0];
	$ysize = $pxsize[1];

	$mbsize = number_format(($xsize*$ysize*3)/1024000, 2, '.', '');

	//Printgroesse ausrechnen
	$printxsize = ceil(($xsize/150)*2.54);
	$printysize = ceil(($ysize/150)*2.54);

	$stmt_prev = "SELECT id, date, time, transref FROM picture_data WHERE ((transref = '".$result_popup['transref']."') AND (id < '".$result_popup['id']."')) ORDER BY id DESC LIMIT 1";
	$query_prev = mysqli_query($link, $stmt_prev);
	$result_prev = mysqli_fetch_array($query_prev, MYSQLI_ASSOC);

	$stmt_next = "SELECT id, date, time, transref FROM picture_data WHERE ((transref = '".$result_popup['transref']."') AND (id > '".$result_popup['id']."')) ORDER BY id ASC LIMIT 1";
	$query_next = mysqli_query($link, $stmt_next);
	$result_next = mysqli_fetch_array($query_next, MYSQLI_ASSOC);

	//Rueckmeldung des Mailers umsetzen
	if(isset($_GET['mail_ok'])){
		$mail_ok = "&nbsp;(gesendet...)";
	}

?>
<?php include(DOCROOT.INSTALLPATH."/php/includes/headsection.inc.php"); ?>
<?php include(DOCROOT.INSTALLPATH."/php/includes/styles.inc.php"); ?>
<?php include(DOCROOT.INSTALLPATH."/php/includes/scripts.inc.php"); ?>
<body>
<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
	<tr>
		<td colspan="2">
			<div id="det_header">
				<div id="det_logo">
					<a href="/<?php echo INSTALLPATH; ?>" onclick="javascript:window.opener.parent.location.href = '/<?php echo INSTALLPATH; ?>/index.php';self.close()"><img src="/<?php echo INSTALLPATH; ?>/images/logo.gif" border="0" alt="<?php echo $TEXT['meta-firma']?> Logo" width="153" /></a>
				</div>

				<div id="det_navigate">
					<?php if(isset($result_prev['id']) != ""){ ?>
						<button class="btlft" onclick="location.href='detail.php?id=<?php echo $result_prev['id']?>'"><?php echo $TEXT['detail-previmage']?></button>
					<?php } else { ?>
						<button class="btlft inact"><?php echo $TEXT['detail-previmage']?></button>
					<?php } ?>

					<a href="/<?php echo INSTALLPATH; ?>/<?php echo $slug; ?>" style="color:#FFF"><button class="btcenter" onclick="javascript:window.opener.parent.location.href = '/<?php echo INSTALLPATH; ?>/<?php echo $result_popup['transref']; ?>'; self.close()"><?php echo $TEXT['detail-homelink']?></button></a>
					<?php if(isset($result_next['id']) != ""){ ?>
						<button class="btrgt" onclick="location.href='detail.php?id=<?php echo $result_next['id']?>'"><?php echo $TEXT['detail-nextimage']?></button>
					<?php } else { ?>
						<button class="btrgt inact"><?php echo $TEXT['detail-nextimage']?></button>
					<?php } ?>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2"><hr class="line plusmargin"></td>
	</tr>
	<tr>
		<td width="540">
			<div id="print-headline">
			<h1><?php echo $result_popup['headline'];?></h1>
			</div>

			<div id="det_preview">
				<img src="/<?php echo INSTALLPATH; ?>/previews/<?php echo $result_popup['filename'];?>" alt="<?php echo $result_popup['headline'];?> (&copy; <?php echo $result_popup['photographer'];?>)" title="<?php echo $result_popup['headline'];?> (&copy; <?php echo $result_popup['photographer'];?>)" />
			</div>

			<div id="det_details">
				<table border="0" cellspacing="0" cellpadding="0" width="500" class="det_description">
					<tr>
						<td colspan="2" valign="top"><strong><?php echo $result_popup['headline'];?></strong></td>
					</tr>

					<tr>
						<td valign="top"><?php echo $TEXT['detail-desc-description']?></td>
						<td valign="top"><?php echo nl2br($result_popup['caption']);?></td>
					</tr>

					<tr>
						<td valign="top"><?php echo $TEXT['detail-desc-keywords']?></td>
						<td valign="top"><?php echo $keywords_html;?></td>
					</tr>

					<tr>
						<td valign="top"><?php echo $TEXT['detail-desc-filesize']?></td>
						<td valign="top"><?php echo $result_popup['picsize'];?> (<?php echo $printxsize; ?>x<?php echo $printysize; ?>cm/150dpi), <?php echo $mbsize; ?> MB (JPG)</td>
					</tr>

					<tr>
						<td valign="top"><?php echo $TEXT['detail-desc-photog']?></td>
						<td valign="top"><?php echo $result_popup['photographer'];?></td>
					</tr>

					<tr>
						<td valign="top"><?php echo $TEXT['detail-desc-rights']?></td>
						<td valign="top">&copy; <?php echo $result_popup['copyright'];?></td>
					</tr>

					<tr>
						<td valign="top"><?php echo $TEXT['detail-desc-specinst']?></td>
						<td valign="top"><?php echo $result_popup['special_instructions'];?></td>
					</tr>
				</table>
			</div>

			<div id="det_contact">
				<table border="0" cellspacing="0" cellpadding="0" width="500" class="det_description">
					<tr>
						<td colspan="2" valign="top"><?php echo $TEXT['detail-contact']?></td>
					</tr>
				</table>
			</div>

		</td>
		<td valign="top">
			<div id="det_functions">
				<h3><?php echo $TEXT['detail-funktionen']?></h3>
				<ul id="functions_list">
				<?php
					if(isset($_SESSION['login'])){
						if(is_file(DOCROOT.INSTALLPATH."/data/".$result_popup['filename'])){
							if(isset($_SESSION['lightboxids'])){
								$ids = explode("|",$_SESSION['lightboxids']);
								$anz_ids = sizeof($ids)-1;
							}
				?>
					<li><span class="ic-save">&nbsp;</span><a href="/<?php echo INSTALLPATH; ?>/download.php?id=<?php echo $result_popup['id']?>" class="textlink"><?php echo $TEXT['detail-download']?></a></li>
					<li><span class="ic-print">&nbsp;</span><a href="javascript:window.print();" class="textlink"><?php echo $TEXT['detail-print']?></a></li>

					<?php if($_SESSION['login'] != "newsletter") { ?>
						<?php if($_SESSION['email'] != "") { ?>
						
							<?php 
								if(isset($_GET['mail']) && $_GET['mail'] == "sent"){
									$mail_ok = "<span style=\"color:red\"><strong>&nbsp;OK</strong></span>"; 
								} else {
									$mail_ok = "";
								}
							?>
						
							<li><span class="ic-mail">&nbsp;</span><a href="/<?php echo INSTALLPATH; ?>/mailer.php?id=<?php echo $result_popup['id']?>" class="textlink"><?php echo $TEXT['detail-emailsend']?></a><?php echo $mail_ok; ?></li>
						<?php } ?>
					<?php } ?>

					<?php if(!preg_match("/$id/",$_SESSION['lightboxids'])) { ?>
						<li><span class="ic-lightbox">&nbsp;</span><a href="/<?php echo INSTALLPATH; ?>/addto.php?action=add&id=<?php echo $result_popup['id']?>" class="textlink"><?php echo $TEXT['detail-addlightbox']?></a> <span class="notice"><a href="#" onclick="javascript:window.opener.parent.location.href = '/<?php echo INSTALLPATH; ?>/lightbox.php';self.close()" style="color:#FFFFFF"><?php echo $anz_ids?></span></a></li>
					<?php } else { ?>
						<li><span class="ic-lightbox">&nbsp;</span><a href="/<?php echo INSTALLPATH; ?>/addto.php?action=delete&id=<?php echo $result_popup['id']?>" class="textlink"><?php echo $TEXT['detail-dellightbox']?></a> <span class="notice"><a href="#" onclick="javascript:window.opener.parent.location.href = '/<?php echo INSTALLPATH; ?>/lightbox.php';self.close()" style="color:#FFFFFF"><?php echo $anz_ids?></a></span></li>
					<?php } ?>

				<?php } else { ?>
					<li><span class="ic-mail">&nbsp;</span><<strong><?php echo $TEXT['def-fehler']?></strong> <a href="mailto:<?php $CONFIG['email']; ?>" class="textlink"><?php echo $CONFIG['email']?></a></li>
				<?php }
					} else {
				?>
					<li><span class="ic-index">&nbsp;</span><a href="/<?php echo INSTALLPATH; ?>/<?php echo $result_popup['transref']; ?>" onclick="javascript:window.opener.parent.location.href = '/<?php echo INSTALLPATH; ?>/<?php echo $result_popup['transref']; ?>';self.close()" class="textlink"><?php echo $TEXT['detail-index']?> <?php echo $result_popup['object_name'];?></a></li>
					<li><span class="ic-mail">&nbsp;</span><a href="mailto:<?php echo $CONFIG['email']?>?subject=Bildanfrage <?php echo $result_popup['object_name'];?> (Bild Nr. <?php echo $result_popup['id'];?>)" class="textlink"><?php echo $TEXT['detail-bildanfrage']?></a></li>
					<li><span class="ic-info">&nbsp;</span><?php echo $TEXT['detail-allefunktionen']?></li>
				<?php } ?>
				</ul>
				<br /><hr class="line">
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2"><hr class="line plusmargin"></td>
	</tr>
	<tr>
		<td colspan="2">
			<div id="det_footer">&copy; <?php echo date("Y");?> / <?php echo $TEXT['meta-firma']?>&nbsp;|&nbsp;
			<a href="javascript:window.print();" title="<?php echo $TEXT['nav-print']?>" rel="nofollow"><?php echo $TEXT['nav-print']?></a>
			</div>
			<div id="print-footer">
			<br /><br /><br />&copy; <?php echo date("Y");?> / <?php echo $TEXT['meta-firma']?>.<br />
			<?php echo $TEXT['nav-printurl']?> http://<?php echo $_SERVER['HTTP_HOST']?><?php echo $_SERVER['REQUEST_URI']?>
		</td>
	</tr>
</table>

<?php include(DOCROOT.INSTALLPATH."/php/includes/analytics.inc.php"); ?>
</body>
</html>
