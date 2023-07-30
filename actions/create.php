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
  $filter = new Filter ($_POST['add_categories'], FILTER_SANITIZE_STRING, $conn);
  $category = $filter -> sanitization();

  $categoryImage = $_FILES["categoryImage"];

//Regex that the category should have
  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:\t\h]+|(^$)/";   

  if ($category == "" || $categoryImage ['name'] == null){

      $_SESSION['message'] = '¡Escriba la categoría o cargue la imagen!';
      $_SESSION['message_alert'] = "danger";          

      header('Location: ' . root . 'categories');
      exit;
  } else {
    if (!preg_match($pattern, $category)){
        $_SESSION['message'] = '¡Categoría incorrecta!';
        $_SESSION['message_alert'] = "danger";
            
        header('Location: ' . root . 'categories');
        exit;
    } else {
      if(strlen($category) > 20 || strlen($category) < 2) {
        $_SESSION['message'] = '¡Longitud de categoría incorrecta!';
        $_SESSION['message_alert'] = "danger";
            
        header('Location: ' . root . 'categories');
        exit;
      } else{
  //lowercase the variable
        $category = strtolower($category);
  //Check if the category had been added
        $sql = "SELECT category FROM categories WHERE category = '$category' AND state = 1;";
        $num_rows = $conn -> query($sql) -> num_rows;      

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
        if($ext != "jpg" && $ext != "png" && $ext != "webp" && $ext != "jpeg"
        && $ext != "gif" ) {
          $uploadOk = "¡Formato no admitido!";
        } 

        if ($uploadOk == "") {
          if(move_uploaded_file($categoryImage["tmp_name"], $target_file) && $stmt -> execute()){
//Notification message        
            $log_message = "Has creado la categoría \"" . $category . "\".";       
            $type = "add";
            
            if($_SESSION['notification'] == 1) {
              $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
              $conn -> query($sql);
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
  
  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:\t\h]+|(^$)/"; 
 
//Variable is null.
  if ($ingredient == ""){
      $_SESSION['message'] = '¡Escriba el ingrediente por favor!';
      $_SESSION['message_alert'] = "danger";

      header('Location: ' . root . 'ingredients');
      exit;
  } else {
  if(!preg_match($pattern, $ingredient)){
      $_SESSION['message'] = '¡Ingrediente incorrecto!';
      $_SESSION['message_alert'] = "danger";
          
      header('Location: ' . root . 'ingredients');
      exit;
  }
//lowercase the variable
    $ingredient = strtolower($ingredient);

    $sql = "SELECT ingredient FROM ingredients WHERE ingredient = '$ingredient' AND username = '" .  $_SESSION['username'] . "' AND state = 1;";

    $num_rows = $conn -> query($sql) -> num_rows;

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
          $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
          $conn -> query($sql);
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
}

/************************************************************************************************/
/***************************************RECIPE ADITION CODE*************************************/
/************************************************************************************************/

//receive the data
if(isset($_POST["recipename"]) && isset($_POST["imageUrl"]) && isset($_FILES["recipeImage"]) && isset($_POST['category']) && isset($_POST['cookingtime']) && isset($_POST['ingredients']) && isset($_POST['preparation'])){

//Data sanitization
  $filter = new Filter ($_POST['recipename'], FILTER_SANITIZE_STRING, $conn);
  $recipename = $filter -> sanitization();

  $filter = new Filter ($_POST['preparation'], FILTER_SANITIZE_STRING, $conn);
  $preparation = $filter -> sanitization();

  $filter = new Filter ($_POST['cookingtime'], FILTER_SANITIZE_NUMBER_INT, $conn);
  $cookingtime = $filter -> sanitization();

  $filter = new Filter ($_POST['ingredients'], FILTER_SANITIZE_STRING, $conn);
  $ingredients = $filter -> sanitization();

  $category = $_POST['category']; 
  $recipeImage = $_FILES["recipeImage"];  

  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:\t\h]+|(^$)/"; 
//If this variables are null
  if ($recipename == "" || $preparation == "" || $ingredients == "") {
      $_SESSION['message'] = '¡Falta nombre de la receta o la preparación!';
      $_SESSION['message_alert'] = "danger";

      header('Location: ' . root . 'add-recipe');
      exit;
  } else {
  if (!preg_match($pattern, $recipename)){
      $_SESSION['message'] = '¡Nombre de receta incorrecto!';
      $_SESSION['message_alert'] = "danger";
          
      header('Location: ' . root . 'add-recipe');
      exit;
  } 
//If cookingtime is not between 5 and 180  
  if ($cookingtime > 180 || $cookingtime < 5) {
      $_SESSION['message'] = '¡Tiempo de cocción debe estar entre 5 - 180 minutos!';
      $_SESSION['message_alert'] = "danger";
          
      header('Location: ' . root . 'add-recipe');
      exit;
  } 
      $_SESSION['category'] = $category;

      $sql = "SELECT recipename FROM recipe WHERE recipename = '$recipename' AND username = '" .  $_SESSION['username'] . "' AND state = 1;";
      $result = $conn -> query($sql);
      $num_rows = $result -> num_rows;
//Check if the recipe exists            
      if($num_rows == 0){
        
        if($cookingtime == "") { 
          $cookingtime = 0;
        }
//Get the category id        
      $sql = "SELECT categoryid FROM categories WHERE category = ? AND state = 1;";   
      $stmt = $conn -> prepare($sql); 
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
        $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
        $conn -> query($sql);
      }

//If no image has been added
        if ($recipeImage ['name'] == null && $_POST["imageUrl"] == "") {           
        $_SESSION['message'] = '¡Receta agregada exitosamente!';
        $_SESSION['message_alert'] = "success";

        header('Location: ' . root . 'add-recipe');
        exit;
        } else if ($_POST["imageUrl"] != "") {
// Remote image URL Sanitization   
          $filter = new Filter ($_POST["imageUrl"], FILTER_SANITIZE_URL, $conn);
          $url = $filter -> sanitization();
//Url existance verification          
          $URLVerif = new UrlVerification ($url);
          $URLVerif = $URLVerif -> urlVerif();
          
          if($URLVerif === false) {
//Notification message        
            $log_message = "Has creado la receta \"" . $recipename . "\".";       
            $type = "add";
            if($_SESSION['notification'] == 1) {
              $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
              $conn -> query($sql);
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
          $uploadOk = "";
//Format verification
          if($ext != "jpg" && $ext != "jpeg" && $ext != "png" && $ext != "gif" && $ext != "webp") {
            $uploadOk = '¡Formato de imagen no admitido!';
          }   
//Size verification            
          if(array_change_key_case(get_headers($url,1))['content-length'] > 300000){
            $uploadOk = '¡El tamaño debe ser menor que 300 KB!';
          }

//Name of the saved image         
          $imageDir = $recipeImagesDir ."/". $recipename . "." . $ext;

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
                $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
                $conn -> query($sql);
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
        $uploadOk = "";
        
// Check if image file is a actual image or fake image
        if(isset($_POST["addrecipe"])) {
          $check = getimagesize($recipeImage["tmp_name"]);
          if($check == false) {
              $uploadOk = "¡Este archivo no es una imagen!";
          } 
        }

// Check file size
        if ($recipeImage["size"] > 300000) {
            $uploadOk = "¡El tamaño debe ser menor que 300 KB!";
        }

// Allow certain file formats
        if($ext != "jpg" && $ext != "jpeg" && $ext != "png" && $ext != "webp" && $ext != "gif") {
            $uploadOk = "¡Formato no admitido!";
        }      

        if ($uploadOk == "") {

            if(move_uploaded_file($recipeImage["tmp_name"], $imageDir)){
//Notification message        
            $log_message = "Has creado la receta \"" . $recipename . "\".";       
            $type = "add";

            if($_SESSION['notification'] == 1) {
              $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
              $conn -> query($sql);
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
}

/************************************************************************************************/
/***************************************INGREDIENTS REPOSITORY CODE******************************/
/************************************************************************************************/

//receive the data
if(isset($_POST['customingredient']) && isset($_POST['uri'])){ 
  $ingredient = $_POST['customingredient'];
  $uri = $_POST['uri'];

  $sql = "SELECT id FROM ingredients WHERE ingredient = '$ingredient' AND username = '" . $_SESSION['username'] . "' AND state = 1;";
  $row = $conn -> query($sql) -> fetch_assoc();
  $ingredientId = $row['id'];
  
  $sql = "SELECT ingredientid FROM ingholder WHERE ingredientid = $ingredientId AND username = '" . $_SESSION['username'] . "';";
  $result = $conn -> query($sql);
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
//Terms of the webpage
  $terms = "yes";
  $rol = $_POST['userrol'];
  $state = $_POST['activeuser'];
  $sessionUser = $_POST['session_user'];
  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:\t\h]+|(^$)/";

//Check if the user is Admin
  $sql = "SELECT userid, `type` FROM users WHERE username = ?;";
  $stmt = $conn -> prepare($sql); 
  $stmt->bind_param("s", $sessionUser);
  $stmt->execute();

  $result = $stmt -> get_result(); 
  $row = $result -> fetch_assoc();   

  $sessionUserType = $row['type'];
//If not, a error is launched
  if($sessionUserType !== 'Admin') {
        header('Location: ' . root . 'error404');
        exit;
//If null        
  } else {
    if ($firstname == "" || $lastname == "" || $username == "" || $password == "" || $sex == "" || $email == "") {
        $_SESSION['message'] = '¡Complete todos los campos por favor!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'user');
        exit;
//If passwords don't match        
    } else {
      if (!preg_match($pattern, $firstname) || !preg_match($pattern, $lastname) || !preg_match($pattern, $username)){
        $_SESSION['message'] = '¡Nombre, apellido o usuario incorrecto!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'user');
        exit;
      } else {
        if(strlen($firstname) < 2 || strlen($firstname) > 30 || strlen($lastname) < 2 || strlen($lastname) > 40 || strlen($username) < 2 || strlen($username) > 30 ||  strlen($password) < 8 ||  strlen($password) > 50 || strlen($passrepeat) < 8 ||  strlen($passrepeat) > 50 || strlen($email) < 15 || strlen($email) > 70) {
            $_SESSION['message'] = '¡Cantidad de caracteres no aceptada!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root . 'user');
            exit;
        } else {
          $sql = "SELECT userid FROM users WHERE username = '$username' AND email = '$email';";
          $result = $conn -> query($sql);

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
              $sql = "SELECT email_code FROM users WHERE firstname = '$firstname' AND lastname = '$lastname' AND username = '$username' AND `password` = '$hashed_password';";
              $result = $conn -> query($sql);

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
                    $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
                    $conn -> query($sql);
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
      }
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
  if($type != "share" ){
    header('Location: ' . root . 'error404');
    exit;
  } else {
//Getting the date    
    $sql = "SELECT date FROM `log` WHERE id = '$messageid';";
    $result = $conn -> query($sql);
    $row = $result -> fetch_assoc();
    
    $date = $row["date"];
//Getting the id 
    $sql = "SELECT recipeid FROM shares WHERE date = '$date' AND share_to = '" . $_SESSION["username"] . "';";
    $result = $conn -> query($sql);
    $row = $result -> fetch_assoc();

    $recipeid = $row["recipeid"];
//Getting the recipe name
    $sql = "SELECT * FROM recipe WHERE recipeid = '$recipeid' AND state = 1;";
    $result = $conn -> query($sql);
    $row = $result -> fetch_assoc();

    $recipename = $row["recipename"];
//Verifying if the recipe already exists
    $sql = "SELECT recipeid FROM recipe WHERE recipename = '$recipename' AND username = '" . $_SESSION["username"] . "' AND state = 1;";
    $result = $conn -> query($sql);

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
//Exiting db connection.
$conn -> close(); 

//Verify that data comes
if(empty($_POST) || empty($_GET)) {
    header('Location: ' . root);
    exit;  
}
?>