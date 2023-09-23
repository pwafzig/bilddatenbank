<?php
    if(!isset($_SESSION[adminlogin])){
   	   	exit;
   	}
   	
	if(isset($_POST['lastchanges']) && $_POST['lastchanges'] != ""){

		foreach ($_POST as $key => $value) {
		    mysql_query("UPDATE konfig SET `key` = '$value' WHERE value = '$key' LIMIT 1");
		}

		echo "<span style=\"color:green\">Änderungen wurden gespeichert</span><br /><br />";

	}
	//Alle Werte aus der Konfigurationstabelle lesen

	$stmt = "SELECT * FROM konfig";
	$query = mysql_query($stmt);

	while($out = mysql_fetch_array($query)){
		$$out[1] = $out[2];
	}

	$anzthumbs_options = "";

	for($i=1;$i<=60;$i++){
		if($i == $anzthumbs){
			$anzthumbs_options .= "<option value=\"".$i."\" selected>".$i."</option>";
		} else {
			$anzthumbs_options .= "<option value=\"".$i."\">".$i."</option>";
		}
	}

	$anznewfiles_options = "";

	for($i=1;$i<=60;$i++){
		if($i == $anznewfiles){
			$anznewfiles_options .= "<option value=\"".$i."\" selected>".$i."</option>";
		} else {
			$anznewfiles_options .= "<option value=\"".$i."\">".$i."</option>";
		}
	}

	$maxfiles_options = "";

	for($i=100;$i<=500;$i+=50){
		if($i == $maxfiles){
			$maxfiles_options .= "<option value=\"".$i."\" selected>".$i."</option>";
		} else {
			$maxfiles_options .= "<option value=\"".$i."\">".$i."</option>";
		}
	}

?>
<table id="admintable" border="1" width="100%">
	<thead>
		<tr>
			<th>
			<strong>Konfiguration</strong>
			</th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td>
		<form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>" method="POST" id="konfigform">
			<fieldset>
				<legend>Allgemeine Einstellungen</legend>
				<dl>
					<dt><label for="name">Name:</label></dt>
				   	<dd><input type="text" name="name" id="name" size="32" maxlength="128" value="<?php echo $name?>" /></dd>
        		</dl>
				<dl>
					<dt><label for="firma">Firma:</label></dt>
				   	<dd><input type="text" name="firma" id="firma" size="32" maxlength="128" value="<?php echo $firma?>" /></dd>
        		</dl>
				<dl>
					<dt><label for="description">Beschreibung:</label></dt>
				   	<dd><textarea name="description" id="description"><?php echo $description?></textarea></dd>
        		</dl>
				<dl>
					<dt><label for="keywords">Schlagworte:</label></dt>
				   	<dd><textarea name="keywords" id="keywords"><?php echo $keywords?></textarea></dd>
        		</dl>
				<dl>
					<dt><label for="besitzer">Besitzer:</label></dt>
				   	<dd><input type="text" name="besitzer" id="besitzer" size="32" maxlength="128" value="<?php echo $besitzer?>" /></dd>
        		</dl>
				<dl>
					<dt><label for="email">Admin-Email:</label></dt>
				   	<dd><input type="text" name="email" id="email" size="32" maxlength="128" value="<?php echo $email?>" /></dd>
        		</dl>
				<dl>
					<dt><label for="sendemail">Versender-Email:</label></dt>
				   	<dd><input type="text" name="sendemail" id="sendemail" size="32" maxlength="128" value="<?php echo $sendemail?>" /></dd>
        		</dl>
				<dl>
					<dt><label for="telnr">Telefonnummer:</label></dt>
				   	<dd><input type="text" name="telnr" id="telnr" size="32" maxlength="128" value="<?php echo $telnr?>" /></dd>
        		</dl>
        	</fieldset>

			<fieldset>
				<legend>Texte</legend>
				<dl>
					<dt><label for="impressum_text">Text Impressum:</label></dt>
				   	<dd><textarea name="impressum_text" id="impressum_text" rows="12"><?php echo $impressum_text?></textarea></dd>
        		</dl>
				<dl>
					<dt><label for="mailer_text">Text Bildzusendung:</label></dt>
				   	<dd><textarea name="mailer_text" id="mailer_text" rows="12"><?php echo $mailer_text?></textarea></dd>
        		</dl>
        	</fieldset>

			<fieldset>
				<legend>Systemkonfiguration</legend>
        		<dl>
					<dt><label for="password">Admin-Passwort:</label></dt>
				   	<dd><input type="text" name="password" id="password" size="32" maxlength="128" value="<?php echo $password?>" /></dd>
        		</dl>
        		<dl>
					<dt><label for="robots">Suchmaschinenzugriff:</label></dt>
				   	<dd><input type="radio" name="robots" id="robots" value="yes" style="width:15px" <?php if($robots == "yes") echo "checked " ?>/> Ja&nbsp;&nbsp;&nbsp;<input type="radio" name="robots" id="robots" value="no" style="width:15px" <?php if($robots == "no") echo "checked " ?>/> Nein </dd>
        		</dl>
        		<dl>
					<dt><label for="anzthumbs">Anzahl der Thumbnails pro Seite:</label></dt>
				   	<dd><select name="anzthumbs" id="anzthumbs" style="width:60px"><?php echo $anzthumbs_options; ?></select></dd>
        		</dl>
        		<dl>
					<dt><label for="anznewfiles">Anzahl der neuen Einträge auf der Startseite:</label></dt>
				   	<dd><select name="anznewfiles" id="anznewfiles" style="width:60px"><?php echo $anznewfiles_options; ?></select></dd>
        		</dl>
        		<dl>
					<dt><label for="anznewfiles">Maximale Anzahl von Bildergebnissen:</label></dt>
				   	<dd><select name="maxfiles" id="maxfiles" style="width:60px"><?php echo $maxfiles_options; ?></select></dd>
        		</dl>
        	</fieldset>


			<fieldset>
				<legend>Änderungen speichern</legend>
				<dl>
					<dt><label for="schriftart">Alle Änderungen speichern:</label></dt>
				   	<dd><input type="submit" size="32" value="Ja, jetzt speichern" /></dd>
        		</dl>
        	</fieldset>
		</td>
	</tr>

	</tbody>
	<input type="hidden" name="lastchanges" value="<?php echo date("Y-m-d H:i:s"); ?>">
	</form>
</table>
