<?php
//Name the session
session_name("Login");

//Iniciating session. 
session_start();

//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

/************************************************************************************************/
/***************************************CATEGORIES ADITION CODE**************************************/
/************************************************************************************************/

//receive the data
if(isset($_POST['add_categories']) && isset($_FILES["categoryImage"])){  
  $filter = new Filter ($_POST['add_categories'], FILTER_SANITIZE_STRING);
  $category = $filter -> sanitization();

  $categoryImage = $_FILES["categoryImage"];

//Input validation object  
  $inputs = ["La categoría" => [$category, [2,20], "incorrecta", true], 
  "La imagen de la categoría" => [$categoryImage ['name'], [], "incorrecta", true]];

  $message = new InputValidation ($inputs, "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]/");  
  $message = $message -> lengthValidation();

    if(count($message) > 0) {
      $_SESSION['message'] = $message [0];
      $_SESSION['message_alert'] = $message [1];          

      header('Location: ' . root . 'categories');
      exit;
    }  
//lowercase the variable
  $category = strtolower($category);
//Check if the category had been added
  $result = $conn -> query("SELECT category FROM categories WHERE category = '$category' AND state = 1;");
  $num_rows = $result -> num_rows;      

  if($num_rows != 0){
      $_SESSION['message'] = '¡Ya ha sido agregado!';
      $_SESSION['message_alert'] = "success";

      header('Location: ' . root . 'categories');
      exit;
  }  
  
  $stmt = $conn -> prepare("INSERT INTO categories (category) VALUES (?);");
  $stmt->bind_param("s", $category);
    
  $categoryImagesDir = "../imgs/categories";
  if (!file_exists($categoryImagesDir)) {
      mkdir($categoryImagesDir, 0777, true);
  }

  $target_dir = "../imgs/categories/";
  $ext = strtolower(pathinfo($categoryImage["name"], PATHINFO_EXTENSION));
  $target_file = $target_dir . $category . "." . $ext;

  $categorySubmit = isset($_POST["categorySubmit"]) ? $_POST["categorySubmit"] : 0;

  $admittedFormats = ["jpg"];

//Image verification        
  $uploadOk = new ImageVerif($categorySubmit, $categoryImage["tmp_name"], $target_file, 300000, $categoryImage["size"], $admittedFormats, $ext);
  $uploadOk = $uploadOk -> file_extention();   

  if ($uploadOk == null) {
    if(move_uploaded_file($categoryImage["tmp_name"], $target_file) && $stmt -> execute()){
//Notification message        
      $log_message = "Has creado la categoría \"" . $category . "\".";       
      $type = "add";
      
      if($_SESSION['notification'] == 1) {
        $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
      }

      $_SESSION['message'] = '¡Categoría agregada con éxito!';
      $_SESSION['message_alert'] = "success";

      $stmt -> close();

      header('Location: ' . root . 'categories');  
      exit;  
    } else {
    $_SESSION['message'] = '¡Error al agregar categoría!';
    $_SESSION['message_alert'] = "danger";

    header('Location: ' . root . 'categories');
    exit;
    }
  } else {
    $_SESSION['message'] = $uploadOk;
    $_SESSION['message_alert'] = "danger";

    header('Location: ' . root . 'categories');
    exit;
  }
}
/************************************************************************************************/
/***************************************INGREDIENT ADITION CODE**********************************/
/************************************************************************************************/

