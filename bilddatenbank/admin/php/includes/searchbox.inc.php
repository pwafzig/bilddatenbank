<?php
	if(isset($_GET['q'])){
		$q = $_GET['q'];
		preg_replace('/\+/iu',' ',$q);
		$q = htmlentities(utf8_decode($q));
	} else {
		$q = $TEXT['search-for'];
	}
?>
<form action="/<?php echo INSTALLPATH; ?>/admin/index.php?sect=bildverwaltung" method="post" id="searchform">
<ul class="form">
	<li><label for="q"><?php echo $TEXT['search-for']?></label><input type="text" value="<?php echo $q?>" onfocus="if (this.value == '<?php echo $TEXT['search-for']?>') this.value = ''" name="q" id="q" /></li>
	<li><input type="submit" id="searchsubmit" value="<?php echo $TEXT['search-label']?>" /></li>
</ul>
</form>