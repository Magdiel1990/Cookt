<?php
//Path requested
$uri = parse_url($_SERVER["REQUEST_URI"])['path']; 

//Parameters coming with that path
$param = isset(parse_url($_SERVER["REQUEST_URI"])['query']) ? parse_url($_SERVER["REQUEST_URI"])['query'] : "";

//No parameters
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
//It comes with parameters
} else {
    $routes = [    
    "/recipes" => "controllers/recipes.controller.php",
    "/random" => "controllers/random.controller.php",
    "/delete" => "controllers/delete.controller.php",
    "/custom" => "controllers/custom.controller.php",
    "/reset" => "controllers/reset.controller.php",
    "/edit" => "controllers/edit.controller.php",
    "/update" => "controllers/update.controller.php",
    "/user-recipes" => "controllers/recipes-list.controller.php",
    "/reset-password" => "controllers/reset-password.controller.php"
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