<?php
$uri = parse_url($_SERVER["REQUEST_URI"])['path']; 
$param = isset(parse_url($_SERVER["REQUEST_URI"])['query']) ? parse_url($_SERVER["REQUEST_URI"])['query'] : "";

if($param == "") {
    $routes = [
    "/" => "controllers/index.controller.php",    
    "/login" => "controllers/login.controller.php",
    "/logout" => "controllers/logout.controller.php",
    "/random" => "controllers/random.controller.php",
    "/custom" => "controllers/custom.controller.php",
    "/profile" => "controllers/profile.controller.php",
    "/ingredients" => "controllers/ingredients.controller.php",
    "/add-recipe" => "controllers/add-recipe.controller.php",
    "/categories" => "controllers/categories.controller.php",
    "/user" => "controllers/users.controller.php",
    "/create" => "controllers/create.controller.php",
    "/email" => "controllers/email.controller.php",
    "/signup" => "controllers/signup.controller.php",
    "/recovery" => "controllers/recovery.controller.php",
    "/error404" => "controllers/404.controller.php"
    ];
} else {
    $routes = [
    "/recipes" => "controllers/recipes.controller.php",
    "/random" => "controllers/random.controller.php",
    "/delete" => "controllers/delete.controller.php",
    "/custom" => "controllers/custom.controller.php",
    "/edit" => "controllers/edit.controller.php",
    "/update" => "controllers/update.controller.php",
    "/user-recipes" => "controllers/recipes-list.controller.php",
    "/reset-password" => "controllers/reset-password.controller.php"
    ];
}

if(array_key_exists($uri, $routes)) {
    if($routes[$uri] == "controllers/recipes.controller.php") {    
    $paramArray = explode("&", $param);

        if(count($paramArray)!=2){
            http_response_code(404);
            require "views/error_pages/404.php";
        } else {
            if(strpos($param, "&username=") !== false && strpos($param, "recipe=") !== false){
                require $routes[$uri];
            } else {
                http_response_code(404);
                require "views/error_pages/404.php";
            }
        }
    } else {
        require $routes[$uri];
    }
} else {
    http_response_code(404);
    require "views/error_pages/404.php";
}
?>