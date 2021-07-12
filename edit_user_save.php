<?php

// Zmiana danych pacjenta - edycja danych

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/Users.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";
$USER_EDIT = "";

$user_id = $_POST['user_id'];
$user_name = $_POST['user_name'];
$user_surname = $_POST['user_surname'];
$user_phone = $_POST['user_phone'];
$user_mail = $_POST['user_mail'];
$user_pesel = $_POST['user_pesel'];

$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->query('SET NAMES UTF8');
$pdo->query('SET CHARACTER SET UTF8');

$user_update = new Users($pdo);

$user_update->updateUserData($user_id, $user_name, $user_surname, $user_phone, $user_mail, $user_pesel);

if($user_update->getError()){
    $TRESC = $user_update->getErrorDescription();
    include_once 'szablony/witryna.php';
    exit();
}
else{
    $KOMUNIKAT = "Dane pacjenta zmienione";
    $TRESC= array();
    $TRESC[0]="szablony/search_engine.php";
    include_once 'szablony/witryna.php';
}
