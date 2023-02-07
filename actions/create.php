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


/************************************************************************************************/
/**********************************FULL INGREDIENT DESCRIPTION CODE******************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['quantity']) || isset($_POST['unit']) || isset($_POST['ingredient'])){

  $ingredient = $_POST['ingredient'];
  $quantity = $_POST['quantity'];
  $unit = $_POST['unit'];


  if ($quantity == "" || $quantity <= 0) {
  //Message if the variable is null.
      $_SESSION['message'] = 'Elija la cantidad por favor!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_recipe.php
      header('Location: ../views/add_recipe.php');
  } else {

    $sql = "INSERT INTO reholder (ingredient, quantity, unit) VALUES ('$ingredient', '$quantity', '$unit');";

      if ($conn->query($sql) === TRUE) {
    //Success message.
          $_SESSION['message'] = 'Ingrediente agregado con éxito!';
          $_SESSION['message_alert'] = "success";
              
    //The page is redirected to the ingredients.php.
          header('Location: ../views/add_recipe.php');

        } else {
    //Failure message.
          $_SESSION['message'] = 'Error al agregar ingrediente!';
          $_SESSION['message_alert'] = "danger";
              
    //The page is redirected to the ingredients.php.
          header('Location: ../views/add_recipe.php');
        }
  }
}


/************************************************************************************************/
/***************************************RECIPE ADDITION CODE*************************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['recipename']) || isset($_POST['preparation']) || isset($_POST['observation']) || isset($_POST['category']) || isset($_POST['cookingtime'])){

  $recipename = $_POST['recipename'];
  $preparation = $_POST['preparation'];
  $observation = $_POST['observation'];
  $category = $_POST['category'];
  $cookingtime = $_POST['cookingtime'];

  if ($recipename == "" || $preparation == "") {
  //Message if the variable is null.
      $_SESSION['message'] = 'Falta nombre de la receta o la preparación!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_recipe.php
      header('Location: ../views/add_recipe.php');
  } else {

    $sql = "SELECT categoryid FROM categories WHERE category = '$category'";

    $result = $conn -> query($sql);

    $row = $result -> fetch_assoc();

    $categoryid = $row["categoryid"];

    $sql = "INSERT INTO recipe (recipename, categoryid, preparation, observation, cookingtime)
    VALUES ('$recipename', $categoryid, '$preparation', '$observation', $cookingtime);";

    $result = $conn -> query($sql);

    $sql = "SELECT * FROM reholder;";
    
    $result = $conn -> query($sql);

    $quantity =  $row["quantity"];
    $unit = $row["unit"];
    $ingredient = $row["ingredient"];


    while($row = $result -> fetch_assoc()) {
      $sql = "INSERT INTO recipeinfo (recipename, quantity, unit, ingredient) VALUES ('$recipename', $quantity, '$unit', '$ingredient';";

      $result = $conn -> query($sql);
    }

    $sql = "DELETE FROM reholder;";

    $result = $conn -> query($sql);   

    /*  if ($conn->query($sql) === TRUE) {
    //Success message.
          $_SESSION['message'] = 'Ingrediente agregado con éxito!';
          $_SESSION['message_alert'] = "success";
              
    //The page is redirected to the ingredients.php.
          header('Location: ../views/add_recipe.php');

        } else {
    //Failure message.
          $_SESSION['message'] = 'Error al agregar ingrediente!';
          $_SESSION['message_alert'] = "danger";
              
    //The page is redirected to the ingredients.php.
          header('Location: ../views/add_recipe.php');
        }*/
  }
}







?>
<?php
//Exiting the connection to the database.
$conn -> close(); 
?>