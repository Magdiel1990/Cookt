<?php
//Inicio sesión.
session_start();
//Destruyo la sesión.
unset($_SESSION["id"]);
unset($_SESSION["name"]);
unset($_SESSION["username"]);
unset($_SESSION['sexo']);
//Redirecciono a la página de login.
header("Location:Login.php");
die();
?>