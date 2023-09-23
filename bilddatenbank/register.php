<?php include("php/includes/start.inc.php"); ?>
<html>
<head>
	<title>Registrierung</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js" language="javascript"></script>
	<script type="text/javascript" src="/".<?php echo INSTALLPATH; ?>."/lib/jquery-validate/jquery.validate.pack.js" language="javascript"></script>
	<style>
		body {
			font-family: Verdana, Arial, Sans-Serif;
			font-size: 9pt;
		}

		label {
			width:120px;
			margin: 3px 0px 10px 0px;
			display: block;
			float: left;
			font-family: Verdana, Arial, Sans-Serif;
			font-size: 9pt;
		}

		label.error {
			width: 250px;
			float: right;
			color: #FF0000;
			font-size: 8pt;
		}

		p {
			clear:both;
		}

		input {
			margin: 0px 0px 10px 0px;
			font-family: Helvetica, Arial, Sans-Serif;
			font-size: 10pt;
			padding: 3px;
			width: 250px;

		}

		.button {
			color: #FFFFFF;
			background-color:#000;
			font-family:verdana,arial,helvetica,sans-serif;
			font-size:10px;
			font-weight:bold;
			text-align:center;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			border: 0px;
			cursor:pointer;
			cursor:hand;
			padding:3px 6px 3px 6px;
			line-height: 12px;
			height: 25px;
		}
	</style>
	<script language="javascript">
		$().ready(function(){
			$("#register").validate({
				rules: {
					name: {
						required: true,
						minlength: 3
					},
					redaktion: {
						required: true,
						minlength: 5
					},
					email: {
						required: true,
						email: true
					},
					telefon: {
						required: true,
						minlength: 5
					}
				},
				messages: {
					name: "Bitte einen Namen eingeben...",
					redaktion: "Bitte den Namen der Redaktion eingeben",
					email: "Bitte eine g&uuml;ltige Emailadresse eingeben",
					telefon: "Bitte eine Telefonnummer eingeben"
				}
			});
		});

	</script>
</head>
<body>
<div style="width:770px">

<?php

	if(isset($_POST['send']) && $_POST['send'] == "true"){

		require_once(DOCROOT.INSTALLPATH."/lib/PHPMailer/class.phpmailer.php");

		$mail = new PHPMailer();

		$mailout  = "Registrierungsanfrage von ".$_POST['name']."\n";
		$mailout .= "Email-Adresse ".$_POST['email']."\n";
		$mailout .= "Redaktion ".$_POST['redaktion']."\n";
		$mailout .= "Telefon ".$_POST['telefon']."\n";
		$mailout .= "\n\n----------------------------------\n";

		$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

		$mailout .= "Remote-IP: " . $_SERVER['REMOTE_ADDR'] . "\r\n";
		$mailout .= "Remote-Host: " . $hostname . "\r\n";
		$mailout .= "Remote-Browser: " . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
		$mailout .= "Uhrzeit: " . date("d.m.Y, H:i:s") . "\r\n";

		$body = $mailout;

		$mail->AddReplyTo($CONFIG['email'],$CONFIG['name']);
		$mail->SetFrom($CONFIG['sendemail'], $CONFIG['name']);
		$mail->AddAddress($CONFIG['email'], $CONFIG['name']);
		$mail->Subject = "Registrierungsanfrage";
		$mail->Body = $mailout;

		if(!$mail->Send()) {
			echo "Tut uns leid, die Email konnte nicht zugestellt werden. Bitte wenden Sie sich an den Administrator: " . $mail->ErrorInfo;
		} else {
			echo "Ihre Anfrage wurde gesendet, herzlichen Dank! <button onclick=\"self.parent.tb_remove();\" class=\"button\">Schlie&szlig;en</button>";
		}

	} else {
?>

	<h3 style="margin:20px 0 0 50px;">Registrierung beantragen:</h3>
	<p style="margin:10px 50px 0 50px;">Um die volle Funktionalit&auml;t der Datenbank nutzen zu k&ouml;nnen, m&uuml;ssen Sie sich registrieren lassen. Eine Registrierung ist nur f&uuml;r gewerbliche Kunden m&ouml;glich.</p>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="register" name="register">
	<fieldset name="register" style="margin:20px 50px 50px 50px;border:none;background-color:#ebebeb;padding:20px">
		<p><label for="name">Name:</label><input name="name" id="name" type="text"></p>
		<p><label for="redaktion">Redaktion:</label><input name="redaktion" id="redaktion" type="text"></p>
		<p><label for="email">E-Mail:</label><input name="email" id="email" type="text"></p>
		<p><label for="telefon">Telefon:</label><input name="telefon" id="telefon" type="text"></p>
		<p><input type="hidden" name="send" value="true">
		<input name="submit" id="submit" type="submit" value="Absenden" class="button"></p>

		<p><small><strong>Hinweis:</strong> Ihre Daten werden nur f&uuml;r die Bearbeitung der Registrierung verwendet.</small></p>
	</fieldset>
	</form>

<?php include("php/includes/analytics.inc.php"); ?>
<?php } ?>

</div>
</body>
</html>