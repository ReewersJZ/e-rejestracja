<?php

// Rezerwacja wybranego terminu wizyty z potwierdzeniem mailowym

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

$update_selected_term = new ClTerms($pdo);
$check_user_terms = new ClTerms($pdo);
$user_saved_term = new ClTerms($pdo);
$clinic_saved_term = new Clinics($pdo);
$user_vaccines = new ClTerms($pdo);

$select_term_clterms_id = $_POST['clterms_id'];
$clterms_status = "zajety";
$clterms_confirmed = "";

if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != "" && isset($_SESSION['user_mail']) && $_SESSION['user_mail'] != ""){

    $check_user_terms->checkUserTerms($_SESSION['user_id'], $clterms_confirmed, "zajety");
    if($check_user_terms->getError()){
        $TRESC = $update_selected_term->getErrorDescription();
        include_once 'szablony/witryna.php';
        exit();
    }
    elseif(count($check_user_terms->check_user_terms_array) >= 1){

            require_once 'vaccines_user.php'; 

            $KOMUNIKAT  = "Umówienie nowej wizyty niemożliwe - usuń aktualnie zarezerwowany termin a następnie wybierz nowy.";
            $TRESC= array();
            $TRESC[0]="szablony/main_user.php";
            include_once 'szablony/witryna.php';
    }
    else{
            $update_selected_term->updateTermUser($select_term_clterms_id, $_SESSION['user_id'], $clterms_status);

            if($update_selected_term->getError()){
                    $TRESC = $update_selected_term->getErrorDescription();
                    include_once 'szablony/witryna.php';
                    exit();
                }
            else{
                require_once 'vaccines_user.php'; 

            // wysyłka maila z potwierdzeniem wybrania terminu

                $from  = "From: ".$postFrom." \r\n";
                $from .= 'MIME-Version: 1.0'."\r\n";
                $from .= 'Content-type: text/html; charset=UTF-8'."\r\n";
                $adress = $_SESSION['user_mail'];
                $title = "Potwierdzenie rejestracji";
                $sentMessage = "<html>
                <head>
                </head>
                <body>
                <h4>Potwierdzenie rejestarcji na szczepienie</h4>
                <p>Termin wizyty: <b>".$check_user_terms_array['clterms_date']."</b></p>
                <p>Godzina: ".$check_user_terms_array['clterms_hour_from']."</p>
                <p>Przychodnia: ".$clinic['clinic_name'].", ".$clinic['clinic_city'].", ul. ".$clinic['clinic_street']."</p><br>
                <p>Jeśli nie możesz skorzystać z wizyty w wybranym terminie prosimy o usunięcie wizyty w aplikacji lub poinformowanie przychodni telefonicznie.</p>
                <p>Yulia, Justyna i Marcin</p>";
                
                mail($adress, $title, $sentMessage, $from);

                // koniec wysyłki maila

                $KOMUNIKAT  = "Zapisano wybrany termin";
                $TRESC= array();
                $TRESC[0]="szablony/main_user.php";
                include_once 'szablony/witryna.php';
            }
    }
}
else{
    $KOMUNIKAT = "Błąd logowania użytkownika.";
    $TRESC= array();
    unset($_SESSION['username']);
    $TRESC[0]="szablony/logowanie.php";
    include_once 'szablony/witryna.php';
}




