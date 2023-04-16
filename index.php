<?php
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
    ];
} else {
    $routes = [
    "/cookt/recipes" => "controllers/recipes.controller.php"
    ];
}

if(array_key_exists($uri, $routes)) {
    require $routes[$uri];
} else {
    http_response_code(404);
    require "views/error_pages/404.php";
}
?>