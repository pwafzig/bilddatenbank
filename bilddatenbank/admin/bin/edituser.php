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
	if(isset($_GET['action']) && $_GET['action'] == "edit"){

		if(isset($_POST['sent']) && $_POST['sent'] == "yes"){

			if($_POST['password'] != "faked"){
				$stmt = "UPDATE users SET login = '".$_POST['login']."', passwort = MD5( '".$_POST['password']."'), name = '".$_POST['name']."', prename = '".$_POST['prename']."', organisation = '".$_POST['organisation']."', email = '".$_POST['email']."', resolution = '".$_POST['resolution']."' WHERE id = ".$_POST['id']." LIMIT 1";
			} else {
				$stmt = "UPDATE users SET login = '".$_POST['login']."', name = '".$_POST['name']."', prename = '".$_POST['prename']."', organisation = '".$_POST['organisation']."', email = '".$_POST['email']."', resolution = '".$_POST['resolution']."' WHERE id = ".$_POST['id']." LIMIT 1";
			}

			$query = mysqli_query($link, $stmt);
			if (!$query) {
    			die('Datenbankfehler: ' . mysqli_error($link));
			} else {
				echo "Die Daten wurden korrekt in der Datenbank ge&auml;ndert. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='/".INSTALLPATH."/admin/index.php?sect=benutzer';\" class=\"submit\">Schlie&szlig;en</button>";
			}
		} else {

		//Benutzerdaten aus DB lesen
		$stmt = "SELECT * FROM users WHERE id='".$_GET['id']."'";
		$query = mysqli_query($link, $stmt);
		$out = mysqli_fetch_row($query);

		//Formular anzeigen	?>

		<script language="javascript">
			$().ready(function(){
				$("#binform").validate({
				rules: {
						name: {
							required: true,
							minlength: 3
						},
						password: {
							required: true,
							minlength: 5
						},
						confirm_password: {
							required: true,
							minlength: 5,
							equalTo: "#password"
						},
						email: {
							required: true,
							email: true
						},
						login: {
							required: true,
							minlength: 3
						}
					},
					messages: {
						name: "Bitte einen Namen eingeben, mindestens 3 Zeichen",
						password: {
							required: "Bitte ein Passwort eingeben",
							minlength: "Das Passwort muss mindestens 5 Zeichen lang sein"
						},
						confirm_password: {
							required: "Bitte das Passwort wiederholen",
							minlength: "Das Passwort muss mindestens 5 Zeichen lang sein",
							equalTo: "Die Passw&ouml;rter m&uuml;ssen &uuml;bereinstimmen"
						},
						email: "Bitte eine g&uuml;ltige Emailadresse eingeben",
						login: {
							required: "Bitte einen Login eingeben",
							minlength: "Der Login muss mindestens 3 Zeichen lang sein"
						}
					}
					});
				});

				$("#password").blur(function() {
					$("#confirm_password").valid();
				});
		</script>

		<form action="<?php echo $_SERVER['PHP_SELF']?>?action=edit" method="POST" name="edituser" id="binform">
			<fieldset>
				<legend>Benutzer bearbeiten</legend>
					<dl>
		        		<dt><label for="prename">Vorname:</label></dt>
        		    	<dd><input type="text" name="prename" id="prename" size="32" maxlength="128" value="<?php echo $out[4]?>" /></dd>
        			</dl>
					<dl>
		        		<dt><label for="name">Nachname:</label></dt>
        		    	<dd><input type="text" name="name" id="name" size="32" maxlength="128" value="<?php echo $out[3]?>" /></dd>
        			</dl>
					<dl>
        				<dt><label for="organisation">Organisation:</label></dt>
            			<dd><input type="text" name="organisation" id="organisation" size="32" maxlength="128" value="<?php echo $out[5]?>" /></dd>
        			</dl>
					<dl>
        				<dt><label for="email">Email:</label></dt>
            			<dd><input type="text" name="email" id="email" size="32" maxlength="128" value="<?php echo $out[6]?>" /></dd>
        			</dl>
        			<dl>
        				<dt><label for="lang">Sprache:</label></dt>
            			<dd><input type="radio" name="lang" id="lang" value="de" /> DE&nbsp;<input type="radio" name="lang" id="lang" value="en" /> EN</dd>
        			</dl>
					<dl>
        				<dt><label for="login">Login:</label></dt>
            			<dd><input type="text" name="login" id="login" size="32" maxlength="128" value="<?php echo $out[1]?>" /></dd>
        			</dl>
					<dl>
        				<dt><label for="password">Passwort:</label></dt>
            			<dd><input type="password" name="password" id="password" size="32" maxlength="128" value="faked" /></dd>
        			</dl>
					<dl>
        				<dt><label for="confirm_password">Passwort wiederholen*:</label></dt>
            			<dd><input type="password" name="confirm_password" id="confirm_password" size="32" maxlength="128" value="faked" /></dd>
        			</dl>
					<dl>
        				<dt><label for="highres">Download:</label></dt>
            			<dd><input type="radio" name="resolution" id="resolution" value="highres"<?php if($out[8] == "highres") echo " checked"; ?> /> Originale&nbsp;<input type="radio" name="resolution" id="resolution" value="lowres"<?php if($out[8] == "lowres") echo " checked"; ?> /> Web-Auflösung</dd>
        			</dl>         			
			</fieldset>
			<fieldset class="action">
				<input type="hidden" name="sent" value="yes">
				<input type="hidden" name="id" value="<?php echo $out[0]?>">
				<input type="submit" name="submit" id="submit" value="Benutzerdaten &auml;ndern" class="submit" />
			</fieldset>
		</form>

		* Nur ausf&uuml;llen, wenn das Passwort ge&auml;ndert werden soll. <?php

		}
	}

	//Neuanlegen eines Users
	if(isset($_GET['action']) && $_GET['action'] == "add"){

		if(isset($_POST['sent']) && $_POST['sent'] == "yes"){
			$stmt = "INSERT INTO users (id, login, passwort, name, prename, organisation, email, lang, resolution, downloads, timestamp, lastlogin) VALUES (NULL, '".$_POST['login']."', MD5('".$_POST['password']."'), '".$_POST['name']."', '".$_POST['prename']."', '".$_POST['organisation']."', '".$_POST['email']."', '".$_POST['lang']."', '".$_POST['resolution']."', '0', CURRENT_TIMESTAMP, CURRENT_TIME())";

			$query = mysqli_query($link, $stmt);
			if (!$query) {
    			die('Datenbankfehler: ' . mysqli_error($link));
			} else {
				echo "Der Benutzer wurde korrekt in die Datenbank eingef&uuml;gt. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='/".INSTALLPATH."/admin/index.php?sect=benutzer';\" class=\"submit\">Schlie&szlig;en</button>";
			}
		} else {

		//Formular anzeigen	?>
		<script type="text/javascript">
		<!--
		    $(document).ready(function () {
		        // Username validation logic
		        var validateUsername = $('#validateUsername');
		        $('#login').keyup(function () {
		            // cache the 'this' instance as we need access to it within a setTimeout, where 'this' is set to 'window'
		            var t = this;

		            // only run the check if the login has actually changed - also means we skip meta keys
		            if (this.value != this.lastValue) {

		                // the timeout logic means the ajax doesn't fire with *every* key press, i.e. if the user holds down
		                // a particular key, it will only fire when the release the key.

		                if (this.timer) clearTimeout(this.timer);

		                // show our holding text in the validation message space
		                validateUsername.removeClass('error').html('&nbsp;<img src="/<?php echo INSTALLPATH; ?>/admin/images/ajax-loader.gif" height="16" width="16" /> Pr&uuml;fe Verf&uuml;gbarkeit...');

		                // fire an ajax request in 1/5 of a second
		                this.timer = setTimeout(function () {
		                    $.ajax({
		                        url: '/<?php echo INSTALLPATH; ?>/admin/bin/checklogin.php',
		                        data: 'action=check&username=' + t.value,
		                        dataType: 'json',
		                        type: 'post',
		                        success: function (j) {
		                            // put the 'msg' field from the $resp array from check_username (php code) in to the validation message
		                            validateUsername.html(j.msg);
		                        }
		                    });
		                }, 200);

		                // copy the latest value to avoid sending requests when we don't need to
		                this.lastValue = this.value;
		            }
		        });

		        // avatar validation
		        // we use keyup *and* change because
		        $('#avatar').keyup(function () {
		            var t = this;
		            clearTimeout(this.timer);
		            this.timer = setTimeout(function () {
		                if (t.value == t.current) {
		                    return true;
		                }

		                var preview = $('#validateAvatar').html('&nbsp;<img src="/<?php echo INSTALLPATH; ?>/admin/images/ajax-loader.gif" height="16" width="16" /> trying to load avatar...');
		                var i = new Image();

		                clearTimeout(t.timeout);

		                if (t.value == '') {
		                    preview.html('');
		                } else {
		                    i.src = t.value;
		                    i.height = 32;
		                    i.width = 32;
		                    i.className = 'avatar';

		                    // set a timeout of x seconds to load the image, otherwise, show the fail message
		                    t.timeout = setTimeout(function () {
		                        preview.html('Image could not be loaded.');
		                        i = null;
		                    }, 3000);

		                    // if the dummy image holder loads, we'll show the image in the validation space,
		                    // but importantly, we clear the timer set above
		                    i.onload = function () {
		                        clearTimeout(t.timeout);
		                        preview.empty().append(i);
		                        i = null;
		                    };
		                }

		                t.current = t.value;
		            }, 250);
		        }).change(function () {
		            $(this).keyup(); // call the keyup function
		        });
		    });
		//-->
		</script>
		<script language="javascript">
			$().ready(function(){
				$("#binform").validate({
				rules: {
						name: {
							required: true,
							minlength: 3
						},
						password: {
							required: true,
							minlength: 5
						},
						confirm_password: {
							required: true,
							minlength: 5,
							equalTo: "#password"
						},
						email: {
							required: true,
							email: true
						}
					},
					messages: {
						name: "Bitte einen Namen eingeben (mind. 3 Zeichen)",
						password: {
							required: "Bitte ein Passwort eingeben",
							minlength: "Das Passwort muss mindestens 5 Zeichen lang sein"
						},
						confirm_password: {
							required: "Bitte das Passwort wiederholen",
							minlength: "Das Passwort muss mindestens 5 Zeichen lang sein",
							equalTo: "Die Passw&ouml;rter m&uuml;ssen &uuml;bereinstimmen"
						},
						email: "Bitte eine g&uuml;ltige Emailadresse eingeben"
					}
					});
				});

				$("#password").blur(function() {
					$("#confirm_password").valid();
				});
		</script>

		<form action="<?php echo $_SERVER['PHP_SELF']?>?action=add" method="POST" name="edituser" id="binform">
			<fieldset>
				<legend>Benutzer anlegen</legend>
					<dl>
		        		<dt><label for="prename">Vorname:</label></dt>
        		    	<dd><input type="text" name="prename" id="prename" size="32" maxlength="128" value="" /></dd>
        			</dl>
					<dl>
		        		<dt><label for="name">Nachname:</label></dt>
        		    	<dd><input type="text" name="name" id="name" size="32" maxlength="128" value="" /></dd>
        			</dl>
					<dl>
        				<dt><label for="organisation">Organisation:</label></dt>
            			<dd><input type="text" name="organisation" id="organisation" size="32" maxlength="128" value="" /></dd>
        			</dl>
					<dl>
        				<dt><label for="email">Email:</label></dt>
            			<dd><input type="text" name="email" id="email" size="32" maxlength="128" value="" /></dd>
        			</dl>
					<dl>
        				<dt><label for="lang">Sprache:</label></dt>
            			<dd><input type="radio" name="lang" id="lang" value="de" /> DE&nbsp;<input type="radio" name="lang" id="lang" value="en" /> EN</dd>
        			</dl>
					<dl>
        				<dt><label for="login">Login:</label></dt>
            			<dd><input type="text" name="login" id="login" size="32" maxlength="128" value="" /><span id="validateUsername"></span></dd>
        			</dl>
					<dl>
        				<dt><label for="password">Passwort:</label></dt>
            			<dd><input type="password" name="password" id="password" size="32" maxlength="128" value=""/></dd>
        			</dl>
					<dl>
        				<dt><label for="confirm_password">Passwort wiederholen:</label></dt>
            			<dd><input type="password" name="confirm_password" id="confirm_password" size="32" maxlength="128" /></dd>
        			</dl>
					<dl>
        				<dt><label for="highres">Download:</label></dt>
            			<dd><input type="radio" name="resolution" id="resolution" value="highres" /> Originale&nbsp;<input type="radio" name="resolution" id="resolution" value="lowres" /> Web-Auflösung</dd>
        			</dl>        			
			</fieldset>
			<fieldset class="action">
				<input type="hidden" name="sent" value="yes">
				<input type="submit" name="submit" id="submit" value="Benutzer anlegen" class="submit" />
			</fieldset>
		</form>

		<?php

		}
	}

	//Loeschen eines Users
	if(isset($_GET['action']) && $_GET['action'] == "kill"){

		if(!isset($_GET['confirm'])){

			//Benutzerdaten aus DB lesen
			$stmt = "SELECT * FROM users WHERE id='".$_GET['id']."'";
			$query = mysqli_query($link, $stmt);
			$out = mysqli_fetch_row($query);

			$confirm = md5($out[1]);

	?>
		Benutzer <span style="color:red;font-weight:bold"><?php echo $out[3]?></span> wirklich l&ouml;schen?
		<button name="killbutton" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $out['0']?>&action=kill&confirm=<?php echo $confirm?>'" class="submit">Ja, l&ouml;schen</button>

	<?php
		} else {

			//Benutzerdaten aus DB lesen
			$stmt = "SELECT * FROM users WHERE id='".$_GET['id']."'";
			$query = mysqli_query($link, $stmt);
			$out = mysqli_fetch_row($query);

			$check = md5($out[1]);

			if($check == $_GET['confirm']){
				$stmt = "DELETE FROM users WHERE id = ".$out[0]." LIMIT 1";
				$query = mysqli_query($link, $stmt);
				if (!$query) {
    				die('Datenbankfehler: ' . mysqli_error($link));
				} else {
					echo "Benutzer wurde gel&ouml;scht. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='/".INSTALLPATH."/admin/index.php?sect=benutzer';\" class=\"submit\">Schlie&szlig;en</button>";
				}
			} else {
				echo "&Uuml;berpr&uuml;fung fehlgeschlagen. Benutzer nicht gel&ouml;scht. <button onclick=\"self.parent.tb_remove(); parent.window.location.href='/".INSTALLPATH."/admin/index.php?sect=benutzer';\" class=\"submit\">Schlie&szlig;en</button>";
			}
		}
	}

?>


</td></tr>
</table>

</body>
</html>