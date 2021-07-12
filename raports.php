<?php

// Przekierowanie na stronę raportów - menu

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';

$AKTYWNY = basename(__FILE__);
$TRESC = "";
$KOMUNIKAT = "";

$TRESC= array();
$TRESC[0]="szablony/raports.php";
include_once 'szablony/witryna.php';