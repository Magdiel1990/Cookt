<?php
//Reviso el estado de la sesión.
session_name("Login");

session_start();

session_unset();

session_destroy();

header('Location: /login');

die();
?>