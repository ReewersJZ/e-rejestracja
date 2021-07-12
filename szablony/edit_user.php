
<?php

// Widok formularza edycji danych pacjenta

if(isset($_SESSION['empname']) && isset($_SESSION['emp_clinic'])){

?> 

<div class="card mb-4">
    <h5 class="card-header text-center">EDYCJA DANYCH PACJENTA</h5>
    <div class="card-body">
    <?php 
        if(isset($USER_EDIT)){
          echo $USER_EDIT;
        }
        ?>

    </div>
</div>


<?php
}
else{
    require_once 'location.php';
    header('Location:'.$location.'index.php'); 
}
?>
