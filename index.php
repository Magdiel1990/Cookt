<?php
//Reviso el estado de la sesión.
session_name("Login");

//Inicio una nueva sesión.
session_start();

$_SESSION['userid'] = 1;
$_SESSION['firstname'] = "Magdiel";
$_SESSION['lastname'] = "Castillo";
$_SESSION['username'] = "Admin";
$_SESSION['type'] = "Admin";
$_SESSION['email'] = "magdielmagdiel1@gmail.com";
$_SESSION['state'] = 1;  
$_SESSION['title'] = "Sr. ";  



$uri = parse_url($_SERVER["REQUEST_URI"])['path']; 
$param = isset(parse_url($_SERVER["REQUEST_URI"])['query']) ? parse_url($_SERVER["REQUEST_URI"])['query'] : "";

if($param == "") {
    $routes = [
    "/cookt" => "controllers/index.controller.php",
    "/cookt/" => "controllers/index.controller.php",
    "/cookt/login" => "controllers/login.controller.php",
    "/cookt/logout" => "controllers/logout.controller.php",
    "/cookt/random" => "controllers/random.controller.php",
    "/cookt/custom" => "controllers/custom.controller.php",
    "/cookt/profile" => "controllers/profile.controller.php",
    "/cookt/units" => "controllers/units.controller.php",
    "/cookt/ingredients" => "controllers/ingredients.controller.php",
    "/cookt/add-recipe" => "controllers/add-recipe.controller.php",
    "/cookt/categories" => "controllers/categories.controller.php",
    "/cookt/user" => "controllers/users.controller.php",
    "/cookt/create" => "controllers/create.controller.php",
    "/cookt/email" => "controllers/email.controller.php",
    "/cookt/signup" => "controllers/signup.controller.php",
    "/cookt/recovery" => "controllers/recovery.controller.php",
    "/cookt/error404" => "controllers/404.controller.php"
    ];
} else {
    $routes = [
    "/cookt/recipes" => "controllers/recipes.controller.php",
    "/cookt/random" => "controllers/random.controller.php",
    "/cookt/delete" => "controllers/delete.controller.php",
    "/cookt/custom" => "controllers/custom.controller.php",
    "/cookt/edit" => "controllers/edit.controller.php",
    "/cookt/update" => "controllers/update.controller.php",
    "/cookt/user/recipes" => "controllers/recipes-list.controller.php",
    "/cookt/reset-password" => "controllers/reset-password.controller.php"
    ];
}

if(array_key_exists($uri, $routes)) {
    require $routes[$uri];
} else {
    http_response_code(404);
    require "views/error_pages/404.php";
}
?>