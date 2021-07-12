<?php

// Wyszukiwarka pacjenta po pesel`u - wyświetlenie danych pacjenta z możliwością edycji, wyświetlenie umówionych terminów wizyt, wyświetlenie przyjętych dawek szczepień

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/ClTerms.php';
require_once 'include/Users.php';
require_once 'include/Clinics.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";
$USERS_INFO = "";

$user_pesel = $_POST['search_engine_pesel'];

$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->query('SET NAMES UTF8');
$pdo->query('SET CHARACTER SET UTF8');

$users_info = new ClTerms($pdo);
$users_info_confirmed = new ClTerms($pdo);
$users_info_user = new Users($pdo);
$clinicName = new Clinics($pdo);

if(isset($user_pesel) && $user_pesel != ""){

    $users_info_user->getUserInfo($user_pesel);

    if($users_info_user->getError()){
        $TRESC = $users_terms->getErrorDescription();
        include_once 'szablony/witryna.php';
        exit();
    }
    else{

        // Wyświetlenie danych wyszukiwanego po peselu pacjenta

        if(count($users_info_user->user_info_array) > 0){

            $user_info_array = $users_info_user->user_info_array[0];

            $user_block_info = 
            
            "<div class='card-body card_body_item'>
            <h5 class='card-title'>Dane pacjenta</h5>
            <form class='terms_list' action='edit_user.php' method='post'>
                <input type='text' id='user_id' name='user_id' class='form-control' value='".$user_info_array['user_id']."' hidden>

                <div class='form-label-group mt-2'>
                    <p>".$user_info_array['user_name']."</p>
                </div>
                <div class='form-label-group mt-2'>
                    <p>".$user_info_array['user_surname']."</p>
                </div>
                <div class='form-label-group mt-2'>
                    <p>".$user_info_array['user_pesel']."</p>
                </div>
                    <div class='form-label-group mt-2'>
                    <p>".$user_info_array['user_mail']."</p>
                </div>
                <div class='form-label-group mt-2'>
                    <p>tel. ".$user_info_array['user_phone']."</p>
                </div>

                <div class='form_button'>
                    <button class='btn btn-lg btn-primary btn-block' type='submit'>Edytuj</button>
                </div>
            </form>
            </div><hr>
            ";
        
            $clterms_user_id = $user_info_array['user_id'];
            $clterms_confirmed = "";
            $clterms_status = "zajety";

            $users_info->checkUserTerms($clterms_user_id, $clterms_confirmed, $clterms_status);

            if(count($users_info->check_user_terms_array) > 0){

                // Wyświetlenie zarezerwowanych terminów wizyt dla wyszukanego po peselu pacjenta

                $check_user_terms_array = $users_info->check_user_terms_array[0];

                $clterms_clinic_id = $check_user_terms_array['clterms_clinic_id'];

                $clinicName->getClinicByID($clterms_clinic_id);
                $clinicName = $clinicName->selected_clinic_array;
                $clinicName = $clinicName[0];
                $clinicName = $clinicName['clinic_name'];
              
                $term_block_info = 
            
                "<div class='card-body card_body_item'>
                <h5 class='card-title'>Umówione wizyty</h5>
                <form class='terms_list' action='delete_term_user.php' method='post'>
                    <input type='text' id='delete_user_term_id' name='delete_user_term_id' class='form-control' value='".$check_user_terms_array['clterms_id']."' hidden>

                    <div class='form-label-group mt-2'>
                        <p>".$clinicName."</p>
                    </div>
                    <div class='form-label-group mt-2'>
                        <p>".$check_user_terms_array['clterms_date']."</p>
                    </div>
                    <div class='form-label-group mt-2'>
                        <p>".$check_user_terms_array['clterms_hour_from']."</p>
                    </div>
                    <div class='form_button'>
                        <button class='btn btn-lg btn-primary btn-block' type='submit'>Usuń termin</button>
                    </div>
                </form>
                </div><hr>
                ";
            }
            else{
                $term_block_info = 
            
                "<div class='card-body card_body_item'>
                <h5 class='card-title'>Umówione wizyty</h5>
                <p>brak informacji</p>
                </div><hr>
                ";
            }

            $clterms_confirmed = "wykonano";
            $users_info_confirmed->checkUserTerms($clterms_user_id, $clterms_confirmed, $clterms_status);

            if(count($users_info_confirmed->check_user_terms_array) > 0){

                // Wyświetlenie przyjętych dawek szczepień dla wyszukanego po peselu pacjenta

                $check_user_terms_array_confirmed = $users_info_confirmed->check_user_terms_array;

                $term_block_info_confirmed = "";

                foreach ($check_user_terms_array_confirmed as $term_confirmed){

                    $info_confirmed = "
                    <div class='card-body card_body_item'>
                        <h5 class='card-title'>Przyjęta dawka</h5>
                        <form class='terms_list' action='edit_user_term.php' method='post'>
                            <input type='text' id='clterms_id' name='clterms_id' class='form-control' value='".$term_confirmed['clterms_id']."' hidden>
    
                            <div class='form-label-group mt-2'>
                                <p>".$term_confirmed['clterms_date']."</p>
                            </div>
                        </form>
                    </div><hr>
                    ";
                    $term_block_info_confirmed = $term_block_info_confirmed . $info_confirmed;
                    }
            }
            else{
                $term_block_info_confirmed =
                "<div class='card-body card_body_item'>
                <h5 class='card-title'>Przyjęta dawka</h5>
                <p>brak informacji</p>
                </div>
                ";
            }
            $USERS_INFO = $user_block_info . $term_block_info . $term_block_info_confirmed;
            $KOMUNIKAT = "";
            $TRESC= array();
            $TRESC[0]="szablony/search_engine.php";
            include_once 'szablony/witryna.php';
        }
        else{
            $USERS_INFO = "brak wyników";
            $KOMUNIKAT = "";
            $TRESC= array();
            $TRESC[0]="szablony/search_engine.php";
            include_once 'szablony/witryna.php';
        }
    }  
}
else{
    $KOMUNIKAT = "Błąd logowania. zaloguj się ponownie.";
    $TRESC= array();
    $TRESC[0]="szablony/search_engine.php";
    include_once 'szablony/witryna.php';
}




