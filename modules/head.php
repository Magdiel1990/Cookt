<?php
//Iniciating session. 
session_start();

$_SESSION['username'] = "Admin";
$_SESSION['type'] = "Admin";
$_SESSION['state'] = 1;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <title>R3CP</title>
    <link rel="shortcut icon" href="../imgs/logo/logo.png">
    <link rel="stylesheet" href="./css/styles.css">
    <script src="https://kit.fontawesome.com/65a5e79025.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>   
</head>
<body>