    
    <!-- Widok formularza logowania -->
    
<div class='card log'>
    <div class='card-body'>
        <form class='form-signin pt-3' action='logowanie.php' method='post'>
            <div class='form-label-group'>
                <label for='inputLogin'>Login (Email)</label>
                <input type='text' id='inputLogin' name='login' class='form-control' placeholder='login' required='required' autocomplete='off'>
            </div>
            <div class='form-label-group mt-4'>
                <label for='inputPassword'>Hasło</label>
                <input type='password' id='inputPassword' name='password' class='form-control' placeholder='hasło' required='required'>
            </div>
            <div class='form_button'>
                <button class='btn btn-lg btn-primary btn-block mt-4' type='submit'>Zaloguj się</button>
            </div>

        </form>
        <div class='flex_container mt-4'>
            <p>Nie masz jeszcze konta? </p>
            <a href='register_form.php'>Zarejestruj się</a>
        </div>

    </div>
</div>
