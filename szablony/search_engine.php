
<?php

// Widok wyszukiwarki pacjenta po numerze pesel

if(isset($_SESSION['empname']) && isset($_SESSION['emp_clinic'])){
$today = date("Y-m-d"); 

?> 

<!-- Wyszukiwarka -->

<div class="card mb-4">
    <h5 class="card-header text-center">WYSZUKIWARKA</h5>
    <div class="card-body">
        <h5 class="card-title">Wyszukaj pacjenta</h5><br>
        <form class='form-signin pt-1 select_form' action='search_engine_pesel.php' method='post'>
            <div class='select_dates mb-3'>
                <div class="form-check mb-1 search_engine_div">
                    <p class="search_engine_options">Pesel:</p>
                </div>
                <div class="form-check mb-1 search_engine_div">
                    <input type="text" minlength="11" maxlength="11" name="search_engine_pesel" autocomplete='off'>
                </div>
                <button class="btn search_engine_button" type="submit">Szukaj</button>
            </div>
        </form>
    </div>
</div>
<hr>

<!-- Wyniki wyszukiwania -->

<div class="card mb-4">
    <h5 class="card-header text-center">WYNIKI WYSZUKIWANIA</h5>
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
