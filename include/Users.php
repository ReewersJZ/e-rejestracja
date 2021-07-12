<?php

/**
 * @author WSB grupa nr 1
 * Klasa obsługi czynności związanych z tabelą 'users'
 */

 class Users
 {
    private $pdo;
    private $is_error;
    private $error_description;
    public $users_array;


    /**
    * Konstruktor klasy
    * @param PDO object $pdo
    */
    public function __construct($pdo){
        $this->pdo=$pdo;
        $this->is_error=FALSE;
    }

    /**
    * Metoda dodająca nowy rekord do bazy danych
    * @param string $user_login
    * @param string $user_password
    * @param string $user_named
    * @param string $user_surname
    * @param string $user_mail
    * @param string $user_phone
    * @param string $user_pesel
    * @param string $user_status
    * @param string $user_code
    */
    public function insert($user_login, $user_password, $user_named, $user_surname, $user_mail, $user_phone, $user_pesel, $user_status, $user_code){
        if ($this->pdo==null){
            $this->is_error=TRUE;
            $this->error_description="Brak połaczenia z bazą danych";
            return;
        }
        try{
            $stmt = $this->pdo -> prepare('INSERT INTO `users` (`user_login`, `user_password`, `user_name`, `user_surname`, `user_mail`, `user_phone`, `user_pesel`, `user_status`, `user_code`) VALUES (:user_login, :user_password, :user_named, :user_surname, :user_mail, :user_phone, :user_pesel, :user_status, :user_code);');
            $stmt->bindValue(':user_login', $user_login, PDO::PARAM_STR);
            $stmt->bindValue(':user_password', $user_password, PDO::PARAM_STR);
            $stmt->bindValue(':user_named', $user_named, PDO::PARAM_STR);
            $stmt->bindValue(':user_surname', $user_surname, PDO::PARAM_STR);
            $stmt->bindValue(':user_mail', $user_mail, PDO::PARAM_STR);
            $stmt->bindValue(':user_phone', $user_phone, PDO::PARAM_STR);
            $stmt->bindValue(':user_pesel', $user_pesel, PDO::PARAM_STR);
            $stmt->bindValue(':user_status', $user_status, PDO::PARAM_STR);
            $stmt->bindValue(':user_code', $user_code, PDO::PARAM_STR);
            $result = $stmt ->execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description='';
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e){
            $this->is_error=TRUE;
            $this->error_description="nie udało się dodać rekordu do bazy danych: ". $e->getMessage();
            return;
        }
    }

    /**
     * pobieranie danych pacjenta - po PESEL`u
    */
    public function getUserInfo($user_pesel)
    {
        $this->user_info_array = array();
        if($this->pdo == null){
            $this->is_error = TRUE;
            $this->error_description = 'Brak połączenia z bazą';
            return;
        }
        try {
            $query = "SELECT `user_id`, `user_login`, `user_name`, `user_surname`, `user_mail`, `user_phone`, `user_pesel` from users WHERE `user_pesel` = '$user_pesel'"; 

            $stmt = $this->pdo -> prepare($query);
            $result = $stmt -> execute();
            if($result == true){
                $this->is_error = FALSE;
                $this->error_description = '';
                $this->user_info_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch(PDOException $e){
            $this->is_error = TRUE;
            $this->error_description = 'Nie udało się odczytać danych z bazy: '. $e->getMessage();
            return;
        }
        return $this->user_info_array;
    }

    /**
     * pobieranie danych pacjenta - po ID
    */
    public function getUserInfoID($user_id)
    {
        $this->user_info_array = array();
        if($this->pdo == null){
            $this->is_error = TRUE;
            $this->error_description = 'Brak połączenia z bazą';
            return;
        }
        try {
            $query = "SELECT `user_id`, `user_login`, `user_name`, `user_surname`, `user_mail`, `user_phone`, `user_pesel` from users WHERE `user_id` = '$user_id'"; 

            $stmt = $this->pdo -> prepare($query);
            $result = $stmt -> execute();
            if($result == true){
                $this->is_error = FALSE;
                $this->error_description = '';
                $this->user_info_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch(PDOException $e){
            $this->is_error = TRUE;
            $this->error_description = 'Nie udało się odczytać danych z bazy: '. $e->getMessage();
            return;
        }
        return $this->user_info_array;
    }
    

    /**
     * modyfikowanie rekordu o opodanym id - edycja danych pacjenta
     * @param int $user_id
     * @param string $user_name
     * @param string $user_surname
     * @param string $user_phone
     * @param string $user_mail
     * @param string $user_pesel
    */
    public function updateUserData($user_id, $user_name, $user_surname, $user_phone, $user_mail, $user_pesel)
    {
        if($this->pdo == null){
            $this->is_error = TRUE;
            $this->error_description = 'Brak połączenia z bazą';
            return;
        }
        try {
            $stmt = $this->pdo -> prepare('UPDATE users SET
                                                user_name=:user_name,
                                                user_surname=:user_surname,
                                                user_phone=:user_phone,
                                                user_mail=:user_mail,
                                                user_pesel=:user_pesel

                                            WHERE user_id=:user_id');
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':user_name', $user_name, PDO::PARAM_STR);
            $stmt->bindValue(':user_surname', $user_surname, PDO::PARAM_STR);
            $stmt->bindValue(':user_phone', $user_phone, PDO::PARAM_STR);
            $stmt->bindValue(':user_mail', $user_mail, PDO::PARAM_STR);
            $stmt->bindValue(':user_pesel', $user_pesel, PDO::PARAM_STR);
            $result = $stmt -> execute();
            if($result == true){
                $this->is_error = FALSE;
                $this->error_description = '';
            }
            $stmt->closeCursor();
        }
        catch(PDOException $e){
            $this->is_error = TRUE;
            $this->error_description = 'Nie udało się zmienić danych: '. $e->getMessage();
            return;
        }
    }
   
    /**
     * pobieranie statusu błędu
    */
    public function getError()
    {
        $error = $this->is_error;
        $this->is_error = FALSE;
        return $error;
    }
    /**
     * pobieranie opisu błędu
    */
    public function getErrorDescription()
    {
        $error_description = $this -> error_description;
        $this -> error_description = '';
        return $error_description;
    }
}
    
 