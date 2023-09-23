<?php include("../php/includes/start.inc.php"); ?>
<?php
    //Sprachvariable retten, bevor die Session gekillt wird
    $lang = $_SESSION['lang'];
    
    //Session killen
    @session_destroy();
    
    //Neue Session starten und Sprachvaraible setzen
    @session_start();
    $_SESSION['lang'] = $lang;
    
    //ZurŸck zur Bilddatenbank
    header("Location:/".INSTALLPATH."/index.php");
    
    //Skript sauber beenden
    exit;
?>