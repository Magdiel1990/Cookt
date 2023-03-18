<?php
//Reviso el estado de la sesión.
session_name("Login");
//Inicio una nueva sesión.
session_start();

//Si ningún usuario se ha logueado se redirige hacia el login.
if (!isset($_SESSION['userid'])) {    
    header("Location: /Cookt/login.php");
    exit;
} else {
    //Sino, calculamos el tiempo transcurrido desde la última actualización.
    $lastTime = $_SESSION["last_access"];
    $currentTime = date("Y-n-j H:i:s");
    //Se resta el tiempo de la página del login y el tiempo de esta página. 
    $timeDiff = (strtotime($currentTime) - strtotime($lastTime));

    //Comparamos el tiempo transcurrido.
    if ($timeDiff >= 5) {
        //Si pasa del tiempo establecido se destruye la sesión.
        session_destroy();

        session_start();

        $_SESSION['lastpage'] = $_SERVER['PHP_SELF'];

        //Envío al usuario a la página de login.
        header("Location: /Cookt/login.php");        

        //Sino, actualizo la fecha de la sesión.
    } else {
        $_SESSION["last_access"] = $currentTime;
    }
}

?>
<!DOCTYPE html>
<html lang="es" data-lt-installed="true">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Magdiel Castillo Mills">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="Keywords" content="receta, recipe, cocina, kitchen, sugerencias, recommendations">
    <meta name="ltm:project" content="recetaspersonalizadas">
    <meta property="og:type" content="website">
    <!--<meta name="ltm:domain" content="recipes23.com">-->
    <meta name="description" content="Encuentra la receta de cocina fácil que estás buscando personalizadas de acuerdo a los ingredientes que tengas en tu casa.">
    <title>Recipes23</title> <!-- It depends where I am in the site.-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="shortcut icon" href="/Cookt/imgs/logo/logo.png">
    <link rel="stylesheet" href="/Cookt/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@600;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/65a5e79025.js" crossorigin="anonymous"></script>
    <script src="/Cookt/js/scripts.js"></script>    
</head>
<body>
    