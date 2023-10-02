<?php if (isset($_SESSION['login'])) { ?>
<h2><?php echo $TEXT['login-loginmsg']?></h2>
<span class="logintext">
<strong><?php echo $_SESSION['prename']?> <?php echo $_SESSION['name']?></strong></span><br /><br />
<?php if($_SESSION['login'] == "probezugang"){ ?>
<br />
<?php } ?>
<a href="/<?php echo INSTALLPATH; ?>/logout.php" rel="nofollow" class="button"><?php echo $TEXT['login-logoutmsg']?></a><br /><br />
<?php } else {
	if($_SERVER['QUERY_STRING'] != ""){
		$refer = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
	} else {
		$refer = $_SERVER['PHP_SELF'];
	}
?>
<h2><?php echo $TEXT['login-logintitle']?></h2>
<form action="/<?php echo INSTALLPATH; ?>/login.php" method="post">
	<input name="login" type="text" class="textfeld" value="<?php echo $TEXT['def-benutzername']?>" onfocus="if (this.value == '<?php echo $TEXT['def-benutzername']?>') this.value = ''">&nbsp;
	<input name="passwort" type="password" class="textfeld" value="<?php echo $TEXT['def-passwort']?>" onfocus="if (this.value == '<?php echo $TEXT['def-passwort']?>') this.value = ''">
	<input name="refer" type="hidden" value="<?php echo $refer?>">
	<input name="absenden" type="submit" value="<?php echo $TEXT['login-label']?>" class="button">
</form>
<br />
<a href="/<?php echo INSTALLPATH; ?>/register.php?KeepThis=true&TB_iframe=true&height=480&width=750" class="thickbox textlink" rel="nofollow"><?php echo $TEXT['login-register']?></a> / <a href="mailto:<?php echo $CONFIG['email']; ?>?subject=<?php echo $TEXT['login-lostpasswort']?>" rel="nofollow" class="textlink"><?php echo $TEXT['login-lostpasswort']?>?</a>
<?php } ?>
