<head>
	<?php
		$title = $TEXT['meta-title'];

		if($CONFIG['robots'] == "yes") {
			$robots = "INDEX, FOLLOW";
		} else {
			$robots = "NOINDEX, NOFOLLOW";
		}
	?>
<title><?php echo $title?></title>
	<META http-equiv="Content-Type" content="text/html; charset=utf-8">
	<META http-equiv="imagetoolbar" content="no">
	<META name="author" content="<?php echo $TEXT['meta-inhaber']?>">
	<META name="company" content="<?php echo $TEXT['meta-firma']?>">
	<META name="robots" content="<?php echo $robots?>">
	<META name="language" content="<?php echo $lang?>">
	<META name="keywords" content="<?php echo $TEXT['meta-keywords']?>">
	<LINK rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://<?php echo $_SERVER['HTTP_HOST']."/".INSTALLPATH ?>/rss.php" />
