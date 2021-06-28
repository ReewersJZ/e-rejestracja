<?php

$user_vaccines->checkUserTerms($_SESSION['user_id'], "wykonano", "zajety");
if($user_vaccines->getError()){
    $TRESC = $user_vaccines->getErrorDescription();
    include_once 'szablony/witryna.php';
    exit();
}
elseif(count($user_vaccines->check_user_terms_array) >= 1){
    $user_vaccines_array = $user_vaccines->check_user_terms_array;
    $vaccines = $user_vaccines_array;
    $counter = 1;
    $vaccine_content = "";

    foreach($vaccines as $vaccine){
        $vaccine_content =  $vaccine_content."<p class='card-text'>Dawka ".$counter.": ".$vaccine['clterms_date'].", godz. ".$vaccine['clterms_hour_from']."</p>";
        $counter +=1;
    }

    $USER_VACCINES = $vaccine_content;
}
else{
    $USER_VACCINES = "Brak informacji o podanych dawkach szczepionek";
}


$user_saved_term->checkUserTerms($_SESSION['user_id'], "", "zajety");
if($user_saved_term->getError()){
    $TRESC = $user_saved_term->getErrorDescription();
    include_once 'szablony/witryna.php';
    exit();
}
elseif(count($user_saved_term->check_user_terms_array) == 1){
    $check_user_terms = $user_saved_term->check_user_terms_array;
    $check_user_terms_array = $check_user_terms[0];
    
    $clinic_saved_term->getClinicByID($check_user_terms_array['clterms_clinic_id']);
    $clinic_saved = $clinic_saved_term->selected_clinic_array;
    $clinic = $clinic_saved[0];

    $term = "
    <form class='vaccines_section' action='delete_term_user.php' method='post'>
    <input class='card-text' name='delete_user_term_id' value='".$check_user_terms_array['clterms_id']."' hidden>
    <p class='card-text'>Data: ".$check_user_terms_array['clterms_date']."</p>
    <p class='card-text'>Godzina: ".$check_user_terms_array['clterms_hour_from']."</p>
    <p class='card-text'>Przychodnia: ".$clinic['clinic_name'].", ".$clinic['clinic_city'].", ".$clinic['clinic_street']."</p>
    <button type='submit' class='btn btn-primary'>Usuń</button>
    </form>";

    $USER_SAVED_TERM = $term;
    $KOMUNIKAT = '';
    $TRESC = array();
    $TRESC[0] = 'szablony/main_user.php';

}
else{
    $USER_SAVED_TERM = "Brak umówionej wizyty";
    $KOMUNIKAT = '';
    $TRESC = array();
    $TRESC[0] = 'szablony/main_user.php';

}

?>