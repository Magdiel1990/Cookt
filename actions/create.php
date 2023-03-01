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
          
  //The page is redirected to the add-units.php
      header('Location: ../views/add-units.php');
  } 
  if (!preg_match($pattern, $unit)){
      //Message if the variable is null.
      $_SESSION['message'] = '¡Unidad incorrecta!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add-units.php
       header('Location: ../views/add-units.php');
  } 

  //lowercase the variable
    $unit = strtolower($unit);

    $sql = "SELECT unit FROM units WHERE unit = '$unit';";

    $num_rows = $conn -> query($sql) -> num_rows;

      if($num_rows != 0){
    //It already exists.
          $_SESSION['message'] = '¡Ya ha sido agregado!';
          $_SESSION['message_alert'] = "success";

    //The page is redirected to the add-units.php.
          header('Location: ../views/add-units.php');
      }
      $sql = "INSERT INTO units (unit) VALUES ('$unit')";

      if ($conn->query($sql)) {
    //Success message.
          $_SESSION['message'] = '¡Unidad agregada con éxito!';
          $_SESSION['message_alert'] = "success";
              
    //The page is redirected to the add-units.php.
          header('Location: ../views/add-units.php');

        } else {
    //Failure message.
          $_SESSION['message'] = '¡Error al agregar unidad!';
          $_SESSION['message_alert'] = "danger";
              
    //The page is redirected to the add-units.php.
          header('Location: ../views/add-units.php');
        }
    
}


