<?php

// Wylogowanie i "zabicie" sesji użytkownika

session_start();

require_once 'szablony/location.php';

if (isset($_SESSION['username'])) {
    unset($_SESSION['username']);
    header('Location:'.$location.'index.php'); 
}
elseif(isset($_SESSION['empname'])){
    unset($_SESSION['empname']);
    header('Location:'.$location.'index.php'); 
}
else{
    header('Location:'.$location.'index.php'); 
}

