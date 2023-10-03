<?php include("../../php/includes/start.inc.php"); ?>
<?php

  	$ajaxcheck = "ok";
    $message = '';
    $error = array();

   	//DB-Connect, um vergebene Usernamen zu prüfen
   	$stmt = "SELECT login FROM users";
   	$query = mysqli_query($link, $stmt);

   	while($out = mysqli_fetch_array($query, MYSQLI_ASSOC)){
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
  if(isset($_POST['username'])){ 
    $username = mysqli_real_escape_string($link, $_POST['username']);

    echo json_encode(check_username($username));
    exit; // only print out the json version of the response
  }
?>