//receive the data
if(isset($_POST['add_ingredient'])){
  $filter = new Filter ($_POST['add_ingredient'], FILTER_SANITIZE_STRING);
  $ingredient = $filter -> sanitization();

//Input validation object  
  $inputs = ["El ingrediente" => [$ingredient, [2,50], "incorrecto", true]];

  $message = new InputValidation ($inputs, "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]/");  
  $message = $message -> lengthValidation();

    if(count($message) > 0) {
      $_SESSION['message'] = $message [0];
      $_SESSION['message_alert'] = $message [1];          

      header('Location: ' . root . 'ingredients');
      exit;
    } 
//lowercase the variable
  $ingredient = strtolower($ingredient);

  $result = $conn -> query("SELECT ingredient FROM ingredients WHERE ingredient = '$ingredient' AND username = '" .  $_SESSION['username'] . "' AND state = 1;");
  $num_rows = $result -> num_rows;

//Check if it already exists
  if($num_rows != 0){
      $_SESSION['message'] = '¡Ya ha sido agregado!';
      $_SESSION['message_alert'] = "success";

      header('Location: ' . root . 'ingredients');
      exit;
  } else {
  $stmt = $conn -> prepare("INSERT INTO ingredients (ingredient, username) VALUES (?, ?);");
  $stmt->bind_param("ss", $ingredient, $_SESSION['username']);

  if ($stmt -> execute()) {
//Notification message        
      $log_message = "Has creado el ingrediente \"" . $ingredient . "\".";       
      $type = "add";

//Verify the settings
      if($_SESSION['notification'] == 1) {
        $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
      }

      $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
      $_SESSION['message_alert'] = "success";

      $stmt -> close();
      header('Location: ' . root . 'ingredients');
      exit;
    } else {
      $_SESSION['message'] = '¡Error al agregar ingrediente!';
      $_SESSION['message_alert'] = "danger";
          
      header('Location: ' . root . 'ingredients');
      exit;
    }
  }
}

/************************************************************************************************/
/***************************************RECIPE ADITION CODE*************************************/
/************************************************************************************************/

