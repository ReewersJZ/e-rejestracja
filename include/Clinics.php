<?php

/**
 * @author WSB grupa nr 1
 * Klasa obsługi czynności związanych z tabelą 'clinics'
 */

 class Clinics
 {
    private $pdo;
    private $is_error;
    private $error_description;
    public $clinics_array;


    /**
    * Konstruktor klasy
    * @param PDO object $pdo
    */
    public function __construct($pdo){
        $this->pdo=$pdo;
        $this->is_error=FALSE;
    }

   
    /**
     * pobieranie listy Podmiotów medycznych
     * @param string $order_by default clinic_name - kolumna względem której ma być sortowana
     * @param bool $narastajaco default true - czy sortowac narastająco
    */
    public function getClinics($order_by='clinic_name', $narastajaco = TRUE)
    {
        $this->clinics_array = array();
        if($this->pdo == null){
            $this->is_error = TRUE;
            $this->error_description = 'Brak połączenia z bazą';
            return;
        }
        try {
            $query = 'SELECT clinic_id, clinic_name, clinic_city, clinic_street FROM clinics ';
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
                $this->clinics_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch(PDOException $e){
            $this->is_error = TRUE;
            $this->error_description = 'Nie udało się odczytać danych z bazy: '. $e->getMessage();
            return;
        }
        return $this->clinics_array;
    }


    /**
     * pobieranie Podmiotu medycznego po ID
     * @param int $clterms_clinic_id
    */
    public function getClinicByID($clterms_clinic_id)
    {
        $this->selected_clinic_array = array();
        if($this->pdo == null){
            $this->is_error = TRUE;
            $this->error_description = 'Brak połączenia z bazą';
            return;
        }
        try {
            $query = "SELECT clinic_id, clinic_name, clinic_city, clinic_street FROM clinics WHERE `clinic_id`= '$clterms_clinic_id'";

            $stmt = $this->pdo -> prepare($query);
            $result = $stmt -> execute();
            if($result == true){
                $this->is_error = FALSE;
                $this->error_description = '';
                $this->selected_clinic_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch(PDOException $e){
            $this->is_error = TRUE;
            $this->error_description = 'Nie udało się odczytać danych z bazy: '. $e->getMessage();
            return;
        }
        return $this->selected_clinic_array;
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