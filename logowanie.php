<?php

// Logika logowania użytkownika

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/ClTerms.php';
require_once 'include/Clinics.php';

$AKTYWNY = basename(__FILE__);
$TRESC="";
$LOGIN="";
$KOMUNIKAT = "";

$USER_SAVED_TERM = "";
$USER_VACCINES = "";

// Sprawdzanie czy podano login i hasło
if ((isset($_POST['login']) && $_POST['login'] !== "")
    && (isset($_POST['password']) && $_POST['password'] !== "")){
    try{
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('SET NAMES UTF8');
        $pdo->query('SET CHARACTER SET UTF8');

        $user_saved_term = new ClTerms($pdo);
        $clinic_saved_term = new Clinics($pdo);
        $user_vaccines = new ClTerms($pdo);

        $stmt = $pdo -> prepare(
            'SELECT 
                `users`.`user_name`,
                `users`.`user_surname`,
                `users`.`user_password`,
                `users`.`user_id`,
                `users`.`user_status`,
                `users`.`user_mail`

            FROM `users` 
            WHERE `users`.`user_login`=:login
            ');	

        $stmt->bindValue(':login', $_POST['login']);
        $result = $stmt -> execute(); 
        if ($stmt->rowCount()>1) throw new PDOException("Błąd w bazie danych. Więcej niż jeden użytkownik o takim samym loginie");
        if ($stmt->rowCount()==0){
            // Jeśli użytkownik o podanym loginie (pacjent) nie istnieje, to sprawdzanie czy istnieje użytkownik - pracownik o podanym loginie
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
            $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->query('SET NAMES UTF8');
            $pdo->query('SET CHARACTER SET UTF8');

            $stmt = $pdo -> prepare(
                'SELECT 
                    `employees`.`emp_name`,
                    `employees`.`emp_surname`,
                    `employees`.`emp_password`,
                    `employees`.`emp_id`,
                    `employees`.`emp_clinic`

                FROM `employees` 
                WHERE `employees`.`emp_login`=:login
                ');	

            $stmt->bindValue(':login', $_POST['login']);
            $result = $stmt -> execute(); 
            if ($stmt->rowCount()>1) throw new PDOException("Błąd w bazie danych. Więcej niż jeden użytkownik o takim samym loginie");
            if ($stmt->rowCount()==0){
                $KOMUNIKAT="Podano błędny login. Spróbuj jeszcze raz.";
                $LOGIN=$_POST['login'];
                $TRESC= array();
                $TRESC[0]="szablony/logowanie.php";
            }
            else{
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row['emp_password'] == md5($_POST['password'].$TAJNY_KLUCZ)){
                    $_SESSION['empname'] = $row['emp_name'] . ' ' . $row['emp_surname'];
                    $_SESSION['emp_id'] = $row['emp_id'];
                    $_SESSION['emp_clinic'] = $row['emp_clinic'];
                    $KOMUNIKAT = '';
                    $TRESC = array();
                    $TRESC[0] = 'szablony/main_employees.php';
                } 
                else {	
                    $KOMUNIKAT = 'Podano błędne hasło. Spróbuj jeszcze raz.';
                    $LOGIN = $_POST['login'];
                    $TRESC = array();
                    $TRESC[0] = 'szablony/logowanie.php';
                }
            }
        }
        else{
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Jeśli użytkownik o podanym loginie istnieje ale jego status jest "nieaktywny" użytkownik musi dokończyć proces rejestracji podając kod weryfikacyjny przesłany mailem
            if($row['user_status'] == "nieaktywny"){
                $KOMUNIKAT = 'Użytkownik nieaktywny - dokończ proces rejestracji podstępując zgodnie ze wskazówkami w mailu';
                $TRESC = array();
                $TRESC[0] = 'szablony/logowanie.php';
            }
            else{
                if($row['user_password'] == md5($_POST['password'].$TAJNY_KLUCZ)){
                    $_SESSION['username'] = $row['user_name'] . ' ' . $row['user_surname'];
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['user_mail'] = $row['user_mail'];

                    require_once 'vaccines_user.php';
                } 
                else {	
                    $KOMUNIKAT = 'Podano błędne hasło. Spróbuj jeszcze raz.';
                    $LOGIN = $_POST['login'];
                    $TRESC = array();
                    $TRESC[0] = 'szablony/logowanie.php';
                }
            }
        }

        $stmt->closeCursor();
    }

    catch(PDOException $e){
        $TRESC.= 'Wystapil blad biblioteki PDO: ' . $e->getMessage();
    }
}

else{
    $KOMUNIKAT="Podaj swoje dane do logowania.";
    $TRESC= array();
    $TRESC[0]="szablony/logowanie.php";
}


include_once 'szablony/witryna.php';