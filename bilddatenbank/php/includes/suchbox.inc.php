<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="searchform">
<ul class="form">
	<li><input type="text" value="<?php echo $q?>" onfocus="if (this.value == '<?php echo $TEXT['search-for']?>') this.value = ''" name="q" id="q" /></li>
	<li><input type="submit" id="searchsubmit" value="<?php echo $TEXT['search-label']?>" /></li>
</ul>
</form>
