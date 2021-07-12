<?php

// Pobieranie listy potwierdzonych wizyt - historii wizyt dla przychodni o id pasującym do pracownika który w niej pracuje

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/ClTerms.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";
$USERS_INFO = "";

$date_from = $_POST['raports_date_from'];
$date_to = $_POST['raports_date_to'];
$clinic_id = $_POST['raports_clinic_id'];

$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->query('SET NAMES UTF8');
$pdo->query('SET CHARACTER SET UTF8');

$raports_term = new ClTerms($pdo);

if(isset($date_from) && $date_from != "" && isset($date_to) && $date_to != ""){

    $raports_term->getBookedTermsRaports($date_from, $date_to, $clinic_id, "zajety", "wykonano");

    if($raports_term->getError()){
        $TRESC = $raports_term->getErrorDescription();
        include_once 'szablony/witryna.php';
        exit();
    }
    else{

        if(count($raports_term->raports_booked_terms_array) > 0){

            $raports_booked_terms_array = $raports_term->raports_booked_terms_array;

            $block_info_array = "<h5>Historia wizyt:</h5>";

            foreach($raports_booked_terms_array as $term){
            
                $block_info = 
                    "<div class='card-body card_body_item raports_items'>
                        <div class='form-label-group mt-2'>
                            <p>".$term['user_name']."</p>
                        </div>
                        <div class='form-label-group mt-2'>
                            <p>".$term['user_surname']."</p>
                        </div>
                        <div class='form-label-group mt-2'>
                            <p>".$term['user_pesel']."</p>
                        </div>
                            <div class='form-label-group mt-2'>
                            <p>".$term['clterms_date']."</p>
                        </div>
                        <div class='form-label-group mt-2'>
                            <p>".$term['clterms_hour_from']."</p>
                        </div>
                    </div><hr>
                    ";

                $block_info_array = $block_info_array . $block_info;
            }

            $USERS_INFO = $block_info_array;
            $TRESC= array();
            $TRESC[0]="szablony/raports.php";
            include_once 'szablony/witryna.php';

        }
        else{
            $USERS_INFO = "Brak danych";
            $TRESC= array();
            $TRESC[0]="szablony/raports.php";
            include_once 'szablony/witryna.php';
        }
    }
}
else{
    $KOMUNIKAT = "Nie uzupełniono zakresu dat";
    $TRESC= array();
    $TRESC[0]="szablony/raports.php";
    include_once 'szablony/witryna.php';
}




