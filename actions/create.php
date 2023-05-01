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
  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ\t\h]+|(^$)/";   

  if ($category == "" || $categoryImage ['name'] == null){

      $_SESSION['message'] = '¡Escriba la categoría o cargue la imagen!';
      $_SESSION['message_alert'] = "danger";          

      header('Location: /categories');
      exit;
  } else {
    if (!preg_match($pattern, $category)){
        $_SESSION['message'] = '¡Categoría incorrecta!';
        $_SESSION['message_alert'] = "danger";
            
        header('Location: /categories');
        exit;
    } else {

//lowercase the variable
      $category = strtolower($category);
//Check if the category had been added
      $sql = "SELECT category FROM categories WHERE category = '$category';";
      $num_rows = $conn -> query($sql) -> num_rows;      

      if($num_rows != 0){
          $_SESSION['message'] = '¡Ya ha sido agregado!';
          $_SESSION['message_alert'] = "success";

          header('Location: /categories');
          exit;
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
          $_SESSION['message'] = '¡Categoría agregada con éxito!';
          $_SESSION['message_alert'] = "success";

          $stmt -> close();

          header('Location: /categories');  
          exit;  
        } else {
        $_SESSION['message'] = '¡Error al agregar categoría!';
        $_SESSION['message_alert'] = "danger";

        header('Location: /categories'); 
        exit;
        }
      } else {
        $_SESSION['message'] = $uploadOk;
        $_SESSION['message_alert'] = "danger";

        header('Location: /categories'); 
        exit;
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
 
//Variable is null.
  if ($ingredient == ""){
      $_SESSION['message'] = '¡Escriba el ingrediente por favor!';
      $_SESSION['message_alert'] = "danger";

      header('Location: /ingredients');
  } else {
  if(!preg_match($pattern, $ingredient)){
      $_SESSION['message'] = '¡Ingrediente incorrecto!';
      $_SESSION['message_alert'] = "danger";
          
      header('Location: /ingredients');
  }
//lowercase the variable
    $ingredient = strtolower($ingredient);

    $sql = "SELECT ingredient FROM ingredients WHERE ingredient = '$ingredient' AND username = '" .  $_SESSION['username'] . "';";

    $num_rows = $conn -> query($sql) -> num_rows;

//Check if it already exists
    if($num_rows != 0){
        $_SESSION['message'] = '¡Ya ha sido agregado!';
        $_SESSION['message_alert'] = "success";

        header('Location: /ingredients');
    } else {
    $stmt = $conn -> prepare("INSERT INTO ingredients (ingredient, username) VALUES (?, ?);");
    $stmt->bind_param("ss", $ingredient, $_SESSION['username']);

    if ($stmt -> execute()) {
        $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
        $_SESSION['message_alert'] = "success";

        $stmt -> close();
        header('Location: /ingredients');

      } else {
        $_SESSION['message'] = '¡Error al agregar ingrediente!';
        $_SESSION['message_alert'] = "danger";
            
        header('Location: /ingredients');
      }
    }
  }
}

/************************************************************************************************/
/***************************************RECIPE ADITION CODE*************************************/
/************************************************************************************************/

//receive the data
if(isset($_POST['recipename']) && isset($_FILES["recipeImage"]) && isset($_POST['category']) && isset($_POST['cookingtime']) && isset($_POST['ingredients']) && isset($_POST['preparation'])){

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

  $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ\t\h]+|(^$)/"; 
//If this variables are null
  if ($recipename == "" || $preparation == "" || $ingredients == "") {
      $_SESSION['message'] = '¡Falta nombre de la receta o la preparación!';
      $_SESSION['message_alert'] = "danger";

      header('Location: /add-recipe');
  } else {
  if (!preg_match($pattern, $recipename)){
      $_SESSION['message'] = '¡Nombre de receta incorrecto!';
      $_SESSION['message_alert'] = "danger";
          
      header('Location: /add-recipe');
  } 
//If cookingtime is not between 5 and 180  
  if ($cookingtime > 180 || $cookingtime < 5) {
      $_SESSION['message'] = '¡Tiempo de cocción debe estar entre 5 - 180 minutos!';
      $_SESSION['message_alert'] = "danger";
          
      header('Location: /add-recipe');
  } 
      $_SESSION['category'] = $category;

      $sql = "SELECT recipename FROM recipe WHERE recipename = '$recipename' AND username = '" .  $_SESSION['username'] . "';";
      $result = $conn -> query($sql);
//Check if the recipe exists            
      if($result -> num_rows == 0){
        
        if($cookingtime == "") { 
          $cookingtime = 0;
        }
//Get the category id        
      $sql = "SELECT categoryid FROM categories WHERE category = '$category';";      
      $row= $conn -> query($sql) -> fetch_assoc();
      
      $categoryid = $row["categoryid"];

      $stmt = $conn -> prepare("INSERT INTO recipe (recipeid, ingredients, preparation, cookingtime, recipename, categoryid, username) VALUES (?, ?, ?, ?, ?, ?, ?);");
      $stmt->bind_param ("issisis", $recipeId, $ingredients, $preparation, $cookingtime, $recipename, $categoryid, $_SESSION['username']);
      
      $stmt -> execute();
      $stmt -> close(); 
//If no image has been added
        if($recipeImage ['name'] == null) {           
        $_SESSION['message'] = '¡Receta agregada exitosamente!';
        $_SESSION['message_alert'] = "success";

        header('Location: /add-recipe');
//If an image has been added     
        } else {
        $recipeImagesDir = "imgs/recipes/". $_SESSION['username'];

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

            if(move_uploaded_file($recipeImage["tmp_name"], $target_file)){
            $_SESSION['message'] = '¡Receta agregada exitosamente!';
            $_SESSION['message_alert'] = "success";

            header('Location: /add-recipe');
            } else {
            $_SESSION['message'] = '¡Error al agregar receta!';
            $_SESSION['message_alert'] = "danger";
                
            header('Location: /add-recipe');
          }
        } else {
            $_SESSION['message'] = $uploadOk;
            $_SESSION['message_alert'] = "danger";

            header('Location: /add-recipe');
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

  //Check if the recipe has been added
  if($result -> num_rows > 0){
      $_SESSION['message'] = '¡Ya ha sido agregado!';
      $_SESSION['message_alert'] = "success";

      header('Location: /custom');
  } else {
    $stmt = $conn -> prepare("INSERT INTO ingholder (ingredientid, username) VALUES (?, ?);");
    $stmt->bind_param ("is", $ingredientId, $_SESSION['username']);

    if ($stmt -> execute()) {
        $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
        $_SESSION['message_alert'] = "success";

        $stmt -> close();            

        header('Location: /custom');

    } else {
      $_SESSION['message'] = '¡Error al agregar ingrediente!';
      $_SESSION['message_alert'] = "danger";
          
      header('Location: /custom');
    }
  }
}


/************************************************************************************************/
/******************************************USER ADITION CODE*************************************/
/************************************************************************************************/

//receive the data
if(isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['sex']) && isset($_POST['username']) && isset($_POST['userpassword']) && isset($_POST['userrol']) && isset($_POST['useremail']) && isset($_POST['session_user'])){

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

//Check if the user is Admin
  $sql = "SELECT userid, `type` FROM users WHERE username ='$sessionUser';";
  $row = $conn -> query($sql) -> fetch_assoc();
  $sessionUserType = $row['type'];
//If not, a error is launched
  if($sessionUserType !== 'Admin') {
        header('Location: /error404');
        exit;
//If null        
  } else {
    if ($firstname == "" || $lastname == "" || $username == "" || $password == ""  || $sex == "") {
        $_SESSION['message'] = '¡Complete todos los campos por favor!';
        $_SESSION['message_alert'] = "danger";

        header('Location: /user');
//If passwords don't match        
    } else {   
      if($password !== $passrepeat) {
        $_SESSION['message'] = '¡Contraseñas no coinciden!';
        $_SESSION['message_alert'] = "danger";  
        
        header('Location: /user');
//Hash password            
      } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if($state == "yes") {
          $state = 1;
        } else { 
          $state = 0;
        }
//Check if it already exists
        $sql = "SELECT userid FROM users WHERE firstname = '$firstname' AND lastname = '$lastname' AND username = '$username' AND `password` = '$hashed_password';";
        $num_rows = $conn -> query($sql) -> num_rows;

        if($num_rows == 0) {          
        $stmt = $conn -> prepare("INSERT INTO users (firstname, lastname, username, `password`, `type`, email, `state`, sex) VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param ("ssssssis", $firstname, $lastname, $username, $hashed_password, $rol, $userEmail, $state, $sex);

          if ($stmt->execute()) {
              $_SESSION['message'] = '¡Usuario agregado con éxito!';
              $_SESSION['message_alert'] = "success";

              $stmt->close();                  
              header('Location: /user');
            } else {
              $_SESSION['message'] = '¡Error al agregar usuario!';
              $_SESSION['message_alert'] = "danger";
                  
              header('Location: /user');;
          }
        } else {
              $_SESSION['message'] = '¡Este usuario ya existe!';
              $_SESSION['message_alert'] = "success";

              header('Location: /user');
        }
      }
    }
  }
}
?>
<?php
//Exiting db connection.
$conn -> close(); 
?>