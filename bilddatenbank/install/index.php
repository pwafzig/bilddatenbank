<html>
<head>
	<title>Installationsroutine Bilddatenbank</title>
	<meta charset="utf-8" />
	<style>
		body {
			background-color: #EBEBEB;
			padding: 30px 160px 30px 160px;
			font-family: Arial, Helvetica, Sans-Serif;
			font-size: 89%;
		}
		.okay {
			color: #00FF00;
			font-weight: bold;
		}

		.fault {
			color: #FF0000;
			font-weight: bold;
		}

		.optional {
			color: #FFFF00;
			font-weight: bold;
		}

		#main {
			border: 1px solid #C0C0C0;
			background-color: #FFF;
			padding: 20px;
		}

		#install input {
			padding: 3px;
		}

		.ruler {
			border-top: 1px solid #C0C0C0;
			margin: 30px 0px 30px 0px;
		}

		.absenden {
			border: 3px solid #47d111;
			background-color: #a3ef85;
			padding: 3px 20px 3px 20px;
			margin-left: 5px;
			font-size: 105%;
			font-weight: bold;
			cursor: pointer;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			border-radius: 5px;
		}

		.retry {
			border: 3px solid #ebc034;
			background-color: #ebdf34;
			padding: 3px 20px 3px 20px;
			font-size: 105%;
			font-weight: bold;
			cursor: pointer;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			border-radius: 5px;
		}
		pre code {
			background-color: #eee;
			border: 1px solid #999;
			display: block;
			padding: 20px;
			font-size: 125%;
		}
		input:read-only {
			background-color: #e1e1e1;
			border: 1px solid #a1a1a1;
		}
	</style>
</head>
<body>
<h1>Installation</h1>
<div id="main">

<?php if(!isset($_POST['submit'])) { ?>

<h3>Checking your basic server configuration</h3>
<p><?php
	//Don't use trailing slashes in your document root, this non standard and breaks a ton of things...
	if (substr($_SERVER['DOCUMENT_ROOT'], -1) != '/'){
		echo "Your DOCUMENT_ROOT variable has no trailing slash... <span class=\"okay\">OK</span><br />";
	} else {
		echo "Your DOCUMENT_ROOT variable contains a trailing slash (e.g. /var/www/bilddatenbank/) <span class=\"fault\">this is a non-standard configuration</span> please remove the trailing slash before continuing...<br />";
		echo "<br /><button name=\"reload\" class=\"retry\" onClick=\"window.location.reload();\">Retry...</button>";
		exit;
	} 
?>

<?php if(!isset($_GET['httpscheck']) OR $_GET['httpscheck'] != "true"){ ?>
<hr border="0" noshade size="1" color="#FFF" class="ruler">
<h3>Checking HTTPS</h3>
<p><?php
	//You should use https but we allow you to dismiss ist
	if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443){
		echo "Your server is running HTTPS... <span class=\"okay\">OK</span><br />";
	} else {
		echo "Your server isn't running HTTPS: <span class=\"fault\">This is a security risk, we strongly advise to set it up running HTTPS!</span><br />";
		echo "<br /><button name=\"reload\" class=\"retry\" onClick=\"window.open('".$_SERVER['PHP_SELF']."?httpscheck=true', '_self');\">Dismiss or retry...</button>";
		exit;
	} 
?>
</p>
<?php } else { ?>
<hr border="0" noshade size="1" color="#FFF" class="ruler">
<h3>Checking HTTPS</h3>
HTTPS check done or dismissed... <span class=\"okay\">OK</span><br />
</p>
<?php } ?>


<hr border="0" noshade size="1" color="#FFF" class="ruler">
<h3>Checking PHP version</h3>
<p><?php
	//PHP version needs to be at least 8.something
	$version = substr(phpversion("Core"), 0, 1);
	if ($version < 8) {
		echo "PHP version seems to be lower than 8.0... <span class=\"fault\">You need to update at least to version 8!</span><br />";
		exit;
	} else {
		echo "PHP version 8 or higher... <span class=\"okay\">OK</span><br />";
	}	
