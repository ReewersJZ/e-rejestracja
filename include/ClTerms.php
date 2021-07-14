<?php

/**
 * @author WSB grupa nr 1
 * Klasa obsługi czynności związanych z tabelą 'clinics_terms'
 */

 class ClTerms
 {
    private $pdo;
    private $is_error;
    private $error_description;
   
    /**
    * Konstruktor klasy
    * @param PDO object $pdo
    */
    public function __construct($pdo){
        $this->pdo=$pdo;
        $this->is_error=FALSE;
    }

    /**
    * Metoda dodająca nowy rekord do bazy danych - dodanie wolnego terminu
    * @param int $clterms_clinic_id
    * @param string $clterms_date
    * @param string $clterms_hour_from
    * @param string $clterms_status
    */
    public function insertCLTerm($clterms_clinic_id, $clterms_date, $clterms_hour_from, $clterms_status){
        if ($this->pdo==null){
            $this->is_error=TRUE;
            $this->error_description="Brak połaczenia z bazą danych";
            return;
        }
        try{
            $stmt = $this->pdo -> prepare('INSERT INTO `clinics_terms` (`clterms_clinic_id`, `clterms_date`, `clterms_hour_from`, `clterms_status`) VALUES (:clterms_clinic_id, :clterms_date, :clterms_hour_from, :clterms_status);');
            $stmt->bindValue(':clterms_clinic_id', $clterms_clinic_id, PDO::PARAM_INT);
            $stmt->bindValue(':clterms_date', $clterms_date, PDO::PARAM_STR);
            $stmt->bindValue(':clterms_hour_from', $clterms_hour_from, PDO::PARAM_STR);
            $stmt->bindValue(':clterms_status', $clterms_status, PDO::PARAM_STR);
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
    * Metoda sprawdzająca czy nowy termin już istnieje w bazie danych
    * @param int $clterms_clinic_id
    * @param string $clterms_date
    * @param string $clterms_hour_from
    * @param string $search_terms_dateFrom
    */
    public function checkTerms($search_terms_clinic, $search_terms_dateFrom, $search_terms_hour_from)
    {
        $this->check_free_terms_array = array();
        if($this->pdo == null){
            $this->is_error = TRUE;
            $this->error_description = 'Brak połączenia z bazą';
            return;
        }
        try {
            $query = "SELECT `clterms_id`, `clterms_date`, `clterms_hour_from` from clinics_terms WHERE `clterms_clinic_id` = '$search_terms_clinic' and `clterms_date`='$search_terms_dateFrom' and  `clterms_hour_from`='$search_terms_hour_from'";

            $stmt = $this->pdo -> prepare($query);
            $result = $stmt -> execute();
            if($result == true){
                $this->is_error = FALSE;
                $this->error_description = '';
                $this->check_free_terms_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch(PDOException $e){
            $this->is_error = TRUE;
            $this->error_description = 'Nie udało się odczytać danych z bazy: '. $e->getMessage();
            return;
        }
        return $this->check_free_terms_array;
    }

  /**
     * pobieranie wolnych terminów
     * @param string $final_query - końcowe, złożone zapytanie dotczące wolnych terminów według wybranych kryteriów wyszukiwania
    */
    public function getTerms($final_query)
    {
        $this->free_terms_array = array();
        if($this->pdo == null){
            $this->is_error = TRUE;
            $this->error_description = 'Brak połączenia z bazą';
            return;
        }
        try {
            $query = $final_query; 

            $stmt = $this->pdo -> prepare($query);
            $result = $stmt -> execute();
            if($result == true){
                $this->is_error = FALSE;
                $this->error_description = '';
                $this->free_terms_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch(PDOException $e){
            $this->is_error = TRUE;
            $this->error_description = 'Nie udało się odczytać danych z bazy: '. $e->getMessage();
            return;
        }
        return $this->free_terms_array;
    }


    /**
     * pobieranie umówionych wizyt z podanej daty
     * @param string $order_by default clterms_hour_from - kolumna względem której ma być sortowana
     * @param bool $narastajaco default true - czy sortowac narastająco
    */
    public function getBookedTerms($clterms_clinic_id, $clterms_date, $clterms_status, $clterms_confirmed, $order_by='clterms_hour_from', $narastajaco = TRUE)
    {
        $this->booked_terms_array = array();
        if($this->pdo == null){
            $this->is_error = TRUE;
            $this->error_description = 'Brak połączenia z bazą';
            return;
        }
        try {
            $query = "SELECT `clterms_id`, `clterms_date`, `clterms_hour_from`, `user_id`, `user_name`, `user_surname`, `user_pesel`,`user_mail`, `user_phone` from clinics_terms inner join users on clinics_terms.clterms_user_id = users.user_id WHERE clinics_terms.clterms_clinic_id = '$clterms_clinic_id' and clinics_terms.clterms_status ='$clterms_status' and clinics_terms.clterms_date ='$clterms_date' and clinics_terms.clterms_confirmed ='$clterms_confirmed'"; 

            $query .= 'ORDER BY '.$order_by;
            if($narastajaco)
                $query .= ' ASC ';
            else
                $query .= ' DESC ';

            $stmt = $this->pdo -> prepare($query);
            $result = $stmt -> execute();
            if($result == true){
                $this->is_error = FALSE;
                $this->error_description = '';
                $this->booked_terms_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch(PDOException $e){
            $this->is_error = TRUE;
            $this->error_description = 'Nie udało się odczytać danych z bazy: '. $e->getMessage();
            return;
        }
        return $this->booked_terms_array;
    }

    /**
     * pobieranie umówionych wizyt/historii wizyt z podanego zakresu dat
     * @param string $order_by default clterms_date - kolumna względem której ma być sortowana
     * @param bool $narastajaco default true - czy sortowac narastająco
    */
    public function getBookedTermsRaports($clterms_date_from, $clterms_date_to, $clterms_clinic_id, $clterms_status, $clterms_confirmed, $order_by='clterms_date', $narastajaco = TRUE)
    {
        $this->raports_booked_terms_array = array();
        if($this->pdo == null){
            $this->is_error = TRUE;
            $this->error_description = 'Brak połączenia z bazą';
            return;
        }
        try {
            $query = "SELECT * from clinics_terms inner join users on clinics_terms.clterms_user_id = users.user_id WHERE clinics_terms.clterms_clinic_id = '$clterms_clinic_id' and clinics_terms.clterms_date >= '$clterms_date_from' and clinics_terms.clterms_date <= '$clterms_date_to' and clinics_terms.clterms_status ='$clterms_status' and clinics_terms.clterms_confirmed ='$clterms_confirmed'"; 

            $query .= 'ORDER BY '.$order_by;
            if($narastajaco)
                $query .= ' ASC ';
            else
                $query .= ' DESC ';

            $stmt = $this->pdo -> prepare($query);
            $result = $stmt -> execute();
            if($result == true){
                $this->is_error = FALSE;
                $this->error_description = '';
                $this->raports_booked_terms_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch(PDOException $e){
            $this->is_error = TRUE;
            $this->error_description = 'Nie udało się odczytać danych z bazy: '. $e->getMessage();
            return;
        }
        return $this->raports_booked_terms_array;
    }


    /**
    * sprawdzanie czy użytkownik ma już ustalony termin wizyty - wybieranie terminu przez użytkownika
    * @param int $clterms_user_id
    * @param string $clterms_confirmed
    * @param string $clterms_status
    */
    public function checkUserTerms($clterms_user_id, $clterms_confirmed, $clterms_status)
    {
        $this->check_user_terms_array = array();
        if($this->pdo == null){
            $this->is_error = TRUE;
            $this->error_description = 'Brak połączenia z bazą';
            return;
        }
        try {
            $query = "SELECT * from clinics_terms WHERE `clterms_user_id` = '$clterms_user_id' and `clterms_confirmed` = '$clterms_confirmed' and `clterms_status` = '$clterms_status'";
            $stmt = $this->pdo -> prepare($query);
            $result = $stmt -> execute();
            if($result == true){
                $this->is_error = FALSE;
                $this->error_description = '';
                $this->check_user_terms_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch(PDOException $e){
            $this->is_error = TRUE;
            $this->error_description = 'Nie udało się odczytać danych z bazy: '. $e->getMessage();
            return;
        }
        return $this->check_user_terms_array;
    }


    /**
     * modyfikowanie rekordu o opodanym id - wybieranie terminu przez użytkownika
     * @param int $clterms_id
     * @param int $clterms_user_id
     * @param string $clterms_status
    */
    public function updateTermUser($clterms_id, $clterms_user_id, $clterms_status)
    {
        if($this->pdo == null){
            $this->is_error = TRUE;
            $this->error_description = 'Brak połączenia z bazą';
            return;
        }
        try {
            $stmt = $this->pdo -> prepare('UPDATE clinics_terms SET
                                                clterms_user_id=:clterms_user_id,
                                                clterms_status=:clterms_status
                                            WHERE clterms_id=:clterms_id');
            $stmt->bindValue(':clterms_id', $clterms_id, PDO::PARAM_INT);
            $stmt->bindValue(':clterms_user_id', $clterms_user_id, PDO::PARAM_INT);
            $stmt->bindValue(':clterms_status', $clterms_status, PDO::PARAM_STR);
            $result = $stmt -> execute();
            if($result == true){
                $this->is_error = FALSE;
                $this->error_description = '';
            }
            $stmt->closeCursor();
        }
        catch(PDOException $e){
            $this->is_error = TRUE;
            $this->error_description = 'Nie udało się wybrać terminu wizyty: '. $e->getMessage();
            return;
        }
    }

    /**
     * modyfikowanie rekordu o opodanym id - potwierdzanie wizyty
     * @param int $clterms_id
     * @param string $clterms_confirmed
    */
    public function updateConfirm($clterms_id, $clterms_confirmed)
    {
        if($this->pdo == null){
            $this->is_error = TRUE;
            $this->error_description = 'Brak połączenia z bazą';
            return;
        }
        try {
            $stmt = $this->pdo -> prepare('UPDATE clinics_terms SET
                                                clterms_confirmed=:clterms_confirmed
                                            WHERE clterms_id=:clterms_id');
            $stmt->bindValue(':clterms_id', $clterms_id, PDO::PARAM_INT);
            $stmt->bindValue(':clterms_confirmed', $clterms_confirmed, PDO::PARAM_STR);
            $result = $stmt -> execute();
            if($result == true){
                $this->is_error = FALSE;
                $this->error_description = '';
            }
            $stmt->closeCursor();
        }
        catch(PDOException $e){
            $this->is_error = TRUE;
            $this->error_description = 'Nie udało się potwierdzić wizyty: '. $e->getMessage();
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