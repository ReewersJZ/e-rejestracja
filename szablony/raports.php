
<?php

// Widok strony z raportami - pracownik


if(isset($_SESSION['empname']) && isset($_SESSION['emp_clinic'])){
$today = date("Y-m-d"); 

?> 

<!-- Wyszukiwarka -->

<div class="card mb-4">
    <h5 class="card-header text-center">RAPORTY</h5>
    <div class="card-body">
        <h5 class="card-title">Zarejestrowani pacjenci</h5><br>
        <form class='form-signin pt-1 select_form' action='raports_registered.php' method='post'>
            <input type="text" name="raports_registered_clinic_id" value="<?php echo $_SESSION['emp_clinic']; ?>" hidden>
            <div class='select_dates form_raports mb-3'>
                <div class="form-check mb-2 raports_div">
                    <p class="raports_options">Wybierz przedział dat:</p>
                </div>
                <div class="form-check mb-2 search_engine_div">
                    <input type="date" name="raports_registered_date_from">
                </div>
                <div class="form-check mb-2 search_engine_div">
                    <input type="date" name="raports_registered_date_to">
                </div>
                <button class="btn search_engine_button" type="submit">Generuj raport</button>
            </div>
        </form>
        <h5 class="card-title">Historia wizyt</h5><br>
        <form class='form-signin pt-1 select_form' action='raports_history.php' method='post'>
            <input type="text" name="raports_clinic_id" value="<?php echo $_SESSION['emp_clinic']; ?>" hidden>
            <div class='select_dates form_raports mb-3'>
                <div class="form-check mb-2 search_engine_div">
                    <p class="raports_options">Wybierz przedział dat:</p>
                </div>
                <div class="form-check mb-2 search_engine_div">
                    <input type="date" name="raports_date_from">
                </div>
                <div class="form-check mb-2 search_engine_div">
                    <input type="date" name="raports_date_to">
                </div>
                <button class="btn search_engine_button" type="submit">Generuj raport</button>
            </div>
        </form>
    </div>
</div>
<hr>

<!-- Wyniki wyszukiwania -->

<div class="card mb-4">
    <h5 class="card-header text-center">WYNIKI</h5>
    <div class="card-body">
    <?php 
        if(isset($USERS_INFO)){
          echo $USERS_INFO;
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
