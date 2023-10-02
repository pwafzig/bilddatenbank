<?php include("php/includes/start.inc.php"); ?>
<?php

    if(isset($_POST['login']) && isset($_POST['passwort'])){

        $login      = stripslashes($_POST['login']);
        $passwort   = stripslashes($_POST['passwort']);

        $login = mysqli_real_escape_string($link, $login);
        $stmt_login = "SELECT * FROM users WHERE login = '".$login."'";
        $query_login = mysqli_query($link, $stmt_login);
        $result_login = mysqli_fetch_array($query_login, MYSQLI_ASSOC);

        if(md5($passwort) == $result_login['passwort']){

            $_SESSION['login']          =   $login;
            $_SESSION['name']           =   $result_login['name'];
            $_SESSION['prename']           =   $result_login['prename'];
            $_SESSION['downloads']      =   $result_login['downloads'];
            $_SESSION['organisation']   =   $result_login['organisation'];
            $_SESSION['email']          =   $result_login['email'];
            $_SESSION['resolution']     =   $result_login['resolution'];
            $_SESSION['lightboxids']    =   "";

            $stmt_log = "UPDATE users SET lastlogin = NOW( ) WHERE login = '".$login."' LIMIT 1";
            mysqli_query($link, $stmt_log);

            header("Location:".$_POST['refer']);

        } else {
            header("Location:/".INSTALLPATH."/index.php?err=12");
            exit;
        }
    } else {
    	exit;
    }


?>