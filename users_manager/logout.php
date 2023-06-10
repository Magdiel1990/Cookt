<?php
//Models.
require_once ("models/models.php");

//Name the session.
session_name("Login");

//Start the session
session_start();

//Destroy the session
session_unset();

session_destroy();

//Redirect to the login page
header('Location: ' . root . 'login');

die();
?>