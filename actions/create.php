<?php
//Iniciating session. 
session_start();

//Including the database connection.
require_once ("../config/db_Connection.php");

//receive the data
$unit= isset($_POST['add']) ? $conn -> real_escape_string($_POST['add']) : null;

if ($unit == ""){
//Message if the variable is null.
    $_SESSION['message'] = 'Escriba la unidad por favor!';
    $_SESSION['message_alert'] = "danger";
        
//The page is redirected to the add_units.php
    header('Location: ../views/add_units.php');
} else {

//lowercase the variable
  $unit = strtolower($unit);

  $sql = "INSERT INTO units (unit) VALUES ('$unit')";

  if ($conn->query($sql) === TRUE) {
//Success message.
      $_SESSION['message'] = 'Unidad agregada con éxito!';
      $_SESSION['message_alert'] = "success";
          
//The page is redirected to the add_units.php.
      header('Location: ../views/add_units.php');

    } else {
//Failure message.
      $_SESSION['message'] = 'Error al agregar unidad!';
      $_SESSION['message_alert'] = "danger";
          
//The page is redirected to the add_units.php.
      header('Location: ../views/add_units.php');
    }
}
?>
<?php
//Exiting the connection to the database.
$conn -> close(); 
?>