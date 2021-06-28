<?php
require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';

$AKTYWNY="verify_user.php";
$TRESC="";
$LOGIN="";
$KOMUNIKAT = "";




if ((isset($_POST['inputLogin_verify']) && $_POST['inputLogin_verify'] !== "")
    && (isset($_POST['inputPassword_verify']) && $_POST['inputPassword_verify'] !== "") && (isset($_POST['inputCode_verify']) && $_POST['inputCode_verify'] !== "")){
    try{
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('SET NAMES UTF8');
        $pdo->query('SET CHARACTER SET UTF8');

        $stmt = $pdo -> prepare(
            'SELECT 
                `users`.`user_login`,
                `users`.`user_password`,
                `users`.`user_code`
            FROM `users` 
            WHERE `users`.`user_login`=:login
            ');	

        $stmt->bindValue(':login', $_POST['inputLogin_verify']);
        $result = $stmt -> execute(); 
        if ($stmt->rowCount()>1) throw new PDOException("Błąd w bazie danych. Więcej niż jeden użytkownik o takim samym loginie");
        if ($stmt->rowCount()==0){
            $KOMUNIKAT = 'Nie dokonano rejestracji. Zarejestruj się';
            $TRESC = array();
            $TRESC[0] = 'szablony/logowanie.php';
        }
        else{
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if($row['user_password'] == md5($_POST['inputPassword_verify'].$TAJNY_KLUCZ)){

                    if ($row['user_code'] == $_POST['inputCode_verify']){

                                if($pdo == null){
                                    $is_error = TRUE;
                                    $error_description = 'Brak połączenia z bazą';
                                    return;
                                }
                                try {
                                    $stmt = $pdo -> prepare('UPDATE users SET
                                                                        user_status=:user_status,
                                                                        user_code=:user_code
                                                                    WHERE user_login=:user_login');
                                    $stmt->bindValue(':user_code', "", PDO::PARAM_STR);
                                    $stmt->bindValue(':user_status', "aktywny" , PDO::PARAM_STR);
                                    $stmt->bindValue(':user_login', $_POST['inputLogin_verify'], PDO::PARAM_STR);
                                    $result = $stmt -> execute();
                                    if($result == true){
                                        $is_error = FALSE;
                                        $error_description = '';
                                        $KOMUNIKAT = 'Weryfikacja przebiegła pomyślnie. Zaloguj się.';
                                        $TRESC = array();
                                        $TRESC[0] = 'szablony/logowanie.php';
                                    }
                                    $stmt->closeCursor();
                                }
                                catch(PDOException $e){
                                    $is_error = TRUE;
                                    $error_description = 'Nie udało się prawidłowo przeprowadzić weryfikacji użytkownika '. $e->getMessage();
                                    return;
                                }
                    }
                    else{
                        $KOMUNIKAT = 'Podano błędny kod weryfikacyjny. Spróbuj jeszcze raz.';
                        $TRESC = array();
                        $TRESC[0] = 'szablony/index_verify_reg.php';
                    }
                } 
                else {	
                    $KOMUNIKAT = 'Podano błędne hasło. Spróbuj jeszcze raz.';
                    $TRESC = array();
                    $TRESC[0] = 'szablony/index_verify_reg.php';
                }
        }

        $stmt->closeCursor();
    }

    catch(PDOException $e){
        $TRESC.= 'Wystapil blad biblioteki PDO: ' . $e->getMessage();
    }
}

else{
    $KOMUNIKAT="Nie wszystkie pola formularza weryfikacyjnego są uzupełnione. Wprowadź dane ponownie.";
    $TRESC= array();
    $TRESC[0]="szablony/index_verify_reg.php";
}


include_once 'szablony/witryna.php';