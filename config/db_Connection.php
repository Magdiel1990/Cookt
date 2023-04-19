<?php
$hostname = "localhost";
$username = "root";
$password = "123456";
$database = "foodbase";

/*$hostname = "localhost";
$username = "u743896838_magdiel";
$password = ">Af=jh8E";
$database = "u743896838_foodbase";
*/
//Connection to the database.
    
$conn = new mysqli($hostname, $username, $password, $database);
    
// Check connection
if ($conn->connect_error) {
    die("Error en conexión: " . $conn->connect_error);
}
?>