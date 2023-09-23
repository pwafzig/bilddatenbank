<?php include("start.inc.php"); ?>
<?php
    if(!isset($_SESSION['lang'])){
        $_SESSION['lang'] = "de";
        $lang = "de";
    } else {
        $lang = $_SESSION['lang'];
    }

	include(DOCROOT.INSTALLPATH."/php/lang/$lang.inc.php");
?>
<h2><?php echo $TEXT['lightbox-title']?>:</h2>
<?php
	if(isset($_SESSION['lightboxids'])){
		$ids = explode("|",$_SESSION['lightboxids']);
		$anz_ids = sizeof($ids)-1;
		echo "<a href=\"/".INSTALLPATH."/lightbox.php\" class=\"textlink\">".$TEXT['lightbox-pre']." <strong style=\"color:red;font-size:large\">".$anz_ids."</strong> ".$TEXT['lightbox-sub']."</a>";
	} else {
		echo $TEXT['lightbox-pre']." <strong>0</strong> ".$TEXT['lightbox-sub'];
	}
?>