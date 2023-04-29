<?php
//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

//Set the session name
session_name("Login");

//Initializing session
session_start();

//If no user has logged in
if (!isset($_SESSION['username'])) {
    header("Location: /login");
    exit;
} else {    
//Else, last login calculation.
    $lastTime = $_SESSION["last_access"];
    $currentTime = date("Y-n-j H:i:s");

    $timeDiff = (strtotime($currentTime) - strtotime($lastTime));

//After 12 min session closes
    if ($timeDiff >= 12*60) {

//Save the user that is going to log out.
        $username = $_SESSION['username'];        

//If time runs out, the session is destroyed.
        session_destroy();  

//Set the session name
        session_name("Login");

//Start the session.
        session_start();
//Last page visited
        $_SESSION['lastpage'] = $_SERVER['REQUEST_URI'];

//Reasign the user that was logged out.
        $_SESSION['username'] = $username;

        header("Location: /login");           
    } else {
//If the user uses the page, the last time is stored.
        $_SESSION["last_access"] = $currentTime;
    }
}

//Title of the pages
$header = new PageHeaders($_SERVER["REDIRECT_URL"]);
$header = $header -> pageHeader();

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
    <meta name="ltm:domain" content="recipeholder.net">
    <meta name="description" content="Encuentra la receta de cocina fácil que estás buscando personalizadas de acuerdo a los ingredientes que tengas en tu casa.">
    <title><?php echo $header;?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="shortcut icon" href="imgs/logo/logo2.png">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@600;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/65a5e79025.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body>
    