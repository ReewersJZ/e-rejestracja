<?php

$TRESC = "
<div class='card log'>
    <div class='card-body'>
        <form class='form-signin pt-3' action='register.php' method='post'>
            <div class='form-label-group'>
                <label for='form_register_name'>Imię</label>
                <input type='text' id='form_register_name' name='form_register_name' class='form-control'  required='required' autocomplete='off'>
            </div>
            <div class='form-label-group mt-4'>
                <label for='form_register_surname'>Nazwisko</label>
                <input type='text' id='form_register_surname' name='form_register_surname' class='form-control'  required='required' autocomplete='off'>
            </div>
            <div class='form-label-group mt-4'>
                <label for='form_register_mail'>E-mail</label>
                <input type='mail' id='form_register_mail' name='form_register_mail' class='form-control' placeholder='np. jan.kowalski@o2.pl' required='required' autocomplete='off'>
            </div>
            <div class='form-label-group mt-4'>
                <label for='form_register_password'>Hasło</label>
                <input type='password' id='form_register_password' name='form_register_password' class='form-control'  required='required'>
            </div>
            <div class='form-label-group mt-4'>
                <label for='form_register_phone'>Numer telefonu</label>
                <input type='tel' id='form_register_phone' name='form_register_phone' class='form-control'  required='required'>
            </div>
            <div class='form-label-group mt-4'>
                <label for='form_register_pesel'>PESEL</label>
                <input type='text' maxlength='11' id='form_register_pesel' name='form_register_pesel' class='form-control' required='required'>
            </div>

            <div class='form-check mt-4'>
                <input type='checkbox' class='form-check-input' id='agreement_rodo'>
                <small>
                    <label class='form-check-label' for='agreement_rodo'>Zgoda na przetwarzanie danych osobowych według RODO.</label>
                </small>
            </div>

            <div class='form_button mt-4 pb-3'>
                <button class='btn btn-lg btn-primary btn-block' type='submit'>Zarejestruj się</button>
            </div>
        </form>
    </div>
</div>";


include_once 'szablony/witryna.php';