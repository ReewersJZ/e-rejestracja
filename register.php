<?php

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/Users.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";


$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->query('SET NAMES UTF8');
$pdo->query('SET CHARACTER SET UTF8');

$users = new Users($pdo);

$verifyCode = bin2hex(random_bytes(4));

$user_login = $_POST['form_register_mail']; 
$user_password = $_POST['form_register_password'];
$user_password = MD5($user_password.$TAJNY_KLUCZ);
$user_named = $_POST['form_register_name']; 
$user_surname = $_POST['form_register_surname']; 
$user_mail = $_POST['form_register_mail']; 
$user_phone = $_POST['form_register_phone']; 
$user_pesel = $_POST['form_register_pesel']; 
$user_status = "nieaktywny"; 
$user_code = $verifyCode; 

if ($user_login !== ""){
    try{
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('SET NAMES UTF8');
        $pdo->query('SET CHARACTER SET UTF8');

        $stmt = $pdo -> prepare(
            'SELECT 
                `users`.`user_login`
            FROM `users` 
            WHERE `users`.`user_login`=:login
            ');	

        $stmt->bindValue(':login', $user_login);
        $result = $stmt -> execute(); 
        if ($stmt->rowCount()>=1) {
            $KOMUNIKAT= "Użytkownik o takim loginie już istnieje";
            $TRESC = array();
            $TRESC[0]="szablony/logowanie.php";
            include_once 'szablony/witryna.php';
        }
        elseif ($stmt->rowCount()==0){
            $users->insert($user_login, $user_password, $user_named, $user_surname, $user_mail, $user_phone, $user_pesel, $user_status, $user_code);
            if($users->getError()){
                    $TRESC = $users->getErrorDescription();
                    include_once 'szablony/witryna.php';
                    exit();
                }
            
            // wysyłka maila z kodem weryfikacyjnym

            $from  = "From: ".$postFrom." \r\n";
            $from .= 'MIME-Version: 1.0'."\r\n";
            $from .= 'Content-type: text/html; charset=UTF-8'."\r\n";
            $adress = $user_login;
            $title = "Rejestracja użytkownika";
            $sentMessage = "<html>
            <head>
            </head>
            <body>
            <h4>Witamy na pokładzie!</h4>
            <p>Twój kod weryfikacyjny to: <b>".$verifyCode."</b></p>
            <p> Aby dokończyć rejestrację kliknij w link: <a href='http://pc55493.wsbpoz.solidhost.pl/erejestracja/index_verify_reg.php'>dokończ rejestrację</a> i wprowadź swój kod weryfikacyjny.</p><br>
            <p>Życzymy przyjemnego użytkowania naszej aplikacji.</p>
            <p>Yulia, Justyna i Marcin</p>";
            
            mail($adress, $title, $sentMessage, $from);

            // koniec wysyłki maila

            $KOMUNIKAT = "Zarejestrowano użytkownika. Dokończ rejestrację podając kod weryfikacyjny z otrzymanego maila ";
            include_once 'index_verify_reg.php';

        }

        $stmt->closeCursor();
    }

    catch(PDOException $e){
        $TRESC.= 'Wystapil blad biblioteki PDO: ' . $e->getMessage();
    }
}

else{
    $KOMUNIKAT="Nie podano adresu e-mail";
    $TRESC= array();
    $TRESC[0]="szablony/logowanie.php";
}


