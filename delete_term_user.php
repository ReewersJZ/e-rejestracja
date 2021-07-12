<?php

// Usuwanie zarezerwowanego terminu wizyty

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/ClTerms.php';
require_once 'include/Clinics.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";
$USER_SAVED_TERM = "";
$USER_VACCINES = "";


$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->query('SET NAMES UTF8');
$pdo->query('SET CHARACTER SET UTF8');

$user_saved_term = new ClTerms($pdo);
$clinic_saved_term = new Clinics($pdo);
$user_vaccines = new ClTerms($pdo);
$delete_term = new ClTerms($pdo);

$delete_term_user = $_POST['delete_user_term_id'];
$clterms_status = "wolny";
$clterms_confirmed = "";


// Sprawdzanie czy termin chce usunąć pacjent
if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != "" && isset($_SESSION['username']) && $_SESSION['username'] != ""){

    $delete_term->updateTermUser($delete_term_user, "0", $clterms_status);
    if($delete_term->getError()){
        $TRESC = $update_selected_term->getErrorDescription();
        include_once 'szablony/witryna.php';
        exit();
    }
    else{
            require_once 'vaccines_user.php'; 
            $KOMUNIKAT  = "Usunięto termin wizyty";
            $TRESC= array();
            $TRESC[0]="szablony/main_user.php";
            include_once 'szablony/witryna.php';
    }
}
// Sprawdzanie czy termin chce usunąć pracownik
elseif(isset($_SESSION['empname']) && isset($_SESSION['emp_clinic'])){
    $delete_term->updateTermUser($delete_term_user, "0", $clterms_status);
    if($delete_term->getError()){
        $TRESC = $update_selected_term->getErrorDescription();
        include_once 'szablony/witryna.php';
        exit();
    }
    else{
            require_once 'vaccines_user.php'; 
            $KOMUNIKAT  = "Usunięto termin wizyty";
            $TRESC= array();
            $TRESC[0]="szablony/search_engine.php";
            include_once 'szablony/witryna.php'; 
    }
}
else{
    $KOMUNIKAT = "Błąd logowania użytkownika.";
    $TRESC= array();
    unset($_SESSION['username']);
    $TRESC[0]="szablony/logowanie.php";
    include_once 'szablony/witryna.php';
}




