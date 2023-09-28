<?php include("../php/includes/start.inc.php"); ?>
<?php if(!isset($_POST['submit'])){ ?>
<?php
	if(isset($_GET['err'])){
		if(preg_match("/[0-9]/", $_GET['err'])){
			$err = $_GET['err'];
			switch ($err) {
		    case 1:
		        $errmsg = "Falsches Passwort";
		        break;
		    case 2:
		        $errmsg = "Falscher Login";
		    	break;
		    case 3:
		        $errmsg = "Falsche Zugangsdaten";
		        break;
		    default:
		    	$errmsg = "Unbekannter Fehler";
			}
		} else {
			$errmsg = "Unbekannter Fehler";
		}
	}
?>
<html>
<head>
<title>
</title>
</head>
<body>
<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
	<tr>
		<td valign="middle" align="center">
			<table border="0" cellspacing="0" cellpadding="5" width="450" style="border:1px solid #c0c0c0; background-color:#efefef; font-family:Arial; font-size:12px">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="login">
				<tr>
					<td colspan="2" align="center"><img src="/<?php echo INSTALLPATH; ?>/images/logo.gif" border="0" width="200"></td>
				</tr>
				<?php if(isset($errmsg)) { ?>
				<tr>
					<td colspan="2" style="padding:20px" align="center"><span style="color:red"><strong><?php echo $errmsg; ?></strong></span></td>
				</tr>
				<?php } ?>
				<tr>
					<td valign="middle" align="right">
						<label for="login">Login:</label>
					</td>
					<td>
						<input name="login" type="text" value=""><br />
					</td>
				</tr>
				<tr>
					<td valign="middle" align="right">
						<label for="password">Passwort:</label>
					</td>
					<td>
						<input name="password" type="password" value="">
					</td>
				</tr>
				<tr>
					<td>
						&nbsp;
					</td>
					<td>
						<br /><input type="submit" name="submit" value="Einloggen"><br /><br />
					</td>
				</tr>
				</form>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php } else {

	$stmt = "SELECT * FROM konfig";
	$query = mysqli_query($link, $stmt);
	while($out = mysqli_fetch_array($query)){
		$CONFIG[$out[1]] = $out[2];
	}

	if($_POST['login'] == $CONFIG['login']){
		//Login ok
		$login = "true";
	} else {
		//Login failed
		$login = "false";
	}

	if($_POST['password'] == $CONFIG['password']){
		//Password ok
		$password = "true";
	} else {
		//Password failed
		$password = "false";
	}

	if(($login == "true") AND ($password == "true")){
		$_SESSION['adminlogin'] = "true";
		header("Location:http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/admin/index.php");
	}

	if(($login == "true") AND ($password == "false")){
		header("Location:http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/admin/login.php?err=1");
	}

	if(($login == "false") AND ($password == "true")){
		header("Location:http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/admin/login.php?err=2");
	}

	if(($login == "false") AND ($password == "false")){
		header("Location:http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/admin/login.php?err=3");
	}
}

?>