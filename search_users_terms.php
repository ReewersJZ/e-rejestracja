<?php

// Widok pacjentów, którzy są umówieni na wizytę w bieżącym dniu do przychodni zgodnej z zalogowanym pracownikiem

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/ClTerms.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";
$USERS_TERMS = "";
$today = date("Y-m-d");
$clterms_status = "zajety";
$clterms_confirmed = "";


$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->query('SET NAMES UTF8');
$pdo->query('SET CHARACTER SET UTF8');

$users_terms = new ClTerms($pdo);

if(isset($_SESSION['emp_clinic']) && $_SESSION['emp_clinic'] != ""){

    $users_terms->getBookedTerms($_SESSION['emp_clinic'], $today, $clterms_status, $clterms_confirmed);
    if($users_terms->getError()){
        $TRESC = $users_terms->getErrorDescription();
        include_once 'szablony/witryna.php';
        exit();
    }
    else{
        if(count($users_terms->booked_terms_array) > 0){
            $booked_terms_array = $users_terms->booked_terms_array;
            $booked_terms_list = "";
            $all_booked_terms = "";
            foreach ($booked_terms_array as $booked_term){

                $booked_terms_list = $booked_terms_list . "
                <div class='card-body card_body_item'>
                    <form class='terms_list' action='edit_user_term.php' method='post'>
                        <input type='text' id='clterms_id' name='clterms_id' class='form-control' value='".$booked_term['clterms_id']."' hidden>
                        <input type='text' id='user_id' name='user_id' class='form-control' value='".$booked_term['user_id']."' hidden>

                        <div class='form-label-group mt-2'>
                            <p>".$booked_term['clterms_date']."</p>
                        </div>
                        <div class='form-label-group mt-2'>
                            <p>".$booked_term['clterms_hour_from']."</p>
                        </div>
                        <div class='form-label-group mt-2'>
                            <p>".$booked_term['user_name']."</p>
                        </div>
                        <div class='form-label-group mt-2'>
                            <p>".$booked_term['user_surname']."</p>
                        </div>
                        <div class='form-label-group mt-2'>
                            <p>".$booked_term['user_pesel']."</p>
                        </div>
                            <div class='form-label-group mt-2'>
                            <p>".$booked_term['user_mail']."</p>
                        </div>
                        <div class='form-label-group mt-2'>
                            <p>".$booked_term['user_phone']."</p>
                        </div>

                        <div class='form_button'>
                            <button class='btn btn-lg btn-primary btn-block' type='submit'>Potwierdz</button>
                        </div>
                    </form>
                </div><hr>
                ";
                }
            $all_booked_terms = $all_booked_terms . $booked_terms_list;
            $USERS_TERMS = $all_booked_terms;
            $TRESC= array();
            $TRESC[0]="szablony/main_employees.php";
            include_once 'szablony/witryna.php';
        }
        else{

        $USERS_TERMS = "Brak wyników";
        $TRESC= array();
        $TRESC[0]="szablony/main_employees.php";
        include_once 'szablony/witryna.php';
        }
    }  
}
else{
    $KOMUNIKAT = "Błąd logowania. zaloguj się ponownie.";
    $TRESC= array();
    $TRESC[0]="szablony/logowanie.php";
    include_once 'szablony/witryna.php';
}




