<div id="lightboxreq"></div>
<script type="text/javascript" language="javascript">
	function alertInhalt() {
		if (http_request.readyState == 4) {
			document.getElementById("lightboxreq").innerHTML = http_request.responseText;
		}
	}
<?php if(isset($_SESSION['accessid']) OR isset($_SESSION['login'])){ ?>
	iv = setInterval("macheRequest('/<?php echo INSTALLPATH; ?>/php/includes/lightboxloader.inc.php')",1000);
<?php } else { ?>
	macheRequest('/<?php echo INSTALLPATH; ?>/php/includes/lightboxloader.inc.php');
<?php } ?>
</script>