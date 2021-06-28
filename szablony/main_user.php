
    <?php 

    if(isset($_SESSION['username'])){
        include "clinics.php";
        $today = date("Y-m-d"); 
    
    ?> 

<div class="card mb-4">
    <h5 class="card-header text-center">Moje szczepienia</h5>
    <div class="card-body">
        <h5 class="card-title">Otrzymane szczepienia</h5>
        <div class="">
            <?php echo $USER_VACCINES; ?>
        </div>
    </div>
    <div class="card-body">
        <h5 class="card-title">Zaplanowana wizyta:</h5>
            <?php echo $USER_SAVED_TERM;?>
    </div>
</div>
<hr>

<div class="card mb-4">
    <h5 class="card-header text-center">Ustal termin wizyty</h5>
    <div class="card-body">
        <form class='select_form' action='search_terms.php' method='post'>
            <div class="mb-3">
                <label for="search_terms_clinic" class="form-label text-center">Wybierz Punkt szczepień</label>
                <select class="form-select" aria-label="Default select example" id="search_terms_clinic" name="search_terms_clinic">
                    <?php echo $CLINICS;?>  
                </select>
            </div>
            <div class="card-body mt-2">
                <div class='select_dates mb-3'>
                    <div class="mb-3 w-25">
                        <label for="search_terms_dateFrom" class="form-label">data od:</label>
                        <input type="date" min="<?php echo $today;?>" class="form-control" id="search_terms_dateFrom" name="search_terms_dateFrom">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="search_terms_dateTo" class="form-label">data do:</label>
                        <input type="date" class="form-control" id="search_terms_dateTo" name="search_terms_dateTo">
                    </div>
                </div>
                <div class='checkbox_hours'>
                    <div class="form-check mb-3">
                        <p>Wybierz godziny:</p>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="search_terms_option1" name="search_terms_option1">
                        <label class="form-check-label" for="search_terms_option1">08:00-12:00 </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="2" id="search_terms_option2" name="search_terms_option2">
                        <label class="form-check-label" for="search_terms_option2">12:00-16:00 </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="3" id="search_terms_option3" name="search_terms_option3">
                        <label class="form-check-label" for="search_terms_option3">16:00-20:00 </label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Wyszukaj</button>
        </form>
    </div>
</div>

<div class="container_content">
    <div class="card mb-4">
      <h5 class="card-header text-center">Wolne terminy</h5>
      <div class="card-body">
        <?php 
        if(isset($FREE_TERMS)){
          echo $FREE_TERMS;
        }
        ?>
      </div>
    </div>
  </div>

    <?php
    }
    else{
        require_once 'location.php';
        header('Location:'.$location.'index.php'); 
    }
?>
