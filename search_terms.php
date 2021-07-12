<?php

// Widok wyszukanych wolnych terminów - widok pacjenta

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

$free_terms = new ClTerms($pdo);
$user_saved_term = new ClTerms($pdo);
$clinic_saved_term = new Clinics($pdo);
$user_vaccines = new ClTerms($pdo);

$search_terms_clinic = $_POST['search_terms_clinic'];
$search_terms_dateFrom = $_POST['search_terms_dateFrom']; 
$search_terms_dateTo = $_POST['search_terms_dateTo'];

$clicked_options = [];
$clicked_true = TRUE;
$clicked_false = FALSE;
$search_terms_option = "search_terms_option";



if(isset($search_terms_dateFrom) && $search_terms_dateFrom != "" && isset($search_terms_dateTo) && $search_terms_dateTo != ""){

    require_once 'vaccines_user.php';

    for($i=1; $i<=3; $i++){
        $search_option_checked = $search_terms_option . $i;
        if(isset($_POST[$search_option_checked])){
            array_push($clicked_options, $clicked_true);
        }
        else{
            array_push($clicked_options, $clicked_false);
        }
    }

    if($clicked_options[0] == FALSE && $clicked_options[1] == FALSE && $clicked_options[2] == FALSE){
        $KOMUNIKAT = "Nie podano zakresu godzin";
        $TRESC= array();
        $TRESC[0]="szablony/main_user.php";
        include_once 'szablony/witryna.php';
    }

    $free_terms_to_choose = "";
    $free_terms_to_choose_all = "";

    for($b=0; $b<count($clicked_options); $b++){
        if($clicked_options[$b] == TRUE){
            if($b == 0){
                $search_terms_hour_from = "08:00:00";
                $search_terms_hour_to = "11:45:00"; 
            }
            elseif($b == 1){
                $search_terms_hour_from = "12:00:00";
                $search_terms_hour_to = "15:45:00"; 
            }
            elseif($b == 2){
                $search_terms_hour_from = "16:00:00";
                $search_terms_hour_to = "19:45:00"; 
            }
        
            $free_terms->getTerms($search_terms_clinic, $search_terms_dateFrom, $search_terms_dateTo, $search_terms_hour_from, $search_terms_hour_to);

            if($free_terms->getError()){
                    $TRESC = $free_terms->getErrorDescription();
                    include_once 'szablony/witryna.php';
                    exit();
                }
            else{
                if(count($free_terms->free_terms_array) > 0){
                    $free_terms_array = $free_terms->free_terms_array;
                    foreach ($free_terms_array as $free_term){

                        $free_terms_to_choose = $free_terms_to_choose . "
                        <div class='card-body card_body_item'>
                            <form class='terms_list' action='select_term_user.php' method='post'>
                                <input type='text' id='clterms_id' name='clterms_id' class='form-control' value='".$free_term['clterms_id']."' hidden>
                                
                                <div class='form-label-group mt-2'>
                                    <p>".$free_term['clinic_name']."</p>
                                </div>
                                <div class='form-label-group mt-2'>
                                    <p>".$free_term['clterms_date']."</p>
                                </div>
                                <div class='form-label-group mt-2'>
                                    <p>".$free_term['clterms_hour_from']."</p>
                                </div>
                                <div class='form_button'>
                                    <button class='btn btn-lg btn-primary btn-block' type='submit'>Wybierz</button>
                                </div>
                            </form>
                        </div>
                        <hr>
                        ";
                    }
                }
            }
        }
    }
    $free_terms_to_choose_all = $free_terms_to_choose_all . $free_terms_to_choose;
    if($free_terms_to_choose_all == ""){
        $FREE_TERMS = "Brak wyników dla podanych kryteriów wyszukiwania";
        $TRESC= array();
        $TRESC[0]="szablony/main_user.php";
        include_once 'szablony/witryna.php';
    }
    $FREE_TERMS = $free_terms_to_choose_all;
    $TRESC= array();
    $TRESC[0]="szablony/main_user.php";
    include_once 'szablony/witryna.php';
}
else{
    $KOMUNIKAT = "Nie podano zakresu dat";
    $TRESC= array();
    $TRESC[0]="szablony/main_user.php";
    include_once 'szablony/witryna.php';
}




