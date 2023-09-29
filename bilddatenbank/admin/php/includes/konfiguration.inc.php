<?php
	if(isset($_POST['lastchanges']) && $_POST['lastchanges'] != ""){

		foreach ($_POST as $key => $value) {
		    mysqli_query($link, "UPDATE konfig SET `key` = '$value' WHERE value = '$key' LIMIT 1");
		}	
		
		echo "<span style=\"color:green\">Änderungen wurden gespeichert</span><br /><br />";
	
	} 	
	//Alle Werte aus der Konfigurationstabelle lesen

	mysqli_query($link, "SET CHARACTER SET 'utf8'");
	$stmt = "SELECT * FROM konfig";
	$query = mysqli_query($link, $stmt);

	while($out = mysqli_fetch_array($query)){
		${$out['1']} = $out['2'];
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
		<form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" method="POST" id="konfigform">
			<fieldset>
				<legend>Allgemeine Einstellungen</legend>
				<dl>
					<dt><label for="name">Name:</label></dt>
				   	<dd><input type="text" name="name" id="name" size="32" maxlength="128" value="<?php echo $name; ?>" /></dd>
        		</dl>
				<dl>
					<dt><label for="description">Beschreibung:</label></dt>
				   	<dd><textarea name="description" id="description"><?php echo $description; ?></textarea></dd>
        		</dl>
				<dl>
					<dt><label for="firma">Firma:</label></dt>
				   	<dd><input type="text" name="firma" id="firma" size="32" maxlength="128" value="<?php echo $firma; ?>" /></dd>
        		</dl>
				<dl>
					<dt><label for="besitzer">Besitzer:</label></dt>
				   	<dd><input type="text" name="besitzer" id="besitzer" size="32" maxlength="128" value="<?php echo $besitzer; ?>" /></dd>
        		</dl>
				<dl>
					<dt><label for="email">Email:</label></dt>
				   	<dd><input type="text" name="email" id="email" size="32" maxlength="128" value="<?php echo $email; ?>" /></dd>
        		</dl>
        	</fieldset>
        	
			<fieldset>
				<legend>Texte</legend>
				<dl>
					<dt><label for="impressum_text">Text Impressum:</label></dt>
				   	<dd><textarea name="impressum_text" id="impressum_text" rows="12"><?php echo $impressum_text; ?></textarea></dd>
        		</dl>
				<dl>
					<dt><label for="mailer_text">Mail Impressum:</label></dt>
				   	<dd><textarea name="mailer_text" id="mailer_text" rows="12"><?php echo $mailer_text; ?></textarea></dd>
        		</dl>
        		<dl>
					<dt><label for="telnr">Telefonnummer:</label></dt>
				   	<dd><input type="text" name="telnr" id="telnr" size="32" maxlength="128" value="<?php echo $telnr; ?>" /></dd>
        		</dl>
        		<dl>
					<dt><label for="keywords">Keywords (kommagetrennt):</label></dt>
				   	<dd><input type="text" name="keywords" id="keywords" size="32" maxlength="256" value="<?php echo $keywords; ?>" /></dd>
        		</dl>
        	</fieldset>        	

			<fieldset>
				<legend>Systemkonfiguration</legend>
				<dl>
					<?php if((!isset($_SERVER['DOCUMENT_ROOT'])) OR ($_SERVER['DOCUMENT_ROOT'] == "")){ ?>
					<dt><label for="abspath">Absoluter Pfad zum Hauptverzeichnis:</label></dt>
				   	<dd><input type="text" name="abspath" id="abspath" size="32" maxlength="128" value="<?php echo $abspath; ?>" /></dd>
				   	<?php } else { ?>
					<dt><label for="abspath">Absoluter Pfad zum Hauptverzeichnis:</label></dt>
				   	<dd><?php echo $_SERVER['DOCUMENT_ROOT']; ?><input type="hidden" name="abspath" id="abspath" value="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>"></dd>	   	
				   	<?php }?>
        		</dl>
        		<dl>
					<dt><label for="password">Admin-Passwort:</label></dt>
				   	<dd><input type="password" name="password" id="password" size="32" maxlength="128" value="<?php echo $password; ?>" /></dd>
        		</dl>
        		<dl>
					<dt><label for="sendemail">Versand Email:</label></dt>
				   	<dd><input type="text" name="sendemail" id="sendemail" size="32" maxlength="128" value="<?php echo $sendemail; ?>" /></dd>
        		</dl>
        		<dl>
					<dt><label for="smtphost">SMTP Host:</label></dt>
				   	<dd><input type="text" name="smtphost" id="smtphost" size="32" maxlength="128" value="<?php echo $smtphost; ?>" /></dd>
        		</dl>
        		<dl>
					<dt><label for="smtplogin">SMTP User:</label></dt>
				   	<dd><input type="text" name="smtplogin" id="smtplogin" size="32" maxlength="128" value="<?php echo $smtplogin; ?>" /></dd>
        		</dl>
        		<dl>
					<dt><label for="smtppass">SMTP Passwort:</label></dt>
				   	<dd><input type="password" name="smtppass" id="smtppass" size="32" maxlength="128" value="<?php echo $smtppass; ?>" /></dd>
        		</dl>
        		<dl>
					<dt><label for="smtpport">SMTP Port:</label></dt>
				   	<dd><input type="text" name="smtpport" id="smtpport" size="32" maxlength="128" value="<?php echo $smtpport; ?>" /></dd>
        		</dl>
        		<dl>
					<dt><label for="robots">Suchmaschinenzugriff:</label></dt>
				   	<dd><input type="radio" name="robots" id="robots" value="yes" style="width:15px" <?php if($robots == "yes") echo "checked " ?>/> Ja&nbsp;&nbsp;&nbsp;<input type="radio" name="robots" id="robots" value="no" style="width:15px" <?php if($robots == "no") echo "checked " ?>/> Nein </dd>
        		</dl> 		         		       		
        	</fieldset>

			<fieldset>
				<legend>Schrift- und Farbeinstellungen</legend>
				<dl>
					<dt><label for="schriftart">Systemschriftart:</label></dt>
				   	<dd><input type="text" name="schriftart" id="schriftart" size="32" maxlength="128" value="<?php echo $schriftart; ?>" /></dd>
        		</dl>
				<dl>
					<dt><label for="vordergrund">Vordergrundfarbe:</label></dt>
				   	<dd><input type="text" name="vordergrund" id="vordergrund" size="32" maxlength="128" value="<?php echo $vordergrund; ?>" /></dd>
        		</dl>
				<dl>
					<dt><label for="hintergrund">Hintergrundfarbe:</label></dt>
				   	<dd><input type="text" name="hintergrund" id="hintergrund" size="32" maxlength="128" value="<?php echo $hintergrund; ?>" /></dd>
        		</dl>
				<dl>
					<dt><label for="kontrast">Kontrastfarbe:</label></dt>
				   	<dd><input type="text" name="kontrast" id="kontrast" size="32" maxlength="128" value="<?php echo $kontrast; ?>" /></dd>
        		</dl>
				<dl>
					<dt><label for="logo">Eigenes Logo (Pfad):</label></dt>
				   	<dd><input type="text" name="logo" id="logo" size="32" maxlength="128" value="<?php echo $logo; ?>" /></dd>
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
