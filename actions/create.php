<?php
//Reviso el estado de la sesión.
session_name("Login");
//Iniciating session. 
session_start();

//Si ningún usuario se ha logueado se redirige hacia el login.
if (!isset($_SESSION['userid'])) {
    header("Location: /Cookt/login.php");
    exit;
} 

//Including the database connection.
require_once ("../config/db_Connection.php");

//Models
require_once ("../models/models.php");

/************************************************************************************************/
/***************************************UNITS ADITION CODE**************************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['add_units'])){
  $filter = new Filter ($_POST['add_units'], FILTER_SANITIZE_STRING, $conn);
  $unit = $filter -> sanitization();

  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ\t\h]+|(^$)/"; 
  

  if ($unit == ""){
  //Message if the variable is null.
      $_SESSION['message'] = '¡Escriba la unidad por favor!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add-units.php
      header('Location: ../views/add-units.php');
  } else {
    if (!preg_match($pattern, $unit)){
        //Message if the variable is null.
        $_SESSION['message'] = '¡Unidad incorrecta!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add-units.php
        header('Location: ../views/add-units.php');
    } 

//lowercase the variable
  $unit = strtolower($unit);
  
  $unitCount = new Units($unit);
  $unitCount = $unitCount -> unitCount2();
                                
  if($num_rows != 0){
//It already exists.
      $_SESSION['message'] = '¡Ya ha sido agregado!';
      $_SESSION['message_alert'] = "success";

//The page is redirected to the add-units.php.
      header('Location: ../views/add-units.php');
  } else {
    // prepare and bind
    $stmt = $conn -> prepare("INSERT INTO units (unit) VALUES (?);");
    $stmt->bind_param("s", $unit);

    if ($stmt->execute()) {
  //Success message.
        $_SESSION['message'] = '¡Unidad agregada con éxito!';
        $_SESSION['message_alert'] = "success";

        $stmt->close();            
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
  }
}


/************************************************************************************************/
/***************************************CATEGORIES ADITION CODE**************************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['add_categories']) && isset($_FILES["categoryImage"])){  
  $filter = new Filter ($_POST['add_categories'], FILTER_SANITIZE_STRING, $conn);
  $category = $filter -> sanitization();

  $categoryImage = $_FILES["categoryImage"];

  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ\t\h]+|(^$)/"; 
  

  if ($category == "" || $categoryImage ['name'] == null){
  //Message if the variable is null.
      $_SESSION['message'] = '¡Escriba la categoría o cargue la imagen!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_units.php
      header('Location: ../views/add-categories.php');
  } else {
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

      $stmt = $conn -> prepare("INSERT INTO categories (category) VALUES (?);");
      $stmt->bind_param("s", $category);
        
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
      if ($categoryImage["size"] > 300000) {
          $uploadOk = "¡El tamaño debe ser menor que 300 KB!";
      }

      // Allow certain file formats
      if($fileExtension != "jpg" && $fileExtension != "png" && $fileExtension != "jpeg"
      && $fileExtension != "gif" ) {
        $uploadOk = "¡Formato no admitido!";
      } 

      if ($uploadOk == "") {
        if(move_uploaded_file($categoryImage["tmp_name"], $target_file) && $stmt -> execute()){
          //Success message.
          $_SESSION['message'] = '¡Categoría agregada con éxito!';
          $_SESSION['message_alert'] = "success";

          $stmt -> close();

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
}



/************************************************************************************************/
/***************************************INGREDIENT ADITION CODE**********************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['add_ingredient'])){
  $filter = new Filter ($_POST['add_ingredient'], FILTER_SANITIZE_STRING, $conn);
  $ingredient = $filter -> sanitization();
  
  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ\t\h]+|(^$)/"; 
 

  if ($ingredient == ""){
  //Message if the variable is null.
      $_SESSION['message'] = '¡Escriba el ingrediente por favor!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add_units.php
      header('Location: ../views/add-ingredients.php');
  } else {
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
      } else {

      $stmt = $conn -> prepare("INSERT INTO ingredients (ingredient, username) VALUES (?, ?);");
      $stmt->bind_param("ss", $ingredient, $_SESSION['username']);

      if ($stmt -> execute()) {
    //Success message.
          $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
          $_SESSION['message_alert'] = "success";

          $stmt -> close();
              
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
    }
}


/************************************************************************************************/
/********************************INGREDIENTS FOR ADDING RECIPE CODE******************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['quantity']) && isset($_POST['fraction']) && isset($_POST['unit']) && isset($_POST['ingredient']) && isset($_POST['detail'])){

  $ingredient = $_POST['ingredient'];
  $quantity = $_POST['quantity'];
  $unit = $_POST['unit'];
  $fraction = $_POST['fraction'];

  $filter = new Filter ($_POST['detail'], FILTER_SANITIZE_STRING, $conn);
  $detail = $filter -> sanitization();


  if ($quantity == "" || $quantity < 0) {
  //Message if the variable is null.
      $_SESSION['message'] = '¡Elija la cantidad por favor!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add-recipe.php
      header('Location: ../views/add-recipe.php');
  } else if ($quantity == "" && $fraction == "") {
    //Message if the variable is null.
    $_SESSION['message'] = '¡Elija la cantidad por favor!';
    $_SESSION['message_alert'] = "danger";
        
    header('Location: ../views/add-recipe.php');
  } else {
    
    if($quantity == 0){
      $strQuantity = "";
    } else {
      $strQuantity = strval($quantity) . " ";
    }

    $completeQuantity = $strQuantity . $fraction;

    $sql = "SELECT id FROM ingredients WHERE ingredient = '$ingredient' AND username = '" . $_SESSION['username'] . "';";
    $row = $conn -> query($sql) -> fetch_assoc();
    $ingredientId = $row['id'];

    $sql = "SELECT re_id FROM reholder WHERE ingredientid = '$ingredientId' AND quantity = '$completeQuantity' AND unit = '$unit' AND username = '" .  $_SESSION['username'] . "';";

    $num_rows = $conn -> query($sql) -> num_rows;

    if($num_rows == 0) {

    $stmt = $conn -> prepare("INSERT INTO reholder (ingredientid, quantity, unit, username, detail) VALUES (?, ?, ?, ?, ?);");
    $stmt->bind_param("issss", $ingredientId, $completeQuantity, $unit, $_SESSION['username'], $detail);

      if ($stmt->execute()) {
    //Success message.
          $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
          $_SESSION['message_alert'] = "success";
          $stmt -> close();
              
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
}

/************************************************************************************************/
/**************************ADD INGREDIENT (TO AN EXISTING RECIPE) CODE***************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['qty']) && isset($_POST['fraction']) && isset($_POST['units']) && isset($_POST['ing']) && isset($_GET['rname']) && isset($_GET['username']) && isset($_POST['detail'])){

  $ingredient = $_POST['ing'];
  $quantity = $_POST['qty'];
  $unit = $_POST['units'];
  $recipeName = $_GET['rname'];
  $userName = $_GET['username'];
  $fraction = $_POST['fraction'];

  $filter = new Filter ($_POST['detail'], FILTER_SANITIZE_STRING, $conn);
  $detail = $filter -> sanitization();

  if ($quantity == "" || $quantity < 0) {
    //Message if the variable is null.
    $_SESSION['message'] = '¡Elija la cantidad por favor!';
    $_SESSION['message_alert'] = "danger";
        
    header('Location: edit.php?recipename='. $recipeName . '&username=' . $userName);
  } else if ($quantity == "" && $fraction == "") {
    //Message if the variable is null.
    $_SESSION['message'] = '¡Elija la cantidad por favor!';
    $_SESSION['message_alert'] = "danger";
        
    header('Location: edit.php?recipename='. $recipeName . '&username=' . $userName);
  } else {

    if($quantity == 0){
      $strQuantity = "";
    } else {
      $strQuantity = strval($quantity) . " " ;
    }

    $completeQuantity = $strQuantity . $fraction;

    $sql = "SELECT recipeid FROM recipe WHERE recipename = '$recipeName' AND username = '$userName';";
    $row = $conn -> query($sql) -> fetch_assoc();
    $recipeId = $row['recipeid'];

    $sql = "SELECT id FROM ingredients WHERE ingredient = '$ingredient' AND username = '$userName';";
    $row = $conn -> query($sql) -> fetch_assoc();
    $ingredientId = $row['id'];

    $stmt = $conn -> prepare("INSERT INTO recipeinfo (recipeid, ingredientid, quantity, unit, detail) VALUES (?, ?, ?, ?, ?);");
    $stmt->bind_param("iisss", $recipeId, $ingredientId, $completeQuantity, $unit, $detail);

    if ($stmt->execute()) {
  //Success message.
        $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
        $_SESSION['message_alert'] = "success";

        $stmt->close();

  //The page is redirected to the ingredients.php.
        header('Location: edit.php?recipename='. $recipeName . '&username=' . $userName);

      } else {
  //Failure message.
        $_SESSION['message'] = '¡Error al agregar ingrediente!';
        $_SESSION['message_alert'] = "danger";
            
  //The page is redirected to the ingredients.php.
        header('Location: edit.php?recipename='. $recipeName . '&username=' . $userName);
    }
  }
}


/************************************************************************************************/
/***************************************RECIPE ADITION CODE*************************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['recipename']) && isset($_POST['preparation']) && isset($_FILES["recipeImage"]) && isset($_POST['category']) && isset($_POST['cookingtime'] )){

  $filter = new Filter ($_POST['recipename'], FILTER_SANITIZE_STRING, $conn);
  $recipename = $filter -> sanitization();

  $filter = new Filter ($_POST['preparation'], FILTER_SANITIZE_STRING, $conn);
  $preparation = $filter -> sanitization();

  $filter = new Filter ($_POST['cookingtime'], FILTER_SANITIZE_NUMBER_INT, $conn);
  $cookingtime = $filter -> sanitization();

  $category = $_POST['category']; 
  $recipeImage = $_FILES["recipeImage"];  

  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ\t\h]+|(^$)/"; 

  if ($recipename == "" || $preparation == "") {
  //Message if the variable is null.
      $_SESSION['message'] = '¡Falta nombre de la receta o la preparación!';
      $_SESSION['message_alert'] = "danger";
          
  //The page is redirected to the add-recipe.php
      header('Location: ../views/add-recipe.php');
  } else {
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

      $stmt = $conn -> prepare("INSERT INTO recipe (recipename, categoryid, preparation, cookingtime, username) VALUES (?, ?, ?, ?, ?);");
      $stmt->bind_param ("sisis", $recipename, $categoryid, $preparation, $cookingtime, $_SESSION['username']);

      $stmt -> execute();
      $stmt -> close();

      $sql = "SELECT recipeid FROM recipe WHERE recipename = '$recipename' AND username = '" . $_SESSION['username'] . "';";
      $row = $conn -> query($sql) -> fetch_assoc();
      $recipeId = $row['recipeid'];

      $sql = "SELECT rh.unit, rh.quantity, i.id, rh.detail FROM reholder rh JOIN ingredients i ON i.id = rh.ingredientid WHERE rh.username = '" .  $_SESSION['username'] . "';";   
      
      $result = $conn -> query($sql);    

      while($row = $result -> fetch_assoc()){
        $stmt = $conn -> prepare("INSERT INTO recipeinfo (recipeid, quantity, unit, ingredientid, detail) VALUES (?, ?, ?, ?, ?);");
        $stmt->bind_param ("sssis", $recipeId, $row["quantity"], $row["unit"], $row["id"], $row['detail']);

        $stmt -> execute();
      }

      $stmt -> close();

      if($recipeImage ['name'] == null) {

      $sql = "DELETE FROM reholder WHERE username = '" . $_SESSION['username'] . "';";

         if($conn->query($sql)){
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
      }  else {
      $sql = "DELETE FROM reholder WHERE username = '" . $_SESSION['username'] . "';";       

      $recipeImagesDir = "../imgs/recipes/". $_SESSION['username'];
        if (!file_exists($recipeImagesDir)) {
            mkdir($recipeImagesDir, 0777, true);
        }

        $fileExtension = strtolower(pathinfo($recipeImage["name"], PATHINFO_EXTENSION));
        $target_file = $recipeImagesDir ."/".  $recipename . "." . $fileExtension;
        $uploadOk = "";
        
        // Check if image file is a actual image or fake image
        if(isset($_POST["addrecipe"])) {
          $check = getimagesize($recipeImage["tmp_name"]);
          if($check == false) {
              $uploadOk = "¡Este archivo no es una imagen!";
          } 
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            $uploadOk = "¡Esta imagen ya existe!";
        }

        // Check file size
        if ($recipeImage["size"] > 300000) {
            $uploadOk = "¡El tamaño debe ser menor que 300 KB!";
        }

        // Allow certain file formats
        if($fileExtension != "jpg" && $fileExtension != "jpeg" && $fileExtension != "png" && $fileExtension != "gif") {
            $uploadOk = "¡Formato no admitido!";
        }      

        if ($uploadOk == "") {
            if(move_uploaded_file($recipeImage["tmp_name"], $target_file) && $conn->query($sql)){
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
            $_SESSION['message'] = $uploadOk;
            $_SESSION['message_alert'] = "danger";

            //The page is redirected to the ingredients.php.
            header('Location: ../views/add-recipe.php');
        }
      }
    }
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
    $stmt = $conn -> prepare("INSERT INTO ingholder (ingredientid, username) VALUES (?, ?);");
    $stmt->bind_param ("is", $ingredientId, $_SESSION['username']);

    if ($stmt -> execute()) {
  //Success message.
        $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
        $_SESSION['message_alert'] = "success";

        $stmt -> close();            
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
if(isset($_POST['firstname']) && isset($_POST['url']) && isset($_POST['lastname']) && isset($_POST['sex']) && isset($_POST['username']) && isset($_POST['userpassword']) && isset($_POST['userrol']) && isset($_POST['useremail']) && isset($_POST['session_user'])){

  $filter = new Filter ($_POST['firstname'], FILTER_SANITIZE_STRING, $conn);
  $firstname = $filter -> sanitization();

  $filter = new Filter ($_POST['lastname'], FILTER_SANITIZE_STRING, $conn);
  $lastname = $filter -> sanitization();

  $filter = new Filter ($_POST['username'], FILTER_SANITIZE_STRING, $conn);
  $username = $filter -> sanitization();
  
  $filter = new Filter ($_POST['useremail'], FILTER_SANITIZE_EMAIL, $conn);
  $email = $filter -> sanitization();

  $filter = new Filter ($_POST['userpassword'], FILTER_SANITIZE_STRING, $conn);
  $password = $filter -> sanitization();

  $filter = new Filter ($_POST['passrepeat'], FILTER_SANITIZE_STRING, $conn);
  $passrepeat = $filter -> sanitization();
  
  $sex = $_POST['sex']; 
  $terms = "yes";
  $rol = $_POST['userrol'];
  $state = $_POST['activeuser'];
  $sessionUser = $_POST['session_user'];

  $sql = "SELECT userid, `type` FROM users WHERE username ='$sessionUser';";
  $row = $conn -> query($sql) -> fetch_assoc();
  $sessionUserType = $row['type'];

  if($sessionUserType != 'Admin') {
    //The page is redirected to the add-recipe.php
        header('Location: ../error/error.php');
  } else {
    if ($firstname == "" || $lastname == "" || $username == "" || $password == ""  || $sex == "") {
    //Message if the variable is null.
        $_SESSION['message'] = '¡Complete todos los campos por favor!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add-recipe.php
        header('Location: ../views/add-users.php');
    } else {   
      if($password != $passrepeat) {
        //Message if the variable is null.
        $_SESSION['message'] = '¡Contraseñas no coinciden!';
        $_SESSION['message_alert'] = "danger";  
        
        //The page is redirected to the add-recipe.php
        header('Location: ../views/add-users.php');
            
      } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if($state == "yes") {
          $state = 1;
        } else { 
          $state = 0;
        }

        $sql = "SELECT userid FROM users WHERE firstname = '$firstname' AND lastname = '$lastname' AND username = '$username' AND `password` = '$hashed_password';";

        $num_rows = $conn -> query($sql) -> num_rows;

        if($num_rows == 0) {
          
        $stmt = $conn -> prepare("INSERT INTO users (firstname, lastname, username, `password`, `type`, email, `state`, sex) VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param ("ssssssis", $firstname, $lastname, $username, $hashed_password, $rol, $userEmail, $state, $sex);

          if ($stmt->execute()) {
        //Success message.
              $_SESSION['message'] = '¡Usuario agregado con éxito!';
              $_SESSION['message_alert'] = "success";

              $stmt->close();
                  
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
    }
  }
}

?>
<?php
//Exiting the connection to the database.
$conn -> close(); 
?>
