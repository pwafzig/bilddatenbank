<div id="homebutton"><a href="/<?php echo INSTALLPATH; ?>"><img src="/<?php echo INSTALLPATH; ?>/images/homebutton.gif" title="<?php echo $TEXT['detail-overview']; ?>" border="0"></a></div>
<?php

	if(isset($_GET['q'])){
		$q = secure_input($_GET['q']);
	} else {
		$q = "";
	}

?>
<div id="searchbox"><form action="/<?php echo INSTALLPATH; ?>" method="get" id="searchform">
<ul class="form">
	<li><label for="q">Search</label><input type="text" value="<?php echo $q; ?>" name="q" id="q" /></li>
	<li><input type="submit" id="searchsubmit" value="<?php echo $TEXT['search-label']?>" /></li>
</ul>
</form></div>