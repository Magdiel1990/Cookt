<?php
//Datos de conexión a la base de datos
$host = 'localhost:3306';
$username = 'root';
$password = '123456';
$db = 'school';

$conn = new mysqli($host,$username,$password,$db);

//Cambio el codificación de caracteres a utf8
$conn -> set_charset("utf8");

//Compruebo si la conexión falla
if ($conn->connect_error) {
    die("<p>Connection failed: " . $conn->connect_error ."</p>");
}
?>