//receive the data
if(isset($_POST["recipename"]) && isset($_POST["imageUrl"]) && isset($_FILES["recipeImage"]) && isset($_POST['category']) && isset($_POST['cookingtime']) && isset($_POST['ingredients']) && isset($_POST['preparation'])){

//Data sanitization
  $filter = new Filter ($_POST['recipename'], FILTER_SANITIZE_STRING);
  $recipename = $filter -> sanitization();

  $filter = new Filter ($_POST['preparation'], FILTER_SANITIZE_STRING);
  $preparation = $filter -> sanitization();

  $filter = new Filter ($_POST['cookingtime'], FILTER_SANITIZE_NUMBER_INT);
  $cookingtime = $filter -> sanitization();

  $filter = new Filter ($_POST['ingredients'], FILTER_SANITIZE_STRING);
  $ingredients = $filter -> sanitization();

  $category = $_POST['category']; 
  $recipeImage = $_FILES["recipeImage"];  

//Input validation object  
  $inputs = ["La receta" => [$recipename, [7,50], "incorrecta", true], 
  "Los ingredientes" => [$ingredients, [], "incorrectos", false],
  "La preparación" => [$preparation, [], "incorrecta", false],   
  "El tiempo de cocción" => [$cookingtime, [5,180], "incorrecto", false]];

  $message = new InputValidation ($inputs, "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]/");  
  $message = $message -> lengthValidation();

    if(count($message) > 0) {
      $_SESSION['message'] = $message [0];
      $_SESSION['message_alert'] = $message [1];          

      header('Location: ' . root . 'add-recipe');
      exit;
    } 

    $_SESSION['category'] = $category;

    $result = $conn -> query("SELECT recipename FROM recipe WHERE recipename = '$recipename' AND username = '" .  $_SESSION['username'] . "' AND state = 1;");
    $num_rows = $result -> num_rows;
//Check if the recipe exists            
    if($num_rows == 0){
//Get the category id        
    $stmt = $conn -> prepare("SELECT categoryid FROM categories WHERE category = ? AND state = 1;"); 
    $stmt -> bind_param("s", $category);
    $stmt -> execute();

    $result = $stmt -> get_result(); 
    $row = $result -> fetch_assoc();       
    
    $categoryid = $row["categoryid"];

    $stmt = $conn -> prepare("INSERT INTO recipe (recipeid, ingredients, preparation, cookingtime, recipename, categoryid, username) VALUES (?, ?, ?, ?, ?, ?, ?);");
    $stmt->bind_param ("issisis", $recipeId, $ingredients, $preparation, $cookingtime, $recipename, $categoryid, $_SESSION['username']);
    
    $stmt -> execute();
    $stmt -> close(); 

//Notification message        
    $log_message = "Has creado la receta \"" . $recipename . "\".";       
    $type = "add";
    
    if($_SESSION['notification'] == 1) {
      $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
    }

//If no image has been added
      if ($recipeImage ['name'] == null && $_POST["imageUrl"] == "") {           
      $_SESSION['message'] = '¡Receta agregada exitosamente!';
      $_SESSION['message_alert'] = "success";

      header('Location: ' . root . 'add-recipe');
      exit;
      } else if ($_POST["imageUrl"] != "") {
// Remote image URL Sanitization   
        $filter = new Filter ($_POST["imageUrl"], FILTER_SANITIZE_URL);
        $url = $filter -> sanitization();
//Url existance verification          
        $URLVerif = new UrlVerification ($url);
        $URLVerif = $URLVerif -> urlVerif();
        
        if($URLVerif === false) {
//Notification message        
          $log_message = "Has creado la receta \"" . $recipename . "\".";       
          $type = "add";
          if($_SESSION['notification'] == 1) {
            $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
          }

          $_SESSION['message'] = '¡Receta agregada exitosamente sin imagen!';
          $_SESSION['message_alert'] = "success";

          header('Location: ' . root . 'add-recipe');
          exit;        
        } else {
// Image path
        $recipeImagesDir = "imgs/recipes/". $_SESSION['username'] ."/";

          if (!file_exists($recipeImagesDir)) {
            mkdir($recipeImagesDir, 0777, true);
          }        
//Delete an old image if it exists
        $files = new Directories($recipeImagesDir, $recipename);
        $ext = $files -> directoryFiles();

        if($ext !== null) {
          $imageDir = $recipeImagesDir . $recipename . "." . $ext;

          unlink($imageDir);
        }

        $ext = pathinfo($url, PATHINFO_EXTENSION);

//Button set          
        $addrecipe = isset($_POST["addrecipe"]) ? $_POST["addrecipe"] : 0;

        $admittedFormats = ["jpg", "jpeg", "png", "gif", "webp"];

//Image directory       
        $imageDir = $recipeImagesDir ."/". $recipename . "." . $ext;

//Message
        $uploadOk = new ImageVerifFromWeb ($addrecipe, null, $imageDir, 300000, null, $admittedFormats, $ext, $url);
        $uploadOk = $uploadOk -> file_extention();  

// Save image 
        if($uploadOk != "") {
          $_SESSION['message'] = $uploadOk;
          $_SESSION['message_alert'] = "danger";

          header('Location: ' . root . 'add-recipe');
          exit;
        } else {
          if(file_put_contents($imageDir, file_get_contents($url)) !== false){
//Notification message        
            $log_message = "Has creado la receta \"" . $recipename . "\".";       
            $type = "add";

            if($_SESSION['notification'] == 1) {
              $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }

            $_SESSION['message'] = '¡Receta agregada exitosamente!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'add-recipe');
            exit;
          } else {
            $_SESSION['message'] = '¡Error al cargar imagen!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'add-recipe');
            exit;
          }             
        }
      }          
    } else {
      $recipeImagesDir = "imgs/recipes/". $_SESSION['username']. "/" ;

        if (!file_exists($recipeImagesDir)) {
          mkdir($recipeImagesDir, 0777, true);
        }

//Delete an old image if it exists
      $files = new Directories($recipeImagesDir, $recipename);
      $ext = $files -> directoryFiles();

      if($ext !== null) {
        $imageDir = $recipeImagesDir . $recipename . "." . $ext;

        unlink($imageDir);
      }
        
      $ext = strtolower(pathinfo($recipeImage["name"], PATHINFO_EXTENSION));
      $imageDir = $recipeImagesDir .  $recipename . "." . $ext;

//Button set          
      $addrecipe = isset($_POST["addrecipe"]) ? $_POST["addrecipe"] : 0;

      $admittedFormats = ["jpg", "jpeg", "png", "gif", "webp"];      
      
//Image verification        
      $uploadOk = new ImageVerif($addrecipe, $recipeImage["tmp_name"], $imageDir, 300000, $recipeImage["size"], $admittedFormats, $ext);
      $uploadOk = $uploadOk -> file_extention();   

      if ($uploadOk == "") {

          if(move_uploaded_file($recipeImage["tmp_name"], $imageDir)){
//Notification message        
          $log_message = "Has creado la receta \"" . $recipename . "\".";       
          $type = "add";

          if($_SESSION['notification'] == 1) {
            $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
          }

          $_SESSION['message'] = '¡Receta agregada exitosamente!';
          $_SESSION['message_alert'] = "success";

          header('Location: ' . root . 'add-recipe');
          exit;
          } else {
          $_SESSION['message'] = '¡Error al agregar receta!';
          $_SESSION['message_alert'] = "danger";
              
          header('Location: ' . root . 'add-recipe');
          exit;
        }
      } else {
          $_SESSION['message'] = $uploadOk;
          $_SESSION['message_alert'] = "danger";

          header('Location: ' . root . 'add-recipe');
          exit;
      }
    }
  } else {
          $_SESSION['message'] = '¡Esta receta ya existe!';
          $_SESSION['message_alert'] = "danger";

          header('Location: ' . root . 'add-recipe');
          exit;
  }
}

