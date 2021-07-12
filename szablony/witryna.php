
<!-- Widok strony głównej - strona bazowa -->

<!DOCTYPE html>
    <html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    <title>e-Vaccin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="../style/style.css">
</head>



<body>
<nav class='navbar sticky-top navbar-expand-lg navbar-light bg-light mb-5'>
  <a class='navbar-brand' href='#'><img class='logo_brand' src='gfx/logo_poziom.png' alt='logo'></a>
    <div id='navigation_flex' class='container-fluid'>
      <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNavAltMarkup' aria-controls='navbarNavAltMarkup' aria-expanded='false' aria-label='Toggle navigation'>
        <span class='navbar-toggler-icon'></span>
      </button>
      <?php if(isset($_SESSION['username'])){include "szablony/menu_user.php";} elseif(isset($_SESSION['empname'])){include "szablony/menu_employee.php";} ;?>
    </div>
</nav>


<!-- Blok do wyświetlania komunikatów -->
<div class="container-fluid">

  <div class="container_message">
    <?php 
    if(isset($KOMUNIKAT)){
      echo $KOMUNIKAT;
    }
        
    ?>
  </div>

  <!-- Blok do wyświetlania zawartości aplikacji-->
  <div class="container_content">
    <?php 
    if (is_array($TRESC)){
      include $TRESC[0];
    }
    else{
      echo $TRESC;
    }
    ?>
  </div>
</div>

  <footer class="">
    <?php include "szablony/footer.php";?>
  </footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
</script>
     
</body>
</html> 