?>
</p>


<hr border="0" noshade size="1" color="#FFF" class="ruler">
<h3>Checking server software</h3>
<p><?php 
	//checking which server software you are using
	if(!isset($_SERVER["SERVER_SOFTWARE"])){
		echo "Your server software isn't detectable... <span class=\"okay\">OK</span><br />";
	} elseif (preg_match("/Apache/i", $_SERVER["SERVER_SOFTWARE"])){
		$server_software = "apache";
		echo "You are using Apache... <span class=\"okay\">OK</span><br />";

		if (!in_array('mod_rewrite', apache_get_modules())) {
			echo "PHP Function Apache Rewrite is not activated... <span class=\"fault\">ERROR</span><br />";
			exit;
		} else {
			echo "PHP Function Apache Rewrite activated... <span class=\"okay\">OK</span><br />";
		}

	} elseif (preg_match("/nginx/i", $_SERVER["SERVER_SOFTWARE"])){
		$server_software = "nginx";
		echo "You are using nginx... ";
		echo "<strong>Make sure your server configuration uses the following redirect:</strong><br /><br />";
	?>
	<pre>
		<code>location / {
  try_files $uri $uri/ /bilddatenbank/index.php?path=$request_uri&rewrite=true;
}</code>
    </pre>

<?php }
	$extensions_available = get_loaded_extensions();
	$extensions = array("zlib", "session", "zip", "exif", "gd", "mysqli", "pcre"); 
	$install = true;

	foreach ($extensions as $extension) {
  		if (!in_array($extension, $extensions_available)) {
    		echo "Extension ".$extension." is not available ... <span class=\"fault\">ERROR</span><br />";
    		$install = false;
    	} else {
			echo "Extension ".$extension." is available ...  <span class=\"okay\">OK</span><br />";
		}
	}

	if (ini_get("safe_mode")) {
    	echo "Safe Mode active - needs to be turned off.";
    	$install = false;
    } else {
		echo "Safe Mode is off... <span class=\"okay\">OK</span><br />";
	}

	if($install != true){
		echo "<br /><button name=\"reload\" class=\"retry\" onClick=\"window.location.reload();\">Retry...</button>";
		exit;
	}	
?>	
</p>

<hr border="0" noshade size="1" color="#FFF" class="ruler">
<h3>Checking directories</h3>
<?php

	$directories = array("/bilddatenbank", "/bilddatenbank/bin", "/bilddatenbank/secure", "/bilddatenbank/logs", "/bilddatenbank/temp", "/bilddatenbank/admin/backup", "/bilddatenbank/data", "/bilddatenbank/lowres", "/bilddatenbank/thumbs", "/bilddatenbank/previews"); //TODO: remove /bilddatenbank and make install dire flexible
	$install = true;

	foreach ($directories as $directory) {
  		if (!is_writeable($_SERVER['DOCUMENT_ROOT'].$directory)) {
    		echo "Directory ".$directory." is not writeable... <span class=\"fault\">ERROR</span><br />";
    		$install = false;
    	} else {
			echo "Directory ".$directory." is writeable......  <span class=\"okay\">OK</span><br />";
		}
	}

	if($install != true){
		echo "<br /><button name=\"reload\" class=\"retry\" onClick=\"window.location.reload();\">Retry...</button>";
		exit;
	}	

?>
<hr border="0" noshade size="1" color="#FFF" class="ruler">
<h3>Basic install data</h3>
<p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="install" id="install">
<input type="hidden" name="server_software" value="<?php echo $server_software; ?>">

<?php
	//find executable
	$php_executable = PHP_BINDIR."/php"; 
	//find installpath
	$installpath = $_SERVER['PHP_SELF'];
	$installpath = explode("/",$installpath);
	$installpath = $installpath[1];
?>

