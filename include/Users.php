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
     * pobieranie statusu bledu
    */
    public function getError()
    {
        $error = $this->is_error;
        $this->is_error = FALSE;
        return $error;
    }
    /**
     * pobieranie opisu blędu
    */
    public function getErrorDescription()
    {
        $error_description = $this -> error_description;
        $this -> error_description = '';
        return $error_description;
    }
}
    
 