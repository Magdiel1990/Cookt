<?php
$hostname = "Localhost:3306";
$username = "root";
$password = "123456";
$database = "foodbase";

//Connection to the database.
    
$conn = new mysqli($hostname, $username, $password, $database);
    
// Check connection
if ($conn->connect_error) {
    die("Error en conexión: " . $conn->connect_error);
}
?>