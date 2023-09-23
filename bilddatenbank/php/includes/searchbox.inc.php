<div id="homebutton"><a href="/<?php echo INSTALLPATH; ?>/"><img src="/<?php echo INSTALLPATH; ?>/images/homebutton.gif" title="<?php echo $TEXT['detail-overview']; ?>" border="0"></a></div>
<?php
	$rewrite = "";
	if(isset($_GET['rewrite'])){
		$rewrite = $_GET['rewrite'];
	}

	if((isset($_GET['q']) && ($rewrite != "true"))){
		$q = $_GET['q'];
		$q = preg_replace('/\+/iu',' ',$q);
		$q = htmlentities(utf8_decode($q));
	} else {
		$q = $TEXT['search-for'];
	}

?>
<div id="searchbox"><form action="http://<?php echo $_SERVER['HTTP_HOST']."/".INSTALLPATH; ?>" method="get" id="searchform">
<ul class="form">
	<li><label for="q">Search</label><input type="text" value="<?php echo $q?>" onfocus="if (this.value == '<?php echo $TEXT['search-for']?>') this.value = ''" name="q" id="q" /></li>
	<li><input type="submit" id="searchsubmit" value="<?php echo $TEXT['search-label']?>" /></li>
</ul>
</form></div>