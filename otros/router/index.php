<?php
require "functions.php";

$uri = parse_url($_SERVER["REQUEST_URI"])['path'];

dd($_SERVER);

$routes = [
"/router/" => "controllers/index.php",
"/router/contact" => "controllers/contact.php",
"/router/about" => "controllers/about.php"
];

if(array_key_exists($uri, $routes)) {
    require $routes[$uri];
} else {
    http_response_code(404);

    echo "ERROR";

    die();
}

echo substr($_SERVER['QUERY_STRING'], 5);


?>