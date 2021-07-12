<?php

// Przekierowanie na stronę główną - menu

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";

$TRESC= array();
$TRESC[0]="szablony/main_employees.php";
include_once 'szablony/witryna.php';
