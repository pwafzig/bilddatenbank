<?php include("../../php/includes/start.inc.php"); ?>
<?php

	/****** Macht nur Probleme, die Variablen werden nicht korrekt interpretiert ******
	//Ajax Check:
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
		$ajaxcheck = "ok";
	} else if (isset($_SERVER['X-Requested-With'])){
		$ajaxcheck = "ok";
	}
	 ****** Deswegen in der nächsten Zeile ein kleiner Override... ******/

	$ajaxcheck = "ok";
    $message = '';
    $error = array();

   	//DB-Connect, um vergebene Usernamen zu prüfen
   	$stmt = "SELECT login FROM users";
   	$query = mysql_query($stmt);

   	while($out = mysql_fetch_array($query)){
		$taken_usernames[] = $out['login'];
   	}

    function check_username($username) {
        global $taken_usernames;
        $resp = array();
        $username = trim($username);
        if (!$username) {
            $resp = array('ok' => false, 'msg' => "Ein Login muss eingegeben werden");
        } else if (!preg_match('/^[a-z0-9]+$/', $username)) {
            $resp = array('ok' => false, "msg" => "Ung&uuml;ltige Zeichen (nur Zahlen und/oder Buchstaben)");
        } else if (in_array($username, $taken_usernames)) {
            $resp = array("ok" => false, "msg" => "Der Login ist nicht verf&uuml;gbar");
        } else {
            $resp = array("ok" => true, "msg" => "");
        }

        return $resp;
    }

	// main submit logic
	echo json_encode(check_username($_REQUEST['username']));
	exit; // only print out the json version of the response

?>
