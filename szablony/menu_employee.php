
 <?php

//  Menu pracownika

$logged = $_SESSION['empname'];


echo "
<button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNavAltMarkup' aria-controls='navbarNavAltMarkup' aria-expanded='false' aria-label='Toggle navigation'>
    <span class='navbar-toggler-icon'></span>
</button>
      <div class='collapse navbar-collapse' id='navbarNavAltMarkup'>
        <div class='navbar-nav navbar-items-to_right'>
            <form action='go_to_main.php' method='post'>
                <button class='btn-nav' type='submit'>Strona główna</button>
            </form>
            <form action='search_engine.php' method='post'>
                <button class='btn-nav' type='submit'>Wyszukiwarka</button>
            </form>
            <form action='raports.php' method='post'>
                <button class='btn-nav' type='submit'>Raporty</button>
            </form>
            <p class='user_name'>$logged</p>
            <form action='logout.php' method='post'>
                <button class='btn' type='submit'>Wyloguj się</button>
            </form>
        </div>
      </div>
    ";