/************************************************************************************************/
/***************************************INGREDIENTS REPOSITORY CODE******************************/
/************************************************************************************************/

//receive the data
if(isset($_POST['customingredient']) && isset($_POST['uri'])){ 
  $ingredient = $_POST['customingredient'];
  $uri = $_POST['uri'];

  $result = $conn -> query ("SELECT id FROM ingredients WHERE ingredient = '$ingredient' AND username = '" . $_SESSION['username'] . "' AND state = 1;");
  $row = $result -> fetch_assoc();
  $ingredientId = $row['id'];
  
  $result = $conn -> query("SELECT ingredientid FROM ingholder WHERE ingredientid = $ingredientId AND username = '" . $_SESSION['username'] . "';");
//Check if the recipe has been added
  if($result -> num_rows > 0){
      $_SESSION['message'] = '¡Ya ha sido agregado!';
      $_SESSION['message_alert'] = "success";

      header('Location: ' . root . $uri);
      exit;
  } else {
    $stmt = $conn -> prepare("INSERT INTO ingholder (ingredientid, username) VALUES (?, ?);");
    $stmt->bind_param ("is", $ingredientId, $_SESSION['username']);

    if($stmt -> execute()) {
        $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
        $_SESSION['message_alert'] = "success";

        $stmt -> close();            

        header('Location: ' . root . $uri);
        exit;
    } else {
      $_SESSION['message'] = '¡Error al agregar ingrediente!';
      $_SESSION['message_alert'] = "danger";
          
      header('Location: ' . root . $uri);
      exit;
    }
  }
}

/************************************************************************************************/
/******************************************USER ADITION CODE*************************************/
/************************************************************************************************/

//receive the data
if (isset($_POST['firstname']) || isset($_POST['lastname']) || isset($_POST['sex']) || isset($_POST['username']) || isset($_POST['userpassword']) || isset($_POST['userrol']) || isset($_POST['useremail']) || isset($_POST['session_user'])) {

  $filter = new Filter ($_POST['firstname'], FILTER_SANITIZE_STRING);
  $firstname = $filter -> sanitization();

  $filter = new Filter ($_POST['lastname'], FILTER_SANITIZE_STRING);
  $lastname = $filter -> sanitization();

  $filter = new Filter ($_POST['username'], FILTER_SANITIZE_STRING);
  $username = $filter -> sanitization();
  
  $filter = new Filter ($_POST['useremail'], FILTER_SANITIZE_EMAIL);
  $email = $filter -> sanitization();

  $filter = new Filter ($_POST['userpassword'], FILTER_SANITIZE_STRING);
  $password = $filter -> sanitization();

  $filter = new Filter ($_POST['passrepeat'], FILTER_SANITIZE_STRING);
  $passrepeat = $filter -> sanitization();
  
  $sex = $_POST['sex']; 
//Terms of the webpage
  $terms = "yes";
  $rol = $_POST['userrol'];
  $state = $_POST['activeuser'];
  $sessionUser = $_POST['session_user'];
  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:\t\h]+|(^$)/";

//Check if the user is Admin
  $stmt = $conn -> prepare("SELECT userid, `type` FROM users WHERE username = ?;"); 
  $stmt->bind_param("s", $sessionUser);
  $stmt->execute();

  $result = $stmt -> get_result(); 
  $row = $result -> fetch_assoc();   

  $sessionUserType = $row['type'];
//If not, a error is launched
  if($sessionUserType !== 'Admin') {
        header('Location: ' . root . 'error404');
        exit;
  } 

