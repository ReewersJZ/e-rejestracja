
 <?php

$logged = $_SESSION['empname'];


echo "
      <div class='collapse navbar-collapse' id='navbarNavAltMarkup'>
        <div class='navbar-nav navbar-items-to_right'>
            <a class='nav-link' href='#'>Wyszukiwarka Pacjenta</a>
            <a class='nav-link' href='#'>Raporty</a>
            <p class='user_name'>$logged</p>
            <form action='logout.php' method='post'>
                <button class='btn btn-sm' type='submit'>Wyloguj się</button>
            </form>
        </div>
      </div>
    ";

