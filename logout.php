<?php
//Reviso el estado de la sesión.
session_name("Login");

session_start();

unset($_SESSION['userid']);

header('Location: /Cookt/login.php');

die();
?>