/************************************************************************************************/
/***************************************CATEGORIES ADITION CODE**************************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['add_categories']) || isset($_FILES["categoryImage"])){
  $category = sanitization($_POST['add_categories'], FILTER_SANITIZE_STRING, $conn);
  $categoryImage = $_FILES["categoryImage"];

  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ\t\h]+|(^$)/"; 
  

  if ($category == "" || $categoryImage ['name'] == null){
  //Message if the variable is null.
      $_SESSION['message'] = '¡Escriba la categoría o cargue la imagen!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_units.php
      header('Location: ../views/add-categories.php');
  } 
  if (!preg_match($pattern, $category)){
      //Message if the variable is null.
      $_SESSION['message'] = '¡Categoría incorrecta!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_units.php
       header('Location: ../views/add-categories.php');
  } else {

  //lowercase the variable
    $category = strtolower($category);

    $sql = "SELECT category FROM categories WHERE category = '$category';";

    $num_rows = $conn -> query($sql) -> num_rows;
    

      if($num_rows != 0){
    //It already exists.
          $_SESSION['message'] = '¡Ya ha sido agregado!';
          $_SESSION['message_alert'] = "success";

    //The page is redirected to the add_units.php.
          header('Location: ../views/add-categories.php');
      }  
      $sql = "INSERT INTO categories (category) VALUES ('$category');";
        
      $categoryImagesDir = "../imgs/categories";
      if (!file_exists($categoryImagesDir)) {
          mkdir($categoryImagesDir, 0777, true);
      }

      $target_dir = "../imgs/categories/";
      $fileExtension = strtolower(pathinfo($categoryImage["name"], PATHINFO_EXTENSION));
      $target_file = $target_dir . $category . "." . $fileExtension;
      $uploadOk = "";
      
      // Check if image file is a actual image or fake image
      if(isset($_POST["categorySubmit"])) {
        $check = getimagesize($categoryImage["tmp_name"]);
        if($check == false) {
            $uploadOk = "¡Este archivo no es una imagen!";
        } 
      }
      // Check if file already exists
      if (file_exists($target_file)) {
        $uploadOk = "¡Esta imagen ya existe!";
      }

      // Check file size
      if ($categoryImage["size"] > 2000000) {
        $uploadOk = "¡Esta imagen ya existe!";
      }

      // Allow certain file formats
      if($fileExtension != "jpg" && $fileExtension != "png" && $fileExtension != "jpeg"
      && $fileExtension != "gif" ) {
        $uploadOk = "¡Formato no admitido!";
      } 

    if ($uploadOk == "") {
        if(move_uploaded_file($categoryImage["tmp_name"], $target_file) && $conn->query($sql)){
          //Success message.
          $_SESSION['message'] = '¡Categoría agregada con éxito!';
          $_SESSION['message_alert'] = "success";

          //The page is redirected to the add_units.php.
          header('Location: ../views/add-categories.php');    
        } else {
        //Failure message.
        $_SESSION['message'] = '¡Error al agregar categoría!';
        $_SESSION['message_alert'] = "danger";

        //The page is redirected to the add_units.php.
        header('Location: ../views/add-categories.php'); 
      }
    } else {
        //Failure message.
        $_SESSION['message'] = $uploadOk;
        $_SESSION['message_alert'] = "danger";

        //The page is redirected to the add_units.php.
        header('Location: ../views/add-categories.php'); 
    }
    }
}



/************************************************************************************************/
/***************************************INGREDIENT ADITION CODE**********************************/
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
      header('Location: ../views/add-ingredients.php');
  } 
  if(!preg_match($pattern, $ingredient)){
      //Message if the variable is null.
      $_SESSION['message'] = '¡Ingrediente incorrecto!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_units.php
      header('Location: ../views/add-ingredients.php');
  }

  //lowercase the variable
    $ingredient = strtolower($ingredient);

    $sql = "SELECT ingredient FROM ingredients WHERE ingredient = '$ingredient' AND username = '" .  $_SESSION['username'] . "';";

    $num_rows = $conn -> query($sql) -> num_rows;

      if($num_rows != 0){
      //It already exists.
          $_SESSION['message'] = '¡Ya ha sido agregado!';
          $_SESSION['message_alert'] = "success";

      //The page is redirected to the add_units.php.
          header('Location: ../views/add-ingredients.php');
      }

      $sql = "INSERT INTO ingredients (ingredient, username) VALUES ('$ingredient', '" .  $_SESSION['username'] . "');";

      if ($conn->query($sql)) {
    //Success message.
          $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
          $_SESSION['message_alert'] = "success";
              
    //The page is redirected to the add_units.php.
          header('Location: ../views/add-ingredients.php');

        } else {
    //Failure message.
          $_SESSION['message'] = '¡Error al agregar ingrediente!';
          $_SESSION['message_alert'] = "danger";
              
    //The page is redirected to the add_units.php.
          header('Location: ../views/add-ingredients.php');
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
          
  //The page is redirected to the add-recipe.php
      header('Location: ../views/add-recipe.php');
  } 
    $sql = "SELECT re_id FROM reholder WHERE ingredient = '$ingredient' AND quantity = '$quantity' AND unit = '$unit' AND username = '" .  $_SESSION['username'] . "';";

    $num_rows = $conn -> query($sql) -> num_rows;

    if($num_rows == 0) {

    $sql = "INSERT INTO reholder (ingredient, quantity, unit, username) VALUES ('$ingredient', '$quantity', '$unit', '" .  $_SESSION['username'] . "');";

      if ($conn->query($sql)) {
    //Success message.
          $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
          $_SESSION['message_alert'] = "success";
              
    //The page is redirected to the ingredients.php.
          header('Location: ../views/add-recipe.php');

        } else {
    //Failure message.
          $_SESSION['message'] = '¡Error al agregar ingrediente!';
          $_SESSION['message_alert'] = "danger";
              
    //The page is redirected to the ingredients.php.
          header('Location: ../views/add-recipe.php');
      }
    } else {
      //Success message.
          $_SESSION['message'] = '¡Ingrediente ya fue agregado!';
          $_SESSION['message_alert'] = "success";
              
      //The page is redirected to the ingredients.php.
          header('Location: ../views/add-recipe.php');
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
          
  //The page is redirected to the add-recipe.php
      header('Location: edit.php?recipename='. $recipeName);
  } else {

    
    $sql = "SELECT recipeid FROM recipe WHERE recipename = '$recipeName' AND username = '" . $_SESSION['username'] . "';";
    $row = $conn -> query($sql) -> fetch_assoc();
    $recipeId = $row['recipeid'];

    $sql = "SELECT id FROM ingredients WHERE ingredient = '$ingredient' AND username = '" . $_SESSION['username'] . "';";
    $row = $conn -> query($sql) -> fetch_assoc();
    $ingredientId = $row['id'];

    $sql = "INSERT INTO recipeinfo (recipeid, ingredientid, quantity, unit) VALUES ('$recipeId', '$ingredientId', '$quantity', '$unit');";

    if ($conn->query($sql)) {
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
          
  //The page is redirected to the add-recipe.php
      header('Location: ../views/add-recipe.php');
  } 
  if (!preg_match($pattern, $recipename)){
      //Message if the variable is null.
      $_SESSION['message'] = '¡Nombre de receta incorrecto!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_units.php
      header('Location: ../views/add-recipe.php');

  } 
  if ($cookingtime > 180 || $cookingtime < 5) {
      //Message if the variable is null.
      $_SESSION['message'] = '¡Tiempo de cocción debe estar entre 5 - 180 minutos!';
      $_SESSION['message_alert'] = "danger";
          
      //The page is redirected to the add_units.php
      header('Location: ../views/add-recipe.php');
  } 

      $_SESSION['category'] = $_POST['category'];

      $sql = "SELECT recipename FROM recipe WHERE recipename = '$recipename' AND username = '" .  $_SESSION['username'] . "';";
      
      $result = $conn -> query($sql);
      $num_rows = $result -> num_rows;
      
      if($num_rows == 0){
        
        if($cookingtime == "") { 
          $cookingtime = 0;
        }
        
      $sql = "SELECT categoryid FROM categories WHERE category = '$category';";
      
      $row= $conn -> query($sql) -> fetch_assoc();
      
      $categoryid = $row["categoryid"];

      $sql = "INSERT INTO recipe (recipename, categoryid, preparation, observation, cookingtime, username)
      VALUES ('$recipename', '$categoryid', '$preparation', '$observation', '$cookingtime', " . $_SESSION['username'] . ");";
      
      $conn -> query($sql);
      
      $sql = "SELECT * FROM reholder WHERE username = " .  $_SESSION['username'] . ";";   
      
      $result = $conn -> query($sql);    

      while($row = $result -> fetch_assoc()){
        $sql = "INSERT INTO recipeinfo (recipename, quantity, unit, ingredient, username)
        VALUES ('$recipename', " . $row["quantity"] . ", '" . $row["unit"] . "', '" . $row["ingredient"] . "', '" . $_SESSION['username'] . "');";

        $conn -> query($sql);
      }

      $sql = "DELETE FROM reholder WHERE username = '" . $_SESSION['username'] . "';";

      if ($conn -> query($sql)) {
      //Success message.
        $_SESSION['message'] = '¡Receta agregada exitosamente!';
        $_SESSION['message_alert'] = "success";

      //The page is redirected to the ingredients.php.
        header('Location: ../views/add-recipe.php');
      } else {
        //Failure message.
        $_SESSION['message'] = '¡Error al agregar receta!';
        $_SESSION['message_alert'] = "danger";
                
      //The page is redirected to the ingredients.php.
        header('Location: ../views/add-recipe.php');
      }
    } else {
      //Failure message.
        $_SESSION['message'] = '¡Esta receta ya fue agregada!';
        $_SESSION['message_alert'] = "success";
                
      //The page is redirected to the ingredients.php.
        header('Location: ../views/add-recipe.php');
    }
}

