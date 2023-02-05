<?php
$hostname = "Localhost:3306";
$username = "root";
$password = "123456";
$database = "foodbase";

/*try {
    $conn = new PDO("mysql:host=$hostname; dbname=$database", $username, $password);
    //set the PDO error mode to exception
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e -> getMessage();
}*/

//Connection to the database.
    
$conn = new mysqli($hostname, $username, $password, $database);
    
// Check connection
if ($conn->connect_error) {
    die("Error en conexión: " . $conn->connect_error);
}
?>