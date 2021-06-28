<?php
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


if ((isset($_POST['login']) && $_POST['login'] !== "")
    && (isset($_POST['password']) && $_POST['password'] !== "")){
    try{
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $user_saved_term = new ClTerms($pdo);
        $clinic_saved_term = new Clinics($pdo);
        $user_vaccines = new ClTerms($pdo);

        $stmt = $pdo -> prepare(
            'SELECT 
                `users`.`user_name`,
                `users`.`user_surname`,
                `users`.`user_password`,
                `users`.`user_id`,
                `users`.`user_status`

            FROM `users` 
            WHERE `users`.`user_login`=:login
            ');	

        $stmt->bindValue(':login', $_POST['login']);
        $result = $stmt -> execute(); 
        if ($stmt->rowCount()>1) throw new PDOException("Błąd w bazie danych. Więcej niż jeden użytkownik o takim samym loginie");
        if ($stmt->rowCount()==0){

            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
            $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
            if($row['user_status'] == "nieaktywny"){
                $KOMUNIKAT = 'Użytkownik nieaktywny - dokończ proces rejestracji podstępując zgodnie ze wskazówkami w mailu';
                $TRESC = array();
                $TRESC[0] = 'szablony/logowanie.php';
            }
            else{
                if($row['user_password'] == md5($_POST['password'].$TAJNY_KLUCZ)){
                    $_SESSION['username'] = $row['user_name'] . ' ' . $row['user_surname'];
                    $_SESSION['user_id'] = $row['user_id'];

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