<?php
    //Reviso el estado de la sesión.
    session_name("Login");
    
    session_start();

    //Including the database connection.
    require_once ("../config/db_Connection.php");

   $data = json_decode($_POST["datos"]);
    echo $data;

  
    $conn -> close();    
?>
