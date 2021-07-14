<?php

// Menu pacjenta

$logged = $_SESSION['username'];

echo "
<button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNavAltMarkup' aria-controls='navbarNavAltMarkup' aria-expanded='false' aria-label='Toggle navigation'>
    <span class='navbar-toggler-icon'></span>
</button>
<div class='collapse navbar-collapse' id='navbarNavAltMarkup'>
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