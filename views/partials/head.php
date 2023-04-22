<?php
//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();


//Reviso el estado de la sesión.
session_name("Login");
//Inicio una nueva sesión.
session_start();

//Si ningún usuario se ha logueado se redirige hacia el login.
if (!isset($_SESSION['username'])) {
    header("Location: /cookt/login");
    exit;
} else {    
    //Sino, calculamos el tiempo transcurrido desde la última actualización.
    $lastTime = $_SESSION["last_access"];
    $currentTime = date("Y-n-j H:i:s");
    //Se resta el tiempo de la página del login y el tiempo de esta página. 
    $timeDiff = (strtotime($currentTime) - strtotime($lastTime));

    //Compare how much time has passed.
    if ($timeDiff >= 10*60) {
        //Save the user that is going to log out.
        $username = $_SESSION['username'];

        //If time runs out, the session is destroyed.
        session_destroy();  

        //Check the state of the session.
        session_name("Login");

        //Start the session.
        session_start();

        $_SESSION['lastpage'] = substr($_SERVER['QUERY_STRING'], 5);

        //Reasign the user that was logged out.
        $_SESSION['username'] = $username;

        //Redirect the user to the login page.
        header("Location: /cookt/login");           
    } else {
        //If the user uses the page, the last time is stored.
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
    <meta name="ltm:project" content="recetas personalizadas">
    <meta property="og:type" content="website">
    <!--<meta name="ltm:domain" content="recipes23.com">-->
    <meta name="description" content="Encuentra la receta de cocina fácil que estás buscando personalizadas de acuerdo a los ingredientes que tengas en tu casa.">
    <title>Recipeholder</title> <!-- It depends where I am in the site.-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="shortcut icon" href="imgs/logo/logo.png">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@600;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/65a5e79025.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body>
    