//Input validation object  
 $inputs = ["El nombre" => [$firstname, [2,30], "incorrecto", true], 
 "El apellido" => [$lastname, [2,40], "incorrecto", true],
 "El usuario" => [$username, [2,30], "incorrecto", true],   
 "La contraseña" => [$password, [8,50], "incorrecta", false], 
 "La contraseña" => [$passrepeat, [8,50], "incorrecta", false],
 "El correo electrónico" => [$email, [15,70], "incorrecto", false]]; 

 $message = new InputValidation ($inputs, "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]/");  
 $message = $message -> lengthValidation();

   if(count($message) > 0) {
     $_SESSION['message'] = $message [0];
     $_SESSION['message_alert'] = $message [1];          

     header('Location: ' . root . 'user');
     exit;
   } 

  $result = $conn -> query("SELECT userid FROM users WHERE username = '$username' AND email = '$email';");

  if($result -> num_rows == 0) {   
    if($password != $passrepeat) {  
      $_SESSION['message'] = '¡Contraseñas no coinciden!';
      $_SESSION['message_alert'] = "danger";  
      
      header('Location: ' . root . 'user');
      exit;
//Hash password            
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      if($state == "yes") {
        $state = 1;
      } else { 
        $state = 0;
      }
//Check if it already exists
      $result = $conn -> query("SELECT email_code FROM users WHERE firstname = '$firstname' AND lastname = '$lastname' AND username = '$username' AND `password` = '$hashed_password';");

      if($result -> num_rows == 0) { 
        
      $uniqcode = md5(uniqid(mt_rand()));

      $stmt = $conn -> prepare("INSERT INTO users (firstname, lastname, username, `password`, `type`, email, `state`, sex, email_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");
      $stmt->bind_param ("ssssssiss", $firstname, $lastname, $username, $hashed_password, $rol, $email, $state, $sex, $uniqcode);
//Confirmation link                            
      $confirmPassLink = "www.recipeholder.net". root ."email_confirm?code=". $uniqcode;
//Message
      $subject = "Confirmación de correo";                            
      $message = "<p>Has sido suscrito en la página de recetas: recipeholder.net. Si no te interesa usar este servicio, ignora este mensaje, de lo contrario haz click en el enlace de confirmación.</p>";
      $message .= "<a href='" . $confirmPassLink . "'>" . $confirmPassLink . "</a>";                           
//set content-type header for sending HTML email
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
//additionals
      $headers .= "From: " .  $_SERVER['HTTP_REFERER'] . "\r\n" .
      "CC: magdielmagdiel01@gmail.com";
//Send email

        if ($stmt->execute() && mail($email, $subject, $message, $headers)) {
//Notification message        
          $log_message = "Has creado el usuario \"" . $username . "\".";       
          $type = "add";

          if($_SESSION['notification'] == 1) {
            $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
          }

          $_SESSION['message'] = '¡Usuario agregado con éxito!';
          $_SESSION['message_alert'] = "success";

          $stmt->close();                  
          header('Location: ' . root . 'user');
          exit;
        } else {
          $_SESSION['message'] = '¡Error al agregar usuario!';
          $_SESSION['message_alert'] = "danger";
              
          header('Location: ' . root . 'user');
          exit;
        }
      } else {
        $_SESSION['message'] = '¡Este usuario ya existe!';
        $_SESSION['message_alert'] = "success";

        header('Location: ' . root . 'user');
        exit;
      }
    }
  } else {
    $row = $result -> fetch_assoc();
    $email_code = $row ["email_code"];
//The user had deleted his account, reactivate
    if($email_code != null) {
      $_SESSION['message'] = '¡Este usuario ya existe, reactiva tu cuenta!';
      $_SESSION['message_alert'] = "danger";

      header('Location: ' . root . 'reactive-account');
      exit;
//The user is already registered and his account is active              
    } else {
      $_SESSION['message'] = '¡Este usuario ya existe!';
      $_SESSION['message_alert'] = "danger";

      header('Location: ' . root . 'user');
      exit;
    } 
  }           
}

/************************************************************************************************/
/**********************************NOTIFICATION RECIPE ADITION CODE******************************/
/************************************************************************************************/

