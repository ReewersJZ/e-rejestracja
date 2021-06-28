
<?php 

if(isset($_SESSION['empname']) && isset($_SESSION['emp_clinic'])){
$today = date("Y-m-d"); 

?> 

<div class="card mb-4">
    <h5 class="card-header text-center">TERMINY</h5>
    <div class="card-body">
        <h5 class="card-title">Dodaj wolne terminy</h5><br>
        <form class='form-signin pt-1 select_form' action='clterms_add.php' method='post'>
                <div class='select_dates mb-3'>
                    <div class="form-check mb-1">
                        <p>Wybierz dzień:</p>
                    </div>
                    <div class="form-check mb-1">
                        <input type="date" min="<?php echo $today;?>" name="clterms_date">
                    </div>
                </div>
                <div class="form-check mb-1">
                    <input class="" type="text" value="<?php echo $_SESSION['emp_clinic']?>" id="clterms_clinic" name="clterms_clinic" hidden>
                </div>
                
                <div class='checkbox_hours mb-1'>
                    <div class="form-check">
                        <p>Wybierz godziny</p>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="clterms_option1" name="clterms_option1">
                        <label class="form-check-label" for="clterms_option1">08:00-12:00 </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="2" id="clterms_option2" name="clterms_option2">
                        <label class="form-check-label" for="clterms_option2">12:00-16:00 </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="3" id="clterms_option3" name="clterms_option3">
                        <label class="form-check-label" for="clterms_option3">16:00-20:00 </label>
                    </div>
                </div>

                <br>
                <button class="btn" type="submit">Zapisz</button>
            </div>
        </form>
    </div>
</div>
<hr>
<div class="card mb-4">
    <h5 class="card-header text-center">Lista dzisiejszych wizyt</h5>
    <div class="card-body">
    <?php 
        include "search_users_terms.php";

        if(isset($USERS_TERMS)){
          echo $USERS_TERMS;
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
