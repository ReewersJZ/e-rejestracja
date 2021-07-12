<?php

// Menu pacjenta

$logged = $_SESSION['username'];

echo "<div class='collapse navbar-collapse' id='navbarNavAltMarkup'>
        <div class='navbar-nav'>
          <div class='navbar-items-to_right'>
            <p class='user_name'>$logged</p>
          </div>
          <div>
          <form action='logout.php' method='post'>
            <button class='btn btn-sm' type='submit'>Wyloguj się</button>
          </form>
          </div>
        </div>
      </div>
      ";