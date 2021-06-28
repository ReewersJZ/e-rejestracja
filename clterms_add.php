<?php

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/ClTerms.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";

function createArrayOfTime($hour_from, $hour_to){
    $array_hours = [];
    for($k=$hour_from; $k<=$hour_to; $k++){
        $array_time = [];
        $array_minutes = ["00", "15", "30", "45"];
        for($j=0; $j<=3; $j++){
            $new_hour = $k .":". $array_minutes[$j] . ":00";
            array_push($array_hours, $new_hour);
        }
    }
    return $array_hours;
}


$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$new_terms = new ClTerms($pdo);
$check_terms = new ClTerms($pdo);

$clterms_date = $_POST['clterms_date'];
$clterms_clinic = $_POST['clterms_clinic']; 
$clterms_status = "wolny";
$clicked_options = [];
$clicked_true = TRUE;
$clicked_false = FALSE;
$clterms_option = "clterms_option";


if(isset($clterms_date) && $clterms_date != ""){
    for($i=1; $i<=3; $i++){
        $clterms_option_checked = $clterms_option . $i;
        if(isset($_POST[$clterms_option_checked])){
            array_push($clicked_options, $clicked_true);
        }
        else{
            array_push($clicked_options, $clicked_false);
        }
    }
    if($clicked_options[0] == FALSE && $clicked_options[1] == FALSE && $clicked_options[2] == FALSE){
        $KOMUNIKAT = "Nie podano zakresu godzin";
        $TRESC= array();
        $TRESC[0]="szablony/main_employees.php";
        include_once 'szablony/witryna.php';
    }
}
else{
    $KOMUNIKAT = "Nie podano daty";
    $TRESC= array();
    $TRESC[0]="szablony/main_employees.php";
    include_once 'szablony/witryna.php';
}


for($b=0; $b<count($clicked_options); $b++){
    if($clicked_options[$b] == TRUE){
        if($b == 0){
            $array_hours = createArrayOfTime(8, 11);
            $if_term_egsist = $array_hours[0];
        }
        elseif($b == 1){
            $array_hours = createArrayOfTime(12, 15);
            $if_term_egsist = $array_hours[0];
        }
        elseif($b == 2){
            $array_hours = createArrayOfTime(16, 19);
            $if_term_egsist = $array_hours[0];
        }

        try{
            $check_terms->checkTerms($clterms_clinic, $clterms_date, $if_term_egsist);
            if($check_terms->getError()){
                    $KOMUNIKAT  = $check_terms->getErrorDescription();
                    include_once 'szablony/witryna.php';
                    exit();
                }
            elseif(count($check_terms->check_free_terms_array) > 0){
                $KOMUNIKAT = "Wskazany termin już istnieje";
            }
            else{
                for($l=0; $l<count($array_hours); $l++){
                    try{
                            $insert_hour = date($array_hours[$l]);
                            $new_terms->insertCLTerm($clterms_clinic, $clterms_date, $insert_hour, $clterms_status);
                            if($new_terms->getError()){
                                    $KOMUNIKAT  = $new_terms->getErrorDescription();
                                    include_once 'szablony/witryna.php';
                                    exit();
                                }
                            
                            $KOMUNIKAT = "Dodano wolne terminy";
                    }
                    
                    catch(PDOException $e){
                        $TRESC.= 'Wystapil blad biblioteki PDO: ' . $e->getMessage();
                    }
                    
                }
            }
        }
        catch(PDOException $e){
            $TRESC.= 'Wystapil blad biblioteki PDO: ' . $e->getMessage();
        }
    }

}


$TRESC= array();
$TRESC[0]="szablony/main_employees.php";
include_once 'szablony/witryna.php';


