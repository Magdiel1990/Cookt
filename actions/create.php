<?php
//Iniciating session. 
session_start();

//Including the database connection.
require_once ("../config/db_Connection.php");


/************************************************************************************************/
/***************************************UNITS ADDITION CODE**************************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['add_units'])){
  $unit = $_POST['add_units'];


  if ($unit == ""){
  //Message if the variable is null.
      $_SESSION['message'] = 'Escriba la unidad por favor!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_units.php
      header('Location: ../views/add_units.php');
  } else {

  //lowercase the variable
    $unit = strtolower($unit);

    $sql = "SELECT unit FROM units WHERE unit = '$unit';";

    $num_rows = $conn -> query($sql) -> num_rows;

      if($num_rows != 0){
    //It already exists.
          $_SESSION['message'] = 'Ya ha sido agregado!';
          $_SESSION['message_alert'] = "success";

    //The page is redirected to the add_units.php.
          header('Location: ../views/add_units.php');
      }  else {
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
  }
}


/************************************************************************************************/
/***************************************INGREDIENT ADDITION CODE*********************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['add_ingredient'])){
  $ingredient = $_POST['add_ingredient'];


  if ($ingredient== ""){
  //Message if the variable is null.
      $_SESSION['message'] = 'Escriba el ingrediente por favor!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_units.php
      header('Location: ../views/add_ingredients.php');
  } else {

  //lowercase the variable
    $ingredient = strtolower($ingredient);

    $sql = "SELECT ingredient FROM ingredients WHERE ingredient = '$ingredient';";

    $num_rows = $conn -> query($sql) -> num_rows;

      if($num_rows != 0){
      //It already exists.
          $_SESSION['message'] = 'Ya ha sido agregado!';
          $_SESSION['message_alert'] = "success";

      //The page is redirected to the add_units.php.
          header('Location: ../views/add_ingredients.php');
      } else {

      $sql = "INSERT INTO ingredients (ingredient) VALUES ('$ingredient')";

      if ($conn->query($sql) === TRUE) {
    //Success message.
          $_SESSION['message'] = 'Ingrediente agregado con éxito!';
          $_SESSION['message_alert'] = "success";
              
    //The page is redirected to the add_units.php.
          header('Location: ../views/add_ingredients.php');

        } else {
    //Failure message.
          $_SESSION['message'] = 'Error al agregar ingrediente!';
          $_SESSION['message_alert'] = "danger";
              
    //The page is redirected to the add_units.php.
          header('Location: ../views/add_ingredients.php');
        }
    }
  }
}







?>
<?php
//Exiting the connection to the database.
$conn -> close(); 
?>