//receive the data
if (isset($_GET['messageid']) && isset($_GET['type'])) {
  $messageid = $_GET['messageid'];
  $type = $_GET['type'];

//Only if it is a shared recipe
  if($type != "share_receiver"){
    header('Location: ' . root . 'error404');
    exit;
  } else {
//Getting the date    
    $result = $conn -> query("SELECT date FROM `log` WHERE id = '$messageid';");
    $row = $result -> fetch_assoc();
    
    $date = $row["date"];
//Getting the id 
    $result = $conn -> query("SELECT recipeid FROM shares WHERE date = '$date' AND share_to = '" . $_SESSION["username"] . "';");
    $row = $result -> fetch_assoc();

    $recipeid = $row["recipeid"];
//Getting the recipe name
    $result = $conn -> query("SELECT * FROM recipe WHERE recipeid = '$recipeid' AND state = 1;");
    $row = $result -> fetch_assoc();

    $recipename = $row["recipename"];
//Verifying if the recipe already exists
    $result = $conn -> query("SELECT recipeid FROM recipe WHERE recipename = '$recipename' AND username = '" . $_SESSION["username"] . "' AND state = 1;");

    if($result -> num_rows == 0){
      $categoryid = $row["categoryid"];
      $ingredients = $row["ingredients"];
      $preparation = $row["preparation"];
      $cookingtime = $row["cookingtime"];

      $result = $conn -> query("INSERT INTO recipe (recipename, categoryid, username, ingredients, preparation, cookingtime) VALUES ('$recipename', '$categoryid', '" . $_SESSION["username"]. "', '$ingredients', '$preparation', '$cookingtime');");

//Notification message        
      $log_message = "Has aceptado la receta \"" . $recipename . "\".";       
      $type = "add";

      if($result) {
        $sql = "DELETE FROM shares WHERE recipeid = '$recipeid';";
        $sql .= "DELETE FROM `log` WHERE id = '$messageid';";
        
        if($_SESSION['notification'] == 1) {
           $sql .= "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', '0');";
        }      

//The page is redirected to the notifications
        if($conn -> multi_query($sql)) {   
          $_SESSION['message'] = '¡Receta agragada exitosamente!';
          $_SESSION['message_alert'] = "success";

          header('Location: ' . root . 'notifications');
          exit;
        }
      } else {
        $_SESSION['message'] = '¡Error al agregar receta!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'notifications');
        exit;
      }     
    } else {
        $_SESSION['message'] = '¡Esta receta ya ha sido agregada!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'notifications');
        exit;      
    }
  }
}

/******************************************************************************************************************** */
/*************************************************DIET ADDING CODE*************************************************** */
/******************************************************************************************************************** */

if (isset($_POST['data']) && isset($_POST['diet']) && isset($_POST['days'])) {

$filter = new Filter ($_POST['diet'], FILTER_SANITIZE_STRING);
$dietName = $filter -> sanitization();

$data = $_POST['data'];
$days = $_POST['days'];

//Input validation object  
$inputs = ["El nombre de dieta" => [$dietName, [2,30], "incorrecto", true], 
"Los datos" => [$data, [], "incorrectos", false],
"Los días" => [$days, [], "incorrectos", false]]; 

$message = new InputValidation ($inputs, "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]/");  
$message = $message -> lengthValidation();

  if(count($message) > 0) {
    $_SESSION['message'] = $message [0];
    $_SESSION['message_alert'] = $message [1];          

    header('Location: ' . root . 'diet');
    exit;
  } 

//Inserting the recipe name
  $result = $conn -> query("INSERT INTO diet (dietname, username) VALUES ('$dietName', '". $_SESSION["username"]."');");

  if($result) {
//Getting the last id    
    $last_id = $conn->insert_id;
//Declaring the multi query
    $sql = "";
//Inserting the recipes details
    for($i = 0; $i < count($data); $i++) {
      $sql .= "INSERT INTO diet_details (day, recipes, dietid) VALUES ('" . $days[$i]. "', '". $data[$i] ."', '$last_id');";
    }

    if($conn -> multi_query($sql)) {
      $_SESSION['message'] = '¡Dieta agregada correctamente!';
      $_SESSION['message_alert'] = "success";

      header('Location: ' . root . 'diet');
      exit;
    } else {
      $_SESSION['message'] = '¡Error al agregar dieta!';
      $_SESSION['message_alert'] = "danger";

      header('Location: ' . root . 'diet');
      exit;
    }
  } else {
      $_SESSION['message'] = '¡Error al agregar dieta!';
      $_SESSION['message_alert'] = "danger";

      header('Location: ' . root . 'diet');
      exit;
  }
}

//Exiting db connection.
$conn -> close(); 

//Verify that data comes
if(empty($_POST) || empty($_GET)) {
    header('Location: ' . root);
    exit;  
}
?>