<input type="hidden" name="php_executable" size="90" value="<?php echo $php_executable; ?>">

<label for="hostname">URL</label><br />
<input type="text" name="hostname" size="90" value="<?php echo $_SERVER['HTTP_HOST']; ?>" readonly><br /><br />

<label for="docroot">Document root path</label><br />
<input type="text" name="docroot" size="90" value="<?php echo $_SERVER['DOCUMENT_ROOT'] ?>" readonly><br /><br />

<label for="installpath">Install directory:</label><br />

<input type="text" name="installpath" size="90" value="<?php echo $installpath; ?>" readonly><br /><br />

<label for="email">Admin email</label><br />
<input type="text" name="email" size="90" placeholder="name@email.com"><br /><br />



<hr border="0" noshade size="1" color="#FFF" class="ruler">
<h3>Database connection data</h3>
<p>
<label for="dbname">Database name</label><br />
<input type="text" name="dbname" size="50" value=""><br /><br />

<label for="dbuser">User</label><br />
<input type="text" name="dbuser" size="50" value=""><br /><br />

<label for="dbpass">Password</label><br />
<input type="text" name="dbpass" size="50" value=""><br /><br />

<label for="dbhost">Host (should be "localhost" unless you use a external mysql server)</label><br />
<input type="text" name="dbhost" size="50" value="localhost"><br /><br />

<strong>Everything correct? Then: <input type="submit" name="submit" value="&nbsp;&nbsp;&nbsp;Start installation...&nbsp;&nbsp;&nbsp;" class="absenden">
</form>
</p>

