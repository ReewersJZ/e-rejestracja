<div class='card log'>
    <div class='card-body'>
        <form class='form-signin pt-3' action='verify_user.php' method='post'>
            <div class='form-label-group'>
                <label for='inputLogin_verify'>Login (Email)</label>
                <input type='text' id='inputLogin_verify' name='inputLogin_verify' class='form-control' placeholder='login' required='required' autocomplete='off'>
            </div>
            <div class='form-label-group mt-4'>
                <label for='inputPassword_verify'>Hasło</label>
                <input type='password' id='inputPassword_verify' name='inputPassword_verify' class='form-control' placeholder='hasło' required='required'>
            </div>
            <div class='form-label-group mt-4'>
                <label for='inputCode_verify'>Kod weryfikacyjny</label>
                <input type='text' id='inputCode_verify' name='inputCode_verify' class='form-control' placeholder='' required='required'>
            </div>
            <div class='form_button'>
                <button class='btn btn-lg btn-primary btn-block mt-4' type='submit'>Prześlij</button>
            </div>

        </form>
    </div>
</div>