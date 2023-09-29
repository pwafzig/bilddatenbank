<?php include("../../php/includes/start.inc.php"); ?>
<html>
<?php include(DOCROOT.INSTALLPATH."/php/includes/styles.inc.php"); ?>
<?php include(DOCROOT.INSTALLPATH."/admin/php/includes/adminstyles.inc.php"); ?>
<?php include(DOCROOT.INSTALLPATH."/php/includes/scripts.inc.php"); ?>
<?php include(DOCROOT.INSTALLPATH."/admin/php/includes/adminscripts.inc.php"); ?>
<body>

<table id="bintable" border="0" width="810" height="540">
<tr><td valign="top">

<?php

	//Editieren einer Access-ID
	if($_GET['action'] == "edit"){

		if(isset($_POST['sent']) && $_POST['sent'] == "yes"){

			//Datum umformatieren
			$date = $_POST['jahr'].$_POST['monat'].$_POST['tag'];

			$stmt = "UPDATE accessids SET date = '".$date."', bemerkung = '".$_POST['bemerkung']."', name = '".$_POST['name']."', resolution = '".$_POST['resolution']."' WHERE id = ".$_POST['id']." LIMIT 1";

			$query = mysqli_query($link, $stmt);
			if (!$query) {
    			die('Datenbankfehler: ' . mysqli_error($link));
			} else {
				echo "Die Daten wurden korrekt in der Datenbank ge&auml;ndert. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='/".INSTALLPATH."/admin/index.php?sect=accessids';\" class=\"submit\">Schlie&szlig;en</button>";
			}
		} else {

		//Access-IDs aus DB lesen
		$stmt = "SELECT * FROM accessids WHERE id='".$_GET['id']."'";
		$query = mysqli_query($link, $stmt);
		$out = mysqli_fetch_row($query);

		//Formular anzeigen	?>

		<script language="javascript">
			$().ready(function(){
				$("#binform").validate({
				rules: {
						name: {
							required: true,
							minlength: 5
						}
					},
					messages: {
						name: "Bitte einen Namen eingeben (mind. 5 Zeichen)"
						}
					});
				});
		</script>

		<?php
		//Datum umformatieren

		$year = substr($out[2], 0, 4);
		$mon  = substr($out[2], 4, 2);
		$day  = substr($out[2], 6, 2);

		//Datumsdropdown erstellen

		$days 	= range (01, 31);
		$months = range (01, 12);
		$years 	= range (date('Y'), date('Y')+5);

		$daysel  = "<select name=\"tag\">";
			foreach ($days as $value) {
				$value = sprintf("%02d",$value);
				if($value == $day){
					$daysel .= "<option value=\"".$value."\" selected>".$value."</option>\n";
				} else {
					$daysel .= "<option value=\"".$value."\">".$value."</option>\n";
				}
			}
		$daysel .= "</select>";

		$monthsel  = "<select name=\"monat\">";
			foreach ($months as $value) {
				$value = sprintf("%02d",$value);
				if($value == $mon){
					$monthsel .= "<option value=\"".$value."\" selected>".$value."</option>\n";
				} else {
					$monthsel .= "<option value=\"".$value."\">".$value."</option>\n";
				}
			}
		$monthsel .= "</select>";

		$yearsel  = "<select name=\"jahr\">";
			foreach ($years as $value) {
				if($value == $year){
					$yearsel .= "<option value=\"".$value."\" selected>".$value."</option>\n";
				} else {
					$yearsel .= "<option value=\"".$value."\">".$value."</option>\n";
				}
			}
		$yearsel .= "</select>";

		?>

		<form action="<?php echo $_SERVER['PHP_SELF']?>?action=edit" method="POST" name="editaccessid" id="binform">
			<fieldset>
				<legend>AccessID bearbeiten</legend>
					<dl>
		        		<dt><label for="name">Name:</label></dt>
        		    	<dd><input type="text" name="name" id="name" size="32" maxlength="128" value="<?php echo htmlentities($out[4])?>" /></dd>
        			</dl>
					<dl>
        				<dt><label for="bemerkung">Bemerkung:</label></dt>
            			<dd><textarea name="bemerkung" rows="5" cols="45" id="bemerkung"><?php echo htmlentities($out[3]);?></textarea></dd>
        			</dl>
					<dl>
        				<dt><label for="date">Ablauf:</label></dt>
            			<dd><?php echo $daysel."&nbsp;".$monthsel."&nbsp;".$yearsel?></dd>
        			</dl>
					<dl>
        				<dt><label for="highres">Download:</label></dt>
            			<dd><input type="radio" name="resolution" id="resolution" value="highres"<?php if($out[5] == "highres") echo " checked"; ?> /> Originale&nbsp;<input type="radio" name="resolution" id="resolution" value="lowres"<?php if($out[5] == "lowres") echo " checked"; ?> /> Web-Auflösung</dd>
        			</dl>        			
			</fieldset>
			<fieldset class="action">
				<input type="hidden" name="sent" value="yes">
				<input type="hidden" name="id" value="<?php echo $out[0]?>">
				<input type="submit" name="submit" id="submit" value="Access-ID &auml;ndern" class="submit" />
			</fieldset>
		</form>

	<?php

		}
	}

	//Neuanlegen einer Access-ID
	if($_GET['action'] == "add"){

		if(isset($_POST['sent']) && $_POST['sent'] == "yes"){

			//Zugangshash erzeugen
			$hash = date('YmdHis').$_SERVER['HTTP_HOST'];

			//Datum umformatieren
			$date = $_POST['jahr'].$_POST['monat'].$_POST['tag'];

			$stmt = "INSERT INTO accessids (id, hash, date, bemerkung, name, resolution, downloads, timestamp, lastlogin) VALUES (NULL, MD5('".$hash."'), '".$date."', '".utf8_decode($_POST['bemerkung'])."', '".utf8_decode($_POST['name'])."', '".$_POST['resolution']."', '0', CURRENT_TIMESTAMP, CURRENT_TIME())";
			$query = mysqli_query($link, $stmt);
			if (!$query) {
    			die('Datenbankfehler: ' . mysqli_error($link));
			} else {
				echo "Die Access-ID wurde korrekt in die Datenbank eingef&uuml;gt. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='/".INSTALLPATH."/admin/index.php?sect=accessids';\" class=\"submit\">Schlie&szlig;en</button>";
			}
		} else { ?>

		<script language="javascript">
			$().ready(function(){
				$("#binform").validate({
				rules: {
						name: {
							required: true,
							minlength: 5
						}
					},
					messages: {
						name: "Bitte einen Namen eingeben (mind. 5 Zeichen)"
						}
					});
				});
		</script>

		<?php
		//Datumsdropdown erstellen

		$days 	= range (01, 31);
		$months = range (01, 12);
		$years 	= range (date('Y'), date('Y')+5);

		$daysel  = "<select name=\"tag\">";
			foreach ($days as $value) {
				$value = sprintf("%02d",$value);
				if($value == date('d')){
					$daysel .= "<option value=\"".$value."\" selected>".$value."</option>\n";
				} else {
					$daysel .= "<option value=\"".$value."\">".$value."</option>\n";
				}
			}
		$daysel .= "</select>";

		$monthsel  = "<select name=\"monat\">";
			foreach ($months as $value) {
				$value = sprintf("%02d",$value);
				if($value == date('m')){
					$monthsel .= "<option value=\"".$value."\" selected>".$value."</option>\n";
				} else {
					$monthsel .= "<option value=\"".$value."\">".$value."</option>\n";
				}
			}
		$monthsel .= "</select>";

		$yearsel  = "<select name=\"jahr\">";
			foreach ($years as $value) {
				if($value == date('Y')){
					$yearsel .= "<option value=\"".$value."\" selected>".$value."</option>\n";
				} else {
					$yearsel .= "<option value=\"".$value."\">".$value."</option>\n";
				}
			}
		$yearsel .= "</select>";

		?>

		<form action="<?php echo $_SERVER['PHP_SELF']?>?action=add" method="POST" name="edituser" id="binform">
			<fieldset>
				<legend>Benutzer bearbeiten</legend>
					<dl>
		        		<dt><label for="name">Name:</label></dt>
        		    	<dd><input type="text" name="name" id="name" size="32" maxlength="128" value="" /></dd>
        			</dl>
					<dl>
        				<dt><label for="bemerkung">Bemerkung:</label></dt>
            			<dd><textarea name="bemerkung" rows="5" cols="45" id="bemerkung"></textarea></dd>
        			</dl>
					<dl>
        				<dt><label for="date">Ablauf:</label></dt>
            			<dd><?php echo $daysel."&nbsp;".$monthsel."&nbsp;".$yearsel?></dd>
        			</dl>
        			<dl>
        				<dt><label for="highres">Download:</label></dt>
            			<dd><input type="radio" name="resolution" id="resolution" value="highres" /> Originale&nbsp;<input type="radio" name="resolution" id="resolution" value="lowres" /> Web-Auflösung</dd>
        			</dl>
			</fieldset>
			<fieldset class="action">
				<input type="hidden" name="sent" value="yes">
				<input type="hidden" name="id" value="<?php echo $out[0]?>">
				<input type="submit" name="submit" id="submit" value="Access-ID eintragen" class="submit" />
			</fieldset>
		</form>

		<?php

		}
	}

	//Loeschen einer Access-ID
	if($_GET['action'] == "kill"){

		if(!isset($_GET['confirm'])){

			//Daten aus DB lesen
			$stmt = "SELECT * FROM accessids WHERE id='".$_GET['id']."'";
			$query = mysqli_query($link, $stmt);
			$out = mysqli_fetch_row($query);

			$confirm = md5($out[4]);

	?>
		Access-ID <span style="color:red;font-weight:bold"><?php echo $out[4]?></span> wirklich l&ouml;schen?
		<button name="killbutton" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $out['0']?>&action=kill&confirm=<?php echo $confirm?>'" class="submit">Ja, l&ouml;schen</button>

	<?php
		} else {

			//Daten aus DB lesen
			$stmt = "SELECT * FROM accessids WHERE id='".$_GET['id']."'";
			$query = mysqli_query($link, $stmt);
			$out = mysqli_fetch_row($query);

			$check = md5($out[4]);

			if($check == $_GET['confirm']){
				$stmt = "DELETE FROM accessids WHERE id = ".$out[0]." LIMIT 1";
				$query = mysqli_query($link, $stmt);
				if (!$query) {
    				die('Datenbankfehler: ' . mysql_error());
				} else {
					echo "Access-ID wurde gel&ouml;scht. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='/".INSTALLPATH."/admin/index.php?sect=accessids';\" class=\"submit\">Schlie&szlig;en</button>";
				}
			} else {
				echo "&Uuml;berpr&uuml;fung fehlgeschlagen. Access-ID nicht gel&ouml;scht. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='/".INSTALLPATH."/admin/index.php?sect=accessids';\" class=\"submit\">Schlie&szlig;en</button>";
			}
		}
	}

?>


</td></tr>
</table>

</body>
</html>