<?php } else { ?>

<h3>Setup database</h3>
<p>

<?php

	//Datenbankverbindung aufbauen
	mysqli_report(MYSQLI_REPORT_OFF);
	$link = @mysqli_connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname']);
	
	if (!$link) {
		die("Connection failed: " . mysqli_connect_error() . " - please check your database credentials!");
	} else {
		echo "Connection data... <span class=\"okay\">OK</span><br />";
	}

	//Datenbank import starten

	$import = file_get_contents("templates/bilddatenbank.sql");

	$import = preg_replace ("%/\*(.*)\*/%Us", '', $import);
	$import = preg_replace ("%^--(.*)\n%mU", '', $import);
	$import = preg_replace ("%^$\n%mU", '', $import);

	mysqli_real_escape_string($link, $import);
	$import = explode (";", $import);

	foreach ($import as $imp){
	if ($imp != '' && $imp != ' '){
		$result = mysqli_query($link, $imp);

			if(!$result) {
				echo "Database file could not be imported... <span class=\"fault\">ERROR</span><br /><pre>".mysqli_error($link)."</pre>";
				exit;
			} else {
				$out = "Database import... <span class=\"okay\">OK</span><br />";
			}
		}
	}

	echo $out;

?>

</p>
<h3>Write files</h3>
<p>

<?php

	//Templates laden und Dateien schreiben
	//config.inc.php

	$shebang = $_POST['php_executable'];

	$config = file_get_contents("templates/config.inc.php");
	$config = preg_replace("/@@INSTALLPATH@@/", $_POST['installpath'], $config);
	$config = preg_replace("/@@DOCROOT@@/", $_POST['docroot'], $config);
	$config = preg_replace("/@@HTTPHOST@@/", $_POST['hostname'], $config);

	file_put_contents($_POST['docroot']."/config.inc.php", $config);
	@chmod ($_POST['docroot']."/config.inc.php", 0644);

	echo "File <strong>config.inc.php</strong> successfully written... <span class=\"okay\">OK</span><br />";

	//dbconnect.inc.php

	$dbconnect = file_get_contents("templates/dbconnect.inc.php");
	$dbconnect = preg_replace("/@@DBHOST@@/", $_POST['dbhost'], $dbconnect);
	$dbconnect = preg_replace("/@@DBUSER@@/", $_POST['dbuser'], $dbconnect);
	$dbconnect = preg_replace("/@@DBNAME@@/", $_POST['dbname'], $dbconnect);
	$dbconnect = preg_replace("/@@DBPASS@@/", $_POST['dbpass'], $dbconnect);

	file_put_contents($_POST['docroot']."/".$_POST['installpath']."/secure/dbconnect.inc.php", $dbconnect);
	@chmod ($_POST['docroot']."/".$_POST['installpath']."/secure/dbconnect.inc.php", 0644);
	echo "File <strong>connect.inc.php</strong> successfully written... <span class=\"okay\">OK</span><br />";

	//.htaccess

	if(isset($_POST['server_software']) AND $_POST['server_software'] == "apache"){
		$htaccess = file_get_contents("templates/htaccess");
		$htaccess = preg_replace("/@@INSTALLPATH@@/", $_POST['installpath'], $htaccess);

		file_put_contents($_POST['docroot']."/".$_POST['installpath']."/.htaccess", $htaccess);
		@chmod ($_POST['docroot']."/".$_POST['installpath']."/.htaccess", 0644);

		echo "File <strong>.htaccess</strong> successfully written... <span class=\"okay\">OK</span><br />";
	}

	//deamon.php

	$deamon = file_get_contents("templates/deamon.php");
	$deamon = preg_replace("/@@SHEBANG@@/", $_POST['php_executable'], $deamon);
	$deamon = preg_replace("/@@HOSTNAME@@/", $_POST['hostname'], $deamon);
	$deamon = preg_replace("/@@DOCROOT@@/", $_POST['docroot'], $deamon);
	$deamon = preg_replace("/@@INSTALLPATH@@/", $_POST['installpath'], $deamon);
	$deamon = preg_replace("/@@EMAIL@@/", $_POST['email'], $deamon);
	$deamon = preg_replace("/@@DBHOST@@/", $_POST['dbhost'], $deamon);
	$deamon = preg_replace("/@@DBUSER@@/", $_POST['dbuser'], $deamon);
	$deamon = preg_replace("/@@DBNAME@@/", $_POST['dbname'], $deamon);
	$deamon = preg_replace("/@@DBPASS@@/", $_POST['dbpass'], $deamon);

	file_put_contents($_POST['docroot']."/".$_POST['installpath']."/bin/deamon.php", $deamon);
	@chmod ($_POST['docroot']."/".$_POST['installpath']."/bin/deamon.php", 0755);

	echo "File <strong>deamon.php</strong> successfully written... <span class=\"okay\">OK</span><br />";

	//dbreader.php

	$dbreader = file_get_contents("templates/dbreader.php");
	$dbreader = preg_replace("/@@SHEBANG@@/", $shebang, $dbreader);
	$dbreader = preg_replace("/@@DOCROOT@@/", $_POST['docroot'], $dbreader);
	$dbreader = preg_replace("/@@INSTALLPATH@@/", $_POST['installpath'], $dbreader);
	$dbreader = preg_replace("/@@EMAIL@@/", $_POST['email'], $dbreader);
	$dbreader = preg_replace("/@@DBHOST@@/", $_POST['dbhost'], $dbreader);
	$dbreader = preg_replace("/@@DBUSER@@/", $_POST['dbuser'], $dbreader);
	$dbreader = preg_replace("/@@DBNAME@@/", $_POST['dbname'], $dbreader);
	$dbreader = preg_replace("/@@DBPASS@@/", $_POST['dbpass'], $dbreader);

	file_put_contents($_POST['docroot']."/".$_POST['installpath']."/bin/dbreader.php", $dbreader);
	@chmod ($_POST['docroot']."/".$_POST['installpath']."/bin/dbreader.php", 0755);

	echo "File <strong>dbreader.php</strong> successfully written... <span class=\"okay\">OK</span><br />";

	echo "<br /><br /><strong>Installation done - head on to <a href=\"/".$_POST['installpath']."/index.php\">Bilddatenbank...</a></strong>";

}

?>
</div>
</body>
</html>