<?php include("../../php/includes/start.inc.php"); ?>
<html>
<?php include(DOCROOT."/".INSTALLPATH."/php/includes/styles.inc.php"); ?>
<?php include(DOCROOT."/".INSTALLPATH."/admin/php/includes/adminstyles.inc.php"); ?>
<?php include(DOCROOT."/".INSTALLPATH."/php/includes/scripts.inc.php"); ?>
<?php include(DOCROOT."/".INSTALLPATH."/admin/php/includes/adminscripts.inc.php"); ?>
<body>

<table id="bintable" border="0" width="810" height="540">
<tr><td valign="top">

<?php

	//Editieren einer Kollektion
	if($_GET['action'] == "edit"){

		if(isset($_POST['sent']) && $_POST['sent'] == "yes"){

			$stmt = "UPDATE collections SET name = '".$_POST['name']."', comment = '".$_POST['comment']."', ids = '".$_POST['ids']."' WHERE id = ".$_POST['id']." LIMIT 1";

			$query = mysqli_query($link, $stmt);
			if (!$query) {
    			die('Datenbankfehler: ' . mysqli_error($link));
			} else {
				echo "Die Daten wurden korrekt in der Datenbank ge&auml;ndert. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='/".INSTALLPATH."/admin/index.php?sect=collections';\" class=\"submit\">Schlie&szlig;en</button>";
			}
		} else {

		//Kollektionen aus DB lesen
		$stmt = "SELECT * FROM collections WHERE id='".$_GET['id']."'";
		$query = mysqli_query($link, $stmt);
		$out = mysqli_fetch_row($query);

		//Formular anzeigen	?>

		<form action="<?php echo $_SERVER['PHP_SELF']?>?action=edit" method="POST" name="editcollections" id="binform">
			<fieldset>
				<legend>Kollektion anlegen / bearbeiten</legend>
					<dl>
		        		<dt><label for="name">Name:</label></dt>
        		    	<dd><input type="text" name="name" id="name" size="32" maxlength="128" value="<?php echo htmlentities($out[1])?>" /></dd>
        			</dl>
					<dl>
        				<dt><label for="comment">Bemerkung:</label></dt>
            			<dd><textarea name="comment" rows="5" cols="45" id="comment"><?php echo htmlentities($out[2]);?></textarea></dd>
        			</dl>
					<dl>
        				<dt><label for="ids">Enthaltene Bilder (kommagetrennte Bild-Nummern):</label></dt>
            			<dd><textarea name="ids" rows="5" cols="45" id="ids"><?php echo htmlentities($out[3]);?></textarea></dd>
        			</dl>
			</fieldset>
			<fieldset class="action">
				<input type="hidden" name="sent" value="yes">
				<input type="hidden" name="id" value="<?php echo $out[0]?>">
				<input type="submit" name="submit" id="submit" value="Kollektion eintragen / &auml;ndern" class="submit" />
			</fieldset>
		</form>

	<?php

		}
	}

	//Neuanlegen einer Kollektion
	if($_GET['action'] == "add"){

		if(isset($_POST['sent']) && $_POST['sent'] == "yes"){

			$stmt = "INSERT INTO collections (id, name, comment, ids) VALUES (NULL, '".$_POST['name']."', '".$_POST['comment']."', '".$_POST['ids']."');";
			$query = mysqli_query($link, $stmt);
			if (!$query) {
    			die('Datenbankfehler: ' . mysql_error());
			} else {
				echo "Die Kollektion wurde korrekt in die Datenbank eingef&uuml;gt. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='/".INSTALLPATH."/admin/index.php?sect=collections';\" class=\"submit\">Schlie&szlig;en</button>";
			}
		} else { ?>

		<form action="<?php echo $_SERVER['PHP_SELF']?>?action=add" method="POST" name="editcollections" id="binform">
			<fieldset>
				<legend>Kollektion bearbeiten</legend>
					<dl>
		        		<dt><label for="name">Name:</label></dt>
        		    	<dd><input type="text" name="name" id="name" size="32" maxlength="128" value="" /></dd>
        			</dl>
					<dl>
        				<dt><label for="comment">Bemerkung:</label></dt>
            			<dd><textarea name="comment" rows="5" cols="45" id="comment"></textarea></dd>
        			</dl>
					<dl>
        				<dt><label for="ids">Enthaltene Bilder (kommagetrennte Bild-Nummern):</label></dt>
            			<dd><textarea name="ids" rows="5" cols="45" id="ids"></textarea></dd>
        			</dl>
			</fieldset>
			<fieldset class="action">
				<input type="hidden" name="sent" value="yes">
				<input type="hidden" name="id" value="">
				<input type="submit" name="submit" id="submit" value="Kollektion eintragen" class="submit" />
			</fieldset>
		</form>

		<?php

		}
	}

	//Loeschen einer Kollektion
	if($_GET['action'] == "kill"){

		if(!isset($_GET['confirm'])){

			//Daten aus DB lesen
			$stmt = "SELECT * FROM collections WHERE id='".$_GET['id']."'";
			$query = mysqli_query($link, $stmt);
			$out = mysqli_fetch_row($query);

			$confirm = md5($out[0]);

	?>
		Kollektion <span style="color:red;font-weight:bold">"<?php echo $out[1]?>"</span> wirklich l&ouml;schen?
		<button name="killbutton" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $out['0']?>&action=kill&confirm=<?php echo $confirm?>'" class="submit">Ja, l&ouml;schen</button>

	<?php
		} else {

			//Daten aus DB lesen
			$stmt = "SELECT * FROM collections WHERE id='".$_GET['id']."'";
			$query = mysqli_query($link, $stmt);
			$out = mysqli_fetch_row($query);

			$check = md5($out[0]);

			if($check == $_GET['confirm']){
				$stmt = "DELETE FROM collections WHERE id = ".$out[0]." LIMIT 1";
				$query = mysqli_query($link, $stmt);
				if (!$query) {
    				die('Datenbankfehler: ' . mysql_error());
				} else {
					echo "Kollektion wurde gel&ouml;scht. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='/".INSTALLPATH."/admin/index.php?sect=collections';\" class=\"submit\">Schlie&szlig;en</button>";
				}
			} else {
				echo "&Uuml;berpr&uuml;fung fehlgeschlagen. Kollektion nicht gel&ouml;scht. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='/".INSTALLPATH."/admin/index.php?sect=collections';\" class=\"submit\">Schlie&szlig;en</button>";
			}
		}
	}

?>


</td></tr>
</table>

</body>
</html>