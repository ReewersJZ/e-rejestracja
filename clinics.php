<?php

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/Clinics.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";
$CLINICS = "";


$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$clinics = new Clinics($pdo);



    try{
        $stmt = $pdo -> prepare(
            'SELECT 
                `clinics`.`clinic_id`,
                `clinics`.`clinic_name`,
                `clinics`.`clinic_city`,
                `clinics`.`clinic_street`,
                `clinics`.`clinic_status`
            FROM `clinics` 
            WHERE `clinics`.`clinic_status`=:clinic_status
            ');	

        $stmt->bindValue(':clinic_status', "aktywny");
        $result = $stmt -> execute(); 
        if($stmt->rowCount()==0){
            echo "Brak przychodni w bazie danych";
        }
        else{
            $clinics->getClinics();
            if($clinics->getError()){
                    $TRESC = $users->getErrorDescription();
                    include_once 'szablony/witryna.php';
                    exit();
                }
            else{
                    $select_clinic = "";

                    foreach ($clinics as $clinics_array){

                        foreach ($clinics_array as $clinic){
                            $select_clinic = $select_clinic . "<option value='".$clinic['clinic_id']."'>".$clinic['clinic_name'].', '.$clinic['clinic_city'].', '.$clinic['clinic_street']."</option>";
                        }
                    }
                    $CLINICS = $select_clinic;
                }
        }

        $stmt->closeCursor();
    }

    catch(PDOException $e){
        $TRESC.= 'Wystapil blad biblioteki PDO: ' . $e->getMessage();
    }



 