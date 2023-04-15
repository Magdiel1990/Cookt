<?php
$uri = parse_url($_SERVER["REQUEST_URI"])['path'];


$routes = [
"/cookt/" => "controllers/index.controller.php",
"/cookt/login" => "controllers/login.controller.php",
"/cookt/logout" => "controllers/logout.controller.php"
];

if(array_key_exists($uri, $routes)) {
    require $routes[$uri];
} else {
    http_response_code(404);

    require "controllers/index.controller.php";
}
?>