/************************************************************************************************/
/***************************************INGREDIENTS REPOSITORY CODE******************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['customingredient'])){
  $ingredient = $_POST['customingredient'];

  $sql = "SELECT id FROM ingredients WHERE ingredient = '$ingredient' AND username = '" . $_SESSION['username'] . "';";
  $row = $conn -> query($sql) -> fetch_assoc();
  $ingredientId = $row['id'];
  
  $sql = "SELECT ingredientid FROM ingholder WHERE ingredientid = $ingredientId AND username = '" . $_SESSION['username'] . "';";
  $result = $conn -> query($sql);
  $num_rows = $result -> num_rows;

  if($num_rows > 0){
  //It already exists.
      $_SESSION['message'] = '¡Ya ha sido agregado!';
      $_SESSION['message_alert'] = "success";

  //The page is redirected to the add_units.php.
      header('Location: ../views/custom-recipe.php');
  } else {

    $sql = "INSERT INTO ingholder (ingredientid, username) VALUES ($ingredientId, '" .  $_SESSION['username'] . "');";

    if ($conn->query($sql)) {
  //Success message.
        $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
        $_SESSION['message_alert'] = "success";
            
  //The page is redirected to the add_units.php.
        header('Location: ../views/custom-recipe.php');

    } else {
//Failure message.
      $_SESSION['message'] = '¡Error al agregar ingrediente!';
      $_SESSION['message_alert'] = "danger";
          
//The page is redirected to the add_units.php.
      header('Location: ../views/custom-recipe.php');
    }
  }
}


/************************************************************************************************/
/******************************************USER ADITION CODE*************************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['userfullname']) || isset($_POST['username']) || isset($_POST['userpassword']) || isset($_POST['userrol']) || isset($_POST['useremail'])){

  $fullName = sanitization($_POST['userfullname'], FILTER_SANITIZE_STRING, $conn);
  $userName=  sanitization($_POST['username'], FILTER_SANITIZE_STRING, $conn);
  $userPassword = $_POST['userpassword'];
  $userRol = $_POST['userrol'];
  $userEmail = sanitization($_POST['useremail'], FILTER_SANITIZE_EMAIL, $conn);
  $state = $_POST['activeuser'];


  if ($fullName == "" || $userName == "" || $userPassword == "") {
  //Message if the variable is null.
      $_SESSION['message'] = '¡Complete todos los campos por favor!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add-recipe.php
      header('Location: ../views/add-users.php');
  } 

  if($state == "yes") {
    $state = 1;
  } else { 
    $state = 0;
  }

  $sql = "SELECT userid FROM users WHERE fullname = '$fullName' AND username = '$userName' AND `password` = '$userPassword';";

  $num_rows = $conn -> query($sql) -> num_rows;

  if($num_rows == 0) {

  $sql = "INSERT INTO users (fullname, username, `password`, `type`, email, `state`) VALUES ('$fullName', '$userName', '$userPassword', '$userRol', '$userEmail', $state);";

    if ($conn->query($sql)) {
  //Success message.
        $_SESSION['message'] = '¡Usuario agregado con éxito!';
        $_SESSION['message_alert'] = "success";
            
  //The page is redirected to the ingredients.php.
        header('Location: ../views/add-users.php');

      } else {
  //Failure message.
        $_SESSION['message'] = '¡Error al agregar usuario!';
        $_SESSION['message_alert'] = "danger";
            
  //The page is redirected to the ingredients.php.
        header('Location: ../views/add-users.php');;
    }
  } else {
    //Success message.
        $_SESSION['message'] = '¡Este usuario ya existe!';
        $_SESSION['message_alert'] = "success";
            
    //The page is redirected to the ingredients.php.
        header('Location: ../views/add-users.php');
  }
}

?>
<?php
//Exiting the connection to the database.
$conn -> close(); 
?>