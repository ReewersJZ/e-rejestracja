<?php

// Przekierowanie do widoku wyszukiwarki - menu

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";

$TRESC= array();
$TRESC[0]="szablony/search_engine.php";
include_once 'szablony/witryna.php';

