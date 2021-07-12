<?php

// Formularz edycji danych pacjenta - widok pracownika

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/Users.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";
$USER_EDIT = "";

$user_id = $_POST['user_id'];

$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->query('SET NAMES UTF8');
$pdo->query('SET CHARACTER SET UTF8');

$user_edit_select = new Users($pdo);

$user_edit_select->getUserInfoID($user_id);

if($user_edit_select->getError()){
    $TRESC = $user_edit_select->getErrorDescription();
    include_once 'szablony/witryna.php';
    exit();
}
else{
    
    $user_edit_select = $user_edit_select->user_info_array[0];

    $USER_EDIT = 
    "<div class='card-body card_body_item'>        
        <form class='form-signin pt-3' action='edit_user_save.php' method='post'>
            <input type='text' id='user_id' name='user_id' class='form-control' value='".$user_edit_select['user_id']."' hidden>
            <div class='form-label-group'>
                <label for='user_name'>Imię</label>
                <input type='text' id='user_name' name='user_name' class='form-control' value='".$user_edit_select['user_name']."'>
            </div>
            <div class='form-label-group mt-4'>
                <label for='user_surname'>Nazwisko</label>
                <input type='text' id='user_surname' name='user_surname' class='form-control' value='".$user_edit_select['user_surname']."'>
            </div>
            <div class='form-label-group mt-4'>
                <label for='user_mail'>E-mail</label>
                <input type='email' id='user_mail' name='user_mail' class='form-control' value='".$user_edit_select['user_mail']."'>
            </div>
            <div class='form-label-group mt-4'>
                <label for='user_phone'>Numer telefonu</label>
                <input type='text' id='user_phone' name='user_phone' class='form-control' value='".$user_edit_select['user_phone']."'>
            </div>
            <div class='form-label-group mt-4'>
                <label for='user_pesel'>PESEL</label>
                <input type='text' minlength='11' maxlength='11' id='user_pesel' name='user_pesel' class='form-control' value='".$user_edit_select['user_pesel']."'>
            </div>
            <div class='form_button mt-4 pb-3'>
                <button class='btn btn-lg btn-primary btn-block' type='submit'>Zapisz</button>
            </div>
        </form>
    </div>";

}
$TRESC= array();
$TRESC[0]="szablony/edit_user.php";
include_once 'szablony/witryna.php';
     
