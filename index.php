<?php
//Head
require_once ("views/partials/head.php");

//Path requested
$uri = parse_url($_SERVER["REQUEST_URI"])['path']; 

//Parameters coming with that path
$param = isset(parse_url($_SERVER["REQUEST_URI"])['query']) ? parse_url($_SERVER["REQUEST_URI"])['query'] : "";

//No parameters
if($param == "") {
    $routes = [
    root => "controllers/index.controller.php",    
    root. "login" => "controllers/login.controller.php",
    root. "logout" => "controllers/logout.controller.php",
    root. "random" => "controllers/random.controller.php",
    root. "custom" => "controllers/custom.controller.php",
    root. "profile" => "controllers/profile.controller.php",
    root. "ingredients" => "controllers/ingredients.controller.php",
    root. "add-recipe" => "controllers/add-recipe.controller.php",
    root. "categories" => "controllers/categories.controller.php",
    root. "user" => "controllers/users.controller.php",
    root. "create" => "controllers/create.controller.php",
    root. "email" => "controllers/email.controller.php",
    root. "signup" => "controllers/signup.controller.php",
    root. "recovery" => "controllers/recovery.controller.php",
    root. "error404" => "controllers/404.controller.php"
    ];
//It comes with parameters
} else {
    $routes = [    
    root. "recipes" => "controllers/recipes.controller.php",
    root. "random" => "controllers/random.controller.php",
    root. "delete" => "controllers/delete.controller.php",
    root. "custom" => "controllers/custom.controller.php",
    root. "reset" => "controllers/reset.controller.php",
    root. "edit" => "controllers/edit.controller.php",
    root. "update" => "controllers/update.controller.php",
    root. "user-recipes" => "controllers/recipes-list.controller.php",
    root. "reset-password" => "controllers/reset-password.controller.php"
    ];
}

//If the uri exists the controllers is called
if(array_key_exists($uri, $routes)) {
//If the recipes page is called, the parameters are stored in an array
    if($routes[$uri] == "controllers/recipes.controller.php") {      
    $paramArray = explode("&", $param);

//If there are not two parameters an error is sent
        if(count($paramArray)!=2){
            http_response_code(404);
            require "views/error_pages/404.php";
//If there are two parameters and are not username nor recipe an error is sent        
        } else {
            if(strpos($param, "&username=") !== false && strpos($param, "recipe=") !== false){
                require $routes[$uri];
            } else {
                http_response_code(404);
                require "views/error_pages/404.php";
            }
        }
//Any other page is called directly
    } else {
        require $routes[$uri];
    }
//If the path does not exist an error is sent

} else {
    http_response_code(404);
    require "views/error_pages/404.php";
}
?>