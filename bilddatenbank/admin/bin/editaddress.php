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

	//Editieren eines Users
	if($_GET[action] == "edit"){

		if($_POST['sent'] == "yes"){
			$stmt = "UPDATE newsletter SET anrede = '".$_POST['anrede']."', name = '".$_POST['name']."', redaktion = '".$_POST['redaktion']."', email = '".$_POST['email']."' WHERE id = ".$_POST['id']." LIMIT 1";

			$query = mysql_query(utf8_decode($stmt));
			if (!$query) {
    			die('Datenbankfehler: ' . mysql_error());
			} else {
				echo "Die Daten wurden korrekt in der Datenbank ge&auml;ndert. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='".INSTALLPATH."/admin/index.php?sect=newsletter';\" class=\"submit\">Schlie&szlig;en</button>";
			}
		} else {

		//Benutzerdaten aus DB lesen
		$stmt = "SELECT * FROM newsletter WHERE id='".$_GET[id]."'";
		$query = mysql_query($stmt);
		$out = mysql_fetch_row($query);

		//Formular anzeigen	?>

		<script language="javascript">
			$().ready(function(){
				$("#binform").validate({
				rules: {
						email: {
							required: true,
							email: true
						}
					},
					messages: {
						email: "Bitte eine g&uuml;ltige Emailadresse eingeben"
					}
					});
				});
		</script>

		<form action="<?php echo $_SERVER[PHP_SELF]?>?action=edit" method="POST" name="edituser" id="binform">
			<fieldset>
				<legend>Adresse bearbeiten</legend>
					<dl>
		        		<dt><label for="anrede">Anrede:</label></dt>
        		    	<dd><input type="text" name="anrede" id="anrede" size="16" maxlength="128" value="<?php echo utf8_encode($out[1])?>" /></dd>
        			</dl>
					<dl>
		        		<dt><label for="name">Name:</label></dt>
        		    	<dd><input type="text" name="name" id="name" size="64" maxlength="128" value="<?php echo utf8_encode($out[2])?>" /></dd>
        			</dl>
					<dl>
        				<dt><label for="redaktion">Redaktion:</label></dt>
            			<dd><input type="text" name="redaktion" id="redaktion" size="64" maxlength="128" value="<?php echo utf8_encode($out[3])?>" /></dd>
        			</dl>
					<dl>
        				<dt><label for="email">Email:</label></dt>
            			<dd><input type="text" name="email" id="email" size="64" maxlength="128" value="<?php echo utf8_encode($out[4])?>" /></dd>
        			</dl>
			</fieldset>
			<fieldset class="action">
				<input type="hidden" name="sent" value="yes">
				<input type="hidden" name="id" value="<?php echo $out[0]?>">
				<input type="submit" name="submit" id="submit" value="Adresse &auml;ndern" class="submit" />
			</fieldset>
		</form>

	<?php
		}
	}

	//Neuanlegen eines Users
	if($_GET[action] == "add"){

		if($_POST['sent'] == "yes"){
			$stmt = "INSERT INTO newsletter (id, anrede, name, redaktion, email) VALUES (NULL, '".$_POST[anrede]."', '".$_POST[name]."', '".$_POST[redaktion]."', '".$_POST[email]."');";
			$query = mysql_query(utf8_decode($stmt));
			if (!$query) {
    			die('Datenbankfehler: ' . mysql_error());
			} else {
				echo "Die Adresse wurde korrekt in die Datenbank eingef&uuml;gt. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='".INSTALLPATH."/admin/index.php?sect=newsletter';\" class=\"submit\">Schlie&szlig;en</button>";
			}
		} else {

		//Formular anzeigen	?>
		<script language="javascript">
			$().ready(function(){
				$("#binform").validate({
				rules: {
						email: {
							required: true,
							email: true
						}
					},
					messages: {
						email: "Bitte eine g&uuml;ltige Emailadresse eingeben"
					}
					});
				});

		</script>

		<form action="<?php echo $_SERVER[PHP_SELF]?>?action=add" method="POST" name="edituser" id="binform">
			<fieldset>
				<legend>Adresse eintragen</legend>
					<dl>
		        		<dt><label for="anrede">Anrede:</label></dt>
        		    	<dd><input type="text" name="anrede" id="anrede" size="32" maxlength="128" value="" /></dd>
        			</dl>
        			<dl>
		        		<dt><label for="name">Name:</label></dt>
        		    	<dd><input type="text" name="name" id="name" size="32" maxlength="128" value="" /></dd>
        			</dl>
					<dl>
        				<dt><label for="redaktion">Redaktion:</label></dt>
            			<dd><input type="text" name="redaktion" id="redaktion" size="32" maxlength="128" value="" /></dd>
        			</dl>
					<dl>
        				<dt><label for="email">Email:</label></dt>
            			<dd><input type="text" name="email" id="email" size="32" maxlength="128" value="" /></dd>
        			</dl>
			</fieldset>
			<fieldset class="action">
				<input type="hidden" name="sent" value="yes">
				<input type="submit" name="submit" id="submit" value="Adresse anlegen" class="submit" />
			</fieldset>
		</form>

		<?php

		}
	}

	//Loeschen eines Users
	if($_GET['action'] == "kill"){

		if(!$_GET['confirm']){

			//Benutzerdaten aus DB lesen
			$stmt = "SELECT * FROM newsletter WHERE id='".$_GET[id]."'";
			$query = mysql_query($stmt);
			$out = mysql_fetch_row($query);

			$confirm = md5($out[1]);

	?>
		Adresse <span style="color:red;font-weight:bold"><?php echo $out[4]?></span> wirklich l&ouml;schen?
		<button name="killbutton" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $out['0']?>&action=kill&confirm=<?php echo $confirm?>'" class="submit">Ja, l&ouml;schen</button>

	<?php
		} else {

			//Benutzerdaten aus DB lesen
			$stmt = "SELECT * FROM newsletter WHERE id='".$_GET[id]."'";
			$query = mysql_query($stmt);
			$out = mysql_fetch_row($query);

			$check = md5($out[1]);

			if($check == $_GET[confirm]){
				$stmt = "DELETE FROM newsletter WHERE id = ".$out[0]." LIMIT 1";
				$query = mysql_query($stmt);
				if (!$query) {
    				die('Datenbankfehler: ' . mysql_error());
				} else {
					echo "Adresse wurde gel&ouml;scht. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='".INSTALLPATH."/admin/index.php?sect=newsletter';\" class=\"submit\">Schlie&szlig;en</button>";
				}
			} else {
				echo "&Uuml;berpr&uuml;fung fehlgeschlagen. Adresse nicht gel&ouml;scht. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='".INSTALLPATH."/admin/index.php?sect=newsletter';\" class=\"submit\">Schlie&szlig;en</button>";
			}
		}
	}

?>


</td></tr>
</table>

</body>
</html>