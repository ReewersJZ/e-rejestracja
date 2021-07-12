<?php

// Potwierdzenie odbycia wizyty, a tym samym potwierdzenie przyjęcia szczepionki przez pacjenta

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/ClTerms.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";

$clterms_id = $_POST['clterms_id'];
$clterms_confirmed = "wykonano";


$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->query('SET NAMES UTF8');
$pdo->query('SET CHARACTER SET UTF8');

$term_to_confirm = new ClTerms($pdo);

if(isset($clterms_id) && $clterms_id != ""){

    $term_to_confirm->updateConfirm($clterms_id, "wykonano");

    if($term_to_confirm->getError()){
        $TRESC = $term_to_confirm->getErrorDescription();
        include_once 'szablony/witryna.php';
        exit();
    }
    else{
        $KOMUNIKAT = "Termin szczepienia potwierdzony";
        $TRESC= array();
        $TRESC[0]="szablony/main_employees.php";
        include_once 'szablony/witryna.php';
    }  
}
else{
    $KOMUNIKAT = "Błąd logowania. zaloguj się ponownie.";
    $TRESC= array();
    $TRESC[0]="szablony/logowanie.php";
    include_once 'szablony/witryna.php';
}




