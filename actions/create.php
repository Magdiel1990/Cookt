<?php
//Iniciating session. 
session_start();

//Including the database connection.
require_once ("../config/db_Connection.php");

//Models
require_once ("../models/models.php");

/************************************************************************************************/
/***************************************UNITS ADITION CODE**************************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['add_units'])){
  $unit = sanitization($_POST['add_units'], FILTER_SANITIZE_STRING, $conn);

  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ\t\h]+|(^$)/"; 
  

  if ($unit == ""){
  //Message if the variable is null.
      $_SESSION['message'] = '¡Escriba la unidad por favor!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_units.php
      header('Location: ../views/add_units.php');
  } elseif (!preg_match($pattern, $unit)){
      //Message if the variable is null.
      $_SESSION['message'] = '¡Unidad incorrecta!';
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
          $_SESSION['message'] = '¡Ya ha sido agregado!';
          $_SESSION['message_alert'] = "success";

    //The page is redirected to the add_units.php.
          header('Location: ../views/add_units.php');
      }  else {
      $sql = "INSERT INTO units (unit) VALUES ('$unit')";

      if ($conn->query($sql) === TRUE) {
    //Success message.
          $_SESSION['message'] = '¡Unidad agregada con éxito!';
          $_SESSION['message_alert'] = "success";
              
    //The page is redirected to the add_units.php.
          header('Location: ../views/add_units.php');

        } else {
    //Failure message.
          $_SESSION['message'] = '¡Error al agregar unidad!';
          $_SESSION['message_alert'] = "danger";
              
    //The page is redirected to the add_units.php.
          header('Location: ../views/add_units.php');
        }
    }
  }
}


/************************************************************************************************/
/***************************************INGREDIENT ADITION CODE*********************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['add_ingredient'])){
  $ingredient = sanitization($_POST['add_ingredient'], FILTER_SANITIZE_STRING, $conn);
  
  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ\t\h]+|(^$)/"; 
 

  if ($ingredient == ""){
  //Message if the variable is null.
      $_SESSION['message'] = '¡Escriba el ingrediente por favor!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_units.php
      header('Location: ../views/add_ingredients.php');
  } elseif(!preg_match($pattern, $ingredient)){
      //Message if the variable is null.
      $_SESSION['message'] = '¡Ingrediente incorrecto!';
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
          $_SESSION['message'] = '¡Ya ha sido agregado!';
          $_SESSION['message_alert'] = "success";

      //The page is redirected to the add_units.php.
          header('Location: ../views/add_ingredients.php');
      } else {

      $sql = "INSERT INTO ingredients (ingredient) VALUES ('$ingredient')";

      if ($conn->query($sql) === TRUE) {
    //Success message.
          $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
          $_SESSION['message_alert'] = "success";
              
    //The page is redirected to the add_units.php.
          header('Location: ../views/add_ingredients.php');

        } else {
    //Failure message.
          $_SESSION['message'] = '¡Error al agregar ingrediente!';
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
      $_SESSION['message'] = '¡Elija la cantidad por favor!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_recipe.php
      header('Location: ../views/add_recipe.php');
  } else {
    $sql = "SELECT re_id FROM reholder WHERE ingredient = '$ingredient' AND quantity = '$quantity' AND unit = '$unit';";

    $num_rows = $conn -> query($sql) -> num_rows;

    if($num_rows == 0) {

    $sql = "INSERT INTO reholder (ingredient, quantity, unit) VALUES ('$ingredient', '$quantity', '$unit');";

      if ($conn->query($sql) === TRUE) {
    //Success message.
          $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
          $_SESSION['message_alert'] = "success";
              
    //The page is redirected to the ingredients.php.
          header('Location: ../views/add_recipe.php');

        } else {
    //Failure message.
          $_SESSION['message'] = '¡Error al agregar ingrediente!';
          $_SESSION['message_alert'] = "danger";
              
    //The page is redirected to the ingredients.php.
          header('Location: ../views/add_recipe.php');
      }
    } else {
      //Success message.
          $_SESSION['message'] = '¡Ingrediente ya fue agregado!';
          $_SESSION['message_alert'] = "success";
              
      //The page is redirected to the ingredients.php.
          header('Location: ../views/add_recipe.php');
    }
  }
}

/************************************************************************************************/
/**************************ADD INGREDIENT (TO AN EXISTING RECIPE) CODE***************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['qty']) || isset($_POST['units']) || isset($_POST['ing']) || isset($_GET['rname'])){

  $ingredient = $_POST['ing'];
  $quantity = $_POST['qty'];
  $unit = $_POST['units'];
  $recipeName = $_GET['rname'];


  if ($quantity == "" || $quantity <= 0) {
  //Message if the variable is null.
      $_SESSION['message'] = '¡Elija la cantidad por favor!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_recipe.php
      header('Location: edit.php?recipename='. $recipeName);
  } else {

    $sql = "INSERT INTO recipeinfo (recipename, ingredient, quantity, unit) VALUES ('$recipeName', '$ingredient', '$quantity', '$unit');";

      if ($conn->query($sql) === TRUE) {
    //Success message.
          $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
          $_SESSION['message_alert'] = "success";
              
    //The page is redirected to the ingredients.php.
          header('Location: edit.php?recipename='. $recipeName);

        } else {
    //Failure message.
          $_SESSION['message'] = '¡Error al agregar ingrediente!';
          $_SESSION['message_alert'] = "danger";
              
    //The page is redirected to the ingredients.php.
          header('Location: edit.php?recipename='. $recipeName);
        }
  }
}


/************************************************************************************************/
/***************************************RECIPE ADITION CODE*************************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['recipename']) || isset($_POST['preparation']) || isset($_POST['observation']) || isset($_POST['category']) || isset($_POST['cookingtime'])){

  $recipename = sanitization($_POST['recipename'], FILTER_SANITIZE_STRING, $conn);
  $preparation = sanitization($_POST['preparation'], FILTER_SANITIZE_STRING, $conn);
  $observation = sanitization($_POST['observation'], FILTER_SANITIZE_STRING, $conn);
  $category = $_POST['category'];
  $cookingtime = sanitization($_POST['cookingtime'], FILTER_SANITIZE_NUMBER_INT, $conn);

  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ\t\h]+|(^$)/"; 

  if ($recipename == "" || $preparation == "") {
  //Message if the variable is null.
      $_SESSION['message'] = '¡Falta nombre de la receta o la preparación!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_recipe.php
      header('Location: ../views/add_recipe.php');
  } elseif (!preg_match($pattern, $recipename)){
      //Message if the variable is null.
      $_SESSION['message'] = '¡Nombre de receta incorrecto!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_units.php
      header('Location: ../views/add_recipe.php');

  } elseif ($cookingtime > 180 || $cookingtime < 5) {
      //Message if the variable is null.
      $_SESSION['message'] = '¡Tiempo de cocción debe estar entre 5 - 180 minutos!';
      $_SESSION['message_alert'] = "danger";
          
      //The page is redirected to the add_units.php
      header('Location: ../views/add_recipe.php');
  } else {

      $_SESSION['category'] = $_POST['category'];

      $sql = "SELECT recipename FROM recipe WHERE recipename = '$recipename';";
      $result = $conn -> query($sql);
      $num_rows = $result -> num_rows;
      
      if($num_rows == 0){
        
        if($cookingtime == "") { 
          $cookingtime = 0;
        }
        
      $sql = "SELECT categoryid FROM categories WHERE category = '$category';";
      $result = $conn -> query($sql);
      $row = $result -> fetch_assoc();
      $categoryid = $row["categoryid"];

      $sql = "INSERT INTO recipe (recipename, categoryid, preparation, observation, cookingtime)
      VALUES ('$recipename', '$categoryid', '$preparation', '$observation', '$cookingtime');";
      $conn -> query($sql);
      $sql = "SELECT * FROM reholder;";    
      $result = $conn -> query($sql);
      $row = $result -> fetch_assoc();    

      while($row = $result -> fetch_assoc()){
        $sql = "INSERT INTO recipeinfo (recipename, quantity, unit, ingredient)
        VALUES ('$recipename', " . $row["quantity"] . ", '" . $row["unit"] . "', '" . $row["ingredient"] . "');";

        $conn -> query($sql);
      }

      $sql = "DELETE FROM reholder /*WHERE username = 'Admin'*/;";
      $conn -> query($sql);   

      if ($conn -> query($sql) === TRUE) {
      //Success message.
        $_SESSION['message'] = '¡Receta agregada exitosamente!';
        $_SESSION['message_alert'] = "success";

      //The page is redirected to the ingredients.php.
        header('Location: ../views/add_recipe.php');
      } else {
        //Failure message.
        $_SESSION['message'] = '¡Error al agregar receta!';
        $_SESSION['message_alert'] = "danger";
                
      //The page is redirected to the ingredients.php.
        header('Location: ../views/add_recipe.php');
      }
    } else {
      //Failure message.
        $_SESSION['message'] = '¡Esta receta ya fue agregada!';
        $_SESSION['message_alert'] = "success";
                
      //The page is redirected to the ingredients.php.
        header('Location: ../views/add_recipe.php');
    }
  }
}

/************************************************************************************************/
/***************************************INGREDIENTS REPOSITORY CODE******************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['customingredient'])){
  $ingredient = $_POST['customingredient'];

  $sql = "SELECT ingredient FROM ingholder WHERE ingredient = '$ingredient';";

  $num_rows = $conn -> query($sql) -> num_rows;

  if($num_rows != 0){
  //It already exists.
      $_SESSION['message'] = '¡Ya ha sido agregado!';
      $_SESSION['message_alert'] = "success";

  //The page is redirected to the add_units.php.
      header('Location: ../views/custom_recipe.php');
  } else {

    $sql = "INSERT INTO ingholder (ingredient) VALUES ('$ingredient');";

    if ($conn->query($sql) === TRUE) {
  //Success message.
        $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
        $_SESSION['message_alert'] = "success";
            
  //The page is redirected to the add_units.php.
        header('Location: ../views/custom_recipe.php');

    } else {
//Failure message.
      $_SESSION['message'] = '¡Error al agregar ingrediente!';
      $_SESSION['message_alert'] = "danger";
          
//The page is redirected to the add_units.php.
      header('Location: ../views/custom_recipe.php');
    }
  }
}

?>
<?php
//Exiting the connection to the database.
$conn -> close(); 
?>