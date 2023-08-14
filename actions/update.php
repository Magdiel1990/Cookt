<?php
//Head of the page.
require_once ("views/partials/head.php");

$_SESSION['location'] = root;

/************************************************************************************************/
/**************************************INGREDIENT UPDATE CODE************************************/
/************************************************************************************************/

if(isset($_POST["ingredientName"]) && isset($_GET["ingredientId"])){
  $filter = new Filter ($_POST["ingredientName"], FILTER_SANITIZE_STRING, $conn);
  $ingredient = $filter -> sanitization();
  
  $id = $_GET["ingredientId"];

//Input validation object  
    $inputs = ["El ingrediente" => [$ingredient, [2,50], "incorrecto", true]];

    $message = new InputValidation ($inputs, "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]/");  
    $message = $message -> lengthValidation();

    if(count($message) > 0) {
        $_SESSION['message'] = $message [0];
        $_SESSION['message_alert'] = $message [1];          

        header('Location: ' . root . 'edit?ingredientname=' . $ingredient);
        exit;
    } 

//lowercase the variable
    $ingredient = strtolower($ingredient);

    $result = $conn -> query ("UPDATE ingredients SET ingredient = '$ingredient' WHERE id = '$id';");

    $stmt = $conn -> prepare("UPDATE ingredients SET ingredient = ? WHERE id = ?;");
    $stmt->bind_param("si", $ingredient, $id);

    if ($stmt -> execute()) {
//Notification message        
        $log_message = "Has editado el ingrediente \"" . $ingredient . "\".";       
        $type = "update";

//Verify the settings
        if($_SESSION['notification'] == 1) {
          $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
        }

        $_SESSION['message'] = '¡Ingrediente editado con éxito!';
        $_SESSION['message_alert'] = "success";

        $stmt -> close();
        header('Location: ' . root . 'edit?ingredientname=' . $ingredient);
        exit;
    } else {
        $_SESSION['message'] = '¡Error al editaringrediente!';
        $_SESSION['message_alert'] = "danger";
            
        header('Location: ' . root . 'edit?ingredientname=' . $ingredient);
        exit;
    }
}

/************************************************************************************************/
/******************************************RECIPE UPDATE CODE************************************/
/************************************************************************************************/


if(isset($_GET["editname"]) && isset($_GET["username"]) && isset($_POST["imageUrl"])&& isset($_FILES["recipeImage"]) && isset($_POST["newRecipeName"]) && isset($_POST["category"])
&& isset($_POST["cookingTime"]) && isset($_POST["ingredients"]) && isset($_POST["preparation"])){

$oldName = $_GET["editname"];

$filter = new Filter ($_POST["newRecipeName"], FILTER_SANITIZE_STRING, $conn);  
$newRecipeName = $filter -> sanitization();

$filter = new Filter ($_POST["cookingTime"], FILTER_SANITIZE_NUMBER_INT, $conn);  
$cookingTime = $filter -> sanitization();

$filter = new Filter ($_POST["preparation"], FILTER_SANITIZE_STRING, $conn);  
$preparation = $filter -> sanitization();

$filter = new Filter ($_POST["ingredients"], FILTER_SANITIZE_STRING, $conn);  
$ingredients = $filter -> sanitization();

$category = $_POST["category"];
$recipeImage = $_FILES["recipeImage"];
$userName = $_GET["username"];

$stmt = $conn -> prepare("SELECT categoryid FROM categories WHERE category = ? AND state = 1;"); 
$stmt->bind_param("s", $category);
$stmt->execute();

$result = $stmt -> get_result(); 
$row = $result -> fetch_assoc();   

$categoryId = $row['categoryid'];

//Input validation object  
    $inputs = ["La receta" => [$newRecipeName, [7,50], "incorrecta", true], 
    "Los ingredientes" => [$ingredients, [], "incorrectos", false],
    "La preparación" => [$preparation, [], "incorrecta", false],   
    "El tiempo de cocción" => [$cookingTime, [5,180], "incorrecto", false]];

    $message = new InputValidation ($inputs, "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]/");  
    $message = $message -> lengthValidation();

    if(count($message) > 0) {
        $_SESSION['message'] = $message [0];
        $_SESSION['message_alert'] = $message [1];          

        header('Location: ' . root . 'edit?recipename='. $oldName . '&username=' . $userName);
        exit;
    }
//If no image is uploaded
    if($recipeImage['name'] == null && $_POST["imageUrl"] == "") {
//Data update   
        $result = $conn->query ("UPDATE recipe SET recipename = '$newRecipeName', preparation = '$preparation', ingredients = '$ingredients', cookingtime = '$cookingTime', categoryid = '$categoryId' WHERE recipename = '$oldName' AND username = '$userName' AND state = 1;");    
        if ($result) {
//Notification message        
            $log_message = "Has actualizado la receta \"" . $oldName . "\" por el nuevo nombre \"" . $newRecipeName . "\".";       
            $type = "update";
            
            if($_SESSION['notification'] == 1) {
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }

//Message if the variable is null.
            $_SESSION['message'] = '¡Receta editada con éxito!';
            $_SESSION['message_alert'] = "success";
                
//The page is redirected to the edit.php
            header('Location: ' . root . 'edit?recipename='. $newRecipeName .'&username=' . $userName);
            exit;
        } else {
//Message if the variable is null.
        $_SESSION['message'] = '¡Error al editar receta!';
        $_SESSION['message_alert'] = "danger";

//The page is redirected to the edit.php
        header('Location: ' . root . 'edit?recipename='. $oldName. '&username=' . $userName);
        exit;
        }  
    } else if ($_POST["imageUrl"] != "") {
//Data update  
        $result = $conn-> query("UPDATE recipe SET recipename = '$newRecipeName', preparation = '$preparation', ingredients = '$ingredients', cookingtime = '$cookingTime', categoryid = '$categoryId' WHERE recipename = '$oldName' AND username = '$userName' AND state = 1;");
        if ($result) {
//Notification message        
            $log_message = "Has actualizado la receta \"" . $oldName . "\" por el nuevo nombre \"" . $newRecipeName . "\".";       
            $type = "update";

            if($_SESSION['notification'] == 1) {
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }

// Remote image URL Sanitization   
            $filter = new Filter ($_POST["imageUrl"], FILTER_SANITIZE_URL, $conn);
            $url = $filter -> sanitization();
//Url existance verification          
            $URLVerif = new UrlVerification ($url);
            $URLVerif = $URLVerif -> urlVerif();
            
            if($URLVerif === false) {
                $_SESSION['message'] = '¡Receta editada exitosamente sin imagen!';
                $_SESSION['message_alert'] = "success";

//The page is redirected to the edit.php
                header('Location: ' . root . 'edit?recipename='. $newRecipeName .'&username=' . $userName);
                exit;        
            } else {                    
// Image path
                $recipeImagesDir = "imgs/recipes/". $_SESSION['username']. "/";

                if (!file_exists($recipeImagesDir)) {
                    mkdir($recipeImagesDir, 0777, true);
                }

//Deleting the old img
                $files = new Directories ($recipeImagesDir, $oldName);
                $ext = $files -> directoryFiles();

                if($ext !== null) {
                    $imageDir = $recipeImagesDir . $oldName . "." . $ext;

                    unlink($imageDir);
                }
            
                $ext = pathinfo($url, PATHINFO_EXTENSION);                        
//Button set          
                $editsubmit = isset($_POST["edit"]) ? $_POST["edit"] : 0;

                $admittedFormats = ["jpg", "jpeg", "png", "gif", "webp"];

//New name for the saved image         
                $recipeImagesDir = $recipeImagesDir . "/" . $newRecipeName . "." . $ext;

//Message
                $uploadOk = new ImageVerifFromWeb ($editsubmit, null, $recipeImagesDir, 300000, null, $admittedFormats, $ext, $url);
                $uploadOk = $uploadOk -> file_extention();  

                if($uploadOk != "") {
                    $_SESSION['message'] = $uploadOk;
                    $_SESSION['message_alert'] = "danger";

                    header('Location: ' . root . 'edit?recipename='. $oldName .'&username=' . $userName);
                    exit;  
// Save image 
                } else {
                    if(file_put_contents($recipeImagesDir, file_get_contents($url)) !== false){
                        $_SESSION['message'] = '¡Receta editada exitosamente!';
                        $_SESSION['message_alert'] = "success";

                        header('Location: ' . root . 'edit?recipename='. $newRecipeName .'&username=' . $userName);
                        exit;   
                    } else {
                        $_SESSION['message'] = '¡Error al cargar imagen!';
                        $_SESSION['message_alert'] = "success";

                        header('Location: ' . root . 'edit?recipename='. $oldName .'&username=' . $userName);
                        exit;  
                    }             
                }
            }  
        } else {
//Message if the variable is null.
            $_SESSION['message'] = '¡Error al editar receta!';
            $_SESSION['message_alert'] = "danger";

//The page is redirected to the edit.php
            header('Location: ' . root . 'edit?recipename='. $oldName. '&username=' . $userName);
            exit;
        }
    } else {
        $target_dir = "imgs/recipes/". $userName . "/";
        
        $files = new Directories($target_dir, $oldName);
        $ext = $files -> directoryFiles();

//Delete the old img
        if($ext !== null) {
            $imageDir = $target_dir . $oldName . "." . $ext;

            unlink ($imageDir);
        }

        $ext = strtolower(pathinfo($recipeImage["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . $newRecipeName . "." . $ext;

//Button set          
        $editsubmit = isset($_POST["edit"]) ? $_POST["edit"] : 0;

        $admittedFormats = ["jpg", "jpeg", "png", "gif", "webp"];      
        
//Image verification        
        $uploadOk = new ImageVerif($editsubmit, $recipeImage["tmp_name"], $target_file, 300000, $recipeImage["size"], $admittedFormats, $ext);
        $uploadOk = $uploadOk -> file_extention();   

        $result = $conn-> query ("UPDATE recipe SET recipename = '$newRecipeName', preparation = '$preparation', ingredients = '$ingredients', cookingtime = '$cookingTime', categoryid = '$categoryId' WHERE recipename = '$oldName' AND username = '$userName' AND state = 1;");

        if ($uploadOk == "") {
            if(move_uploaded_file($recipeImage["tmp_name"], $target_file) && $result){
//Notification message        
            $log_message = "Has actualizado la receta \"" . $oldName . "\" por el nuevo nombre \"" . $newRecipeName . "\".";       
            $type = "update";

            if($_SESSION['notification'] == 1) {
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }
//Success message.
            $_SESSION['message'] = '¡Receta editada con éxito!';
            $_SESSION['message_alert'] = "success";

//The page is redirected to the edit.php
            header('Location: ' . root . 'edit?recipename='. $newRecipeName. '&username=' . $userName);
            exit;   
            } else {
            //Failure message.
            $_SESSION['message'] = '¡Error al editar receta!';
            $_SESSION['message_alert'] = "danger";

//The page is redirected to the add_units.php.
            header('Location: ' . root . 'edit?recipename=' . $oldName. '&username=' . $userName);
            exit;
            }
        } else {
//Failure message.
            $_SESSION['message'] = $uploadOk;
            $_SESSION['message_alert'] = "danger";

//The page is redirected to the add_units.php.
            header('Location: ' . root . 'edit?recipename=' . $oldName. '&username=' . $userName);
            exit;
        }     
    }
} else {
//Message if the variable is null.
    $_SESSION['message'] = '¡Tiempo de cocción incorrecto!';
    $_SESSION['message_alert'] = "danger";
            
//The page is redirected to the edit.php
    header('Location: ' . root . 'edit?recipename='. $oldName. '&username=' . $userName);
    exit;
}  

/************************************************************************************************/
/******************************************CATEGORY UPDATE CODE***********************************/
/************************************************************************************************/

if(isset($_POST['categoryName']) || isset($_FILES["categoryImage"])) {

$categoryId = $_GET["categoryid"];

$filter = new Filter ($_POST["categoryName"], FILTER_SANITIZE_STRING, $conn);  
$newCategoryName = $filter -> sanitization();

$categoryImage = $_FILES["categoryImage"];

$stmt = $conn -> prepare("SELECT category FROM categories WHERE categoryid = ? AND state = 1;"); 
$stmt->bind_param("i", $categoryId);
$stmt->execute();

$result = $stmt -> get_result(); 
$row = $result -> fetch_assoc();   

$oldCategoryName = $row['category'];

//Input validation object  
$inputs = ["La categoría" => [$newCategoryName, [2,20], "incorrecta", true], 
"La imagen de la categoría" => [$categoryImage, [], "incorrecta", true]];

$message = new InputValidation ($inputs, "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]/");  
$message = $message -> lengthValidation();

    if(count($message) > 0) {
        $_SESSION['message'] = $message [0];
        $_SESSION['message_alert'] = $message [1];          
//The page is redirected to the edit.php
        header('Location: ' . root . 'edit?categoryid=' . $categoryId);
        exit;
    }  
   
    if($categoryImage['name'] == null) {            
        $arrCategoryFiles = array();
        $iterator = new FilesystemIterator("imgs/categories");

        foreach($iterator as $fileName) {
            $arrCategoryFiles[] = pathinfo($fileName->getFilename(), PATHINFO_FILENAME);
            $arrCategoryExt[] = pathinfo($fileName->getFilename(), PATHINFO_EXTENSION);
        }

        if (in_array($oldCategoryName, $arrCategoryFiles)){
            $fileIndex = array_search($oldCategoryName, $arrCategoryFiles);
            $fileExt = $arrCategoryExt[$fileIndex];

            $categoryDir = "imgs/categories/" . $oldCategoryName . "." . $fileExt;
            $newCategoryDir = "imgs/categories/" . $newCategoryName . "." . $fileExt;
            
            if(is_file($categoryDir)){

                rename($categoryDir, $newCategoryDir);

                $result = $conn->query("UPDATE categories SET category = '$newCategoryName' WHERE categoryid = '$categoryId' AND state = 1;");
                if ($result) {
//Notification message        
                    $log_message = "Has actualizado la categoría \"" . $oldCategoryName . "\" por el nuevo nombre \"" . $newCategoryName . "\".";       
                    $type = "update";

                    if($_SESSION['notification'] == 1) {
                        $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
                    }

//Message if the variable is null.
                    $_SESSION['message'] = '¡La categoría ha sido editada!';
                    $_SESSION['message_alert'] = "success";
                        
//The page is redirected to the add_units.php.
                    header('Location: ' . root . 'categories'); 
                    exit;
                } else {
//Message if the variable is null.
                    $_SESSION['message'] = '¡Error al editar categoría!';
                    $_SESSION['message_alert'] = "danger";
                        
//The page is redirected to the edit.php
                    header('Location: ' . root . 'edit?categoryid=' . $categoryId);
                    exit;
                }
            }
        }
    } else {
        $target_dir = "imgs/categories/";
        $ext = strtolower(pathinfo($categoryImage["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . $newCategoryName . "." . $ext;

        if(is_file($target_file)){
            unlink($target_file);
        }
        
        $categorySubmit = isset($_POST["categoryeditionsubmit"]) ? $_POST["categoryeditionsubmit"] : 0;

        $admittedFormats = ["jpg"];

//Image verification        
        $uploadOk = new ImageVerif($categorySubmit, $categoryImage["tmp_name"], $target_file, 300000, $categoryImage["size"], $admittedFormats, $ext);
        $uploadOk = $uploadOk -> file_extention();    

        if ($uploadOk == "") {
            if(move_uploaded_file($categoryImage["tmp_name"], $target_file) && $conn->query($sql)){
//Notification message        
            $log_message = "Has actualizado la categoría \"" . $oldCategoryName . "\" por el nuevo nombre \"" . $newCategoryName . "\".";       
            $type = "update";

            if($_SESSION['notification'] == 1) {
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }

//Success message.
            $_SESSION['message'] = '¡Categoría editada con éxito!';
            $_SESSION['message_alert'] = "success";

//The page is redirected to the add_units.php.
            header('Location: ' . root . 'categories'); 
            exit;   
            } else {
//Failure message.
            $_SESSION['message'] = '¡Error al editar categoría!';
            $_SESSION['message_alert'] = "danger";

//The page is redirected to the add_units.php.
            header('Location: ' . root . 'edit?categoryid=' . $categoryId);
            exit;
        }
        } else {
//Failure message.
            $_SESSION['message'] = $uploadOk;
            $_SESSION['message_alert'] = "danger";

//The page is redirected to the add_units.php.
            header('Location: ' . root . 'edit?categoryid=' . $categoryId);
            exit;
        }
    }    
}

/************************************************************************************************/
/******************************************USER UPDATE CODE*************************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['firstname']) && isset($_GET['userid']) && isset($_POST['lastname']) && isset($_POST['sex']) && isset($_POST['username']) && isset($_POST['userrol']) && isset($_POST['useremail']) && isset($_POST['new_password']) && isset($_POST['repite_password']) && isset($_POST['current_password'])  || isset($_FILES["profile"])){
  
    date_default_timezone_set("America/Santo_Domingo");

    $filter = new Filter ($_POST['firstname'], FILTER_SANITIZE_STRING, $conn);
    $firstname = $filter -> sanitization();

    $filter = new Filter ($_POST['lastname'], FILTER_SANITIZE_STRING, $conn);
    $lastname = $filter -> sanitization();

    $filter = new Filter ($_POST['username'], FILTER_SANITIZE_STRING, $conn);
    $username = $filter -> sanitization();

    $filter = new Filter ($_POST['useremail'], FILTER_SANITIZE_EMAIL, $conn);
    $userEmail = $filter -> sanitization();

    $filter = new Filter ($_POST['current_password'], FILTER_SANITIZE_STRING, $conn);
    $actualPassword = $filter -> sanitization();

    $filter = new Filter ($_POST['new_password'], FILTER_SANITIZE_STRING, $conn);
    $newPassword = $filter -> sanitization();

    $filter = new Filter ($_POST['repite_password'], FILTER_SANITIZE_STRING, $conn);
    $againNewPassword = $filter -> sanitization();

    $userId = $_GET['userid'];  
    $userRol = $_POST['userrol'];
    $state = isset($_POST['activeuser']) ? "yes" : "no";
    $sex = $_POST['sex'];
    $updateTime =  date("Y-m-d H:i:s");
    $profileImg = isset($_FILES["profile"]) ? $_FILES["profile"] : "";
 
    if($state == "yes") {
        $state = 1;        
    } else {
        $state = 0;
    }

/*** Picture handler ***/
    if($profileImg["name"] != ""){
        $target_dir = "imgs/users/";
//If the directory doesnt exist it's created
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

//Delete an old image if it exists
        $files = new Directories($target_dir, $_SESSION["username"]);
        $ext = $files -> directoryFiles();

        if($ext !== null) {
          $imageDir = $target_dir . $username . "." . $ext;

          unlink($imageDir);
        }        

//New picture data
        $ext = strtolower(pathinfo($profileImg["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . $_SESSION["username"] . "." . $ext;
        
        $userSubmit = isset($_POST["usersubmit"]) ?$_POST["usersubmit"] : 0;

        $admittedFormats = ["jpg", "jpeg", "png", "gif", "webp"];

//Image verification        
        $uploadOk = new ImageVerif($userSubmit, $profileImg["tmp_name"], $target_file, 300000, $profileImg["size"], $admittedFormats, $ext);
        $uploadOk = $uploadOk -> file_extention();  

        if ($uploadOk == "") {
            move_uploaded_file($profileImg["tmp_name"], $target_file);
        }
    }

//Input validation object  
    $inputs = ["El nombre" => [$firstname, [2,30], "incorrecto", true], 
    "El apellido" => [$lastname, [2,40], "incorrecto", true],
    "El usuario" => [$username, [2,30], "incorrecto", true],   
    "El correo electrónico" => [$userEmail, [15,70], "incorrecto", false]]; 

    $message = new InputValidation ($inputs, "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]/");  
    $message = $message -> lengthValidation();

    if(count($message) > 0) {
        $_SESSION['message'] = $message [0];
        $_SESSION['message_alert'] = $message [1];          

        header('Location: ' . root . 'edit?userid='. $userId);
        exit;
    } 

    if($actualPassword != "" && $newPassword != "" && $againNewPassword != ""){
        if(strlen($actualPassword) < 8 ||  strlen($actualPassword) > 50 || strlen($newPassword) < 8 ||  strlen($newPassword) > 50 || strlen($againNewPassword) < 8 ||  strlen($againNewPassword) > 50) {
            $_SESSION['message'] = '¡Cantidad de caracteres no aceptada!';
            $_SESSION['message_alert'] = "danger";

//The page is redirected to the edit.php
            header('Location: ' . root . 'edit?userid='. $userId);
            exit;
        }

        if($newPassword == $againNewPassword){
            $stmt = $conn -> prepare("SELECT password FROM users WHERE userid = ?;"); 
            $stmt->bind_param("i", $userId);
            $stmt->execute();

            $result = $stmt -> get_result(); 
            $row = $result -> fetch_assoc();   

            if (password_verify($actualPassword, $row['password'])){
                $hash_password = password_hash($newPassword, PASSWORD_DEFAULT);
                $result = $conn->query("UPDATE users SET password = '$hash_password', firstname = '$firstname',  lastname = '$lastname', username = '$userName', type = '$userRol', email = '$userEmail', state='$state', sex = '$sex' WHERE userid = '$userId';");

                if ($result) {
//Notification message        
                $log_message = "Has actualizado el usuario \"" . $username . "\".";       
                $type = "update";

                if($_SESSION['notification'] == 1) {
                    $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
                }                            

//Message if the variable is null.
                $_SESSION['message'] = '¡Usuario editado correctamente!';
                $_SESSION['message_alert'] = "success";

//The page is redirected to the edit.php
                header('Location: ' . root . 'edit?userid='. $userId);
                exit;
                            
                    if($_SESSION["userid"] == $userId){
//The page is redirected to the profile.php
                        header('Location: ' . root . 'profile');
                        exit;
                    } else {
//The page is redirected to the user.php
                        header('Location: ' . root . 'user');
                        exit;
                    }
                } else {
//Message if the variable is null.
                $_SESSION['message'] = '¡Error al editar usuario!';
                $_SESSION['message_alert'] = "danger";

//The page is redirected to the edit.php
                header('Location: ' . root . 'edit?userid='. $userId);
                exit;
                }  
            } else {
//Message if the variable is null.
                $_SESSION['message'] = '¡Contraseña actual incorrecta!';
                $_SESSION['message_alert'] = "danger";
                    
//The page is redirected to the edit.php
                header('Location: ' . root . 'edit?userid='. $userId);
                exit;
            }
        } else {
//Message if the variable is null.
            $_SESSION['message'] = '¡Contraseñas nuevas no coinciden!';
            $_SESSION['message_alert'] = "danger";
                
//The page is redirected to the edit.php
            header('Location: ' . root . 'edit?userid='. $userId);
            exit;
        }
    } else {            
        $result = $conn->query("UPDATE users SET firstname = '$firstname', lastname = '$lastname', username = '$username', type = '$userRol', email = '$userEmail', state='$state', sex = '$sex' WHERE userid = '$userId';");
        
        if ($result) {
//Notification message        
        $log_message = "Has actualizado el usuario \"" . $username . "\".";       
        $type = "update";
        
        if($_SESSION['notification'] == 1) {
            $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
        }

//Message if the variable is null.
        $_SESSION['message'] = '¡Usuario editado correctamente!';
        $_SESSION['message_alert'] = "success";

//The page is redirected to the edit.php
        header('Location: ' . root . 'edit?userid='. $userId);
        exit;
            
            if($_SESSION["userid"] == $userId){
//The page is redirected to the profile.php
                header('Location: ' . root . 'profile');
                exit;
            } else {
//The page is redirected to the user.php
                header('Location: ' . root . 'user');
                exit;
            }
        } else {
//Message if the variable is null.
        $_SESSION['message'] = '¡Error al editar usuario!';
        $_SESSION['message_alert'] = "danger";

//The page is redirected to the edit.php
        header('Location: ' . root . 'edit?userid='. $userId);
        exit;
        }  
    }        
}

/************************************************************************************************/
/*********************************RESTORE FROM RECIPE BIN CODE***********************************/
/************************************************************************************************/

//receive the data
if(isset($_GET['id']) && isset($_GET['table'])) {
    $id = $_GET['id'];
    $table = $_GET['table'];

//Possible tables
    $tables = ["recipe", "ingredients", "categories", "diet"];

    if(!in_array($table, $tables)) {
        header('Location: ' . root . 'error404');
        exit;     
    }

//id determination
    switch ($table) {
        case "recipe":
            $idName = "recipeid";
            break;
        case "ingredients":
            $idName = "id";
            break;  
        case "diet":
            $idName = "id";
            break;                  
        default:
            $idName = "categoryid";
    }   

    $sql = "UPDATE $table SET state = 1 WHERE $idName = '$id';";
    $sql .= "DELETE FROM recycle WHERE elementid = '$id';";

    if($conn -> multi_query($sql)) {
        $_SESSION['message'] = '¡Elemento restaurado exitosamente!';
        $_SESSION['message_alert'] = "success";

        header('Location: ' . root . 'recycle');
        exit;
    } else {
        $_SESSION['message'] = '¡Error al restaurar elemento!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'recycle');
        exit;
    } 
}

/************************************************************************************************/
/******************************************SETTING UPDATE CODE***********************************/
/************************************************************************************************/

if(isset($_GET['settingid'])) {

    $notification = isset($_POST['notification']) ? 1 : 0;
    $shares = isset($_POST["share"]) ? 1 : 0;    
    $recycle = isset($_POST["recycle"]) ? 1 : 0;
    $reminders = isset($_POST["reminder"]) ? 1 : 0;

    $result = $conn -> query("UPDATE users SET notification = '$notification',  shares = '$shares', recycle = '$recycle', reminders = '$reminders' WHERE username = '" . $_SESSION['username'] . "';");

    if($result) {
//Session variables reassignations
        $_SESSION['notification'] = $notification;
        $_SESSION['shares'] = $shares;
        $_SESSION['recycle'] = $recycle;
        $_SESSION['message'] = '¡Los cambios han sido aplicados!';
        $_SESSION['message_alert'] = "success";

        header('Location: ' . root . 'settings');
        exit;

    } else {
        $_SESSION['message'] = '¡Error al aplicar cambios!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'settings');
        exit;
    }
}

//Exit connection
$conn->close();

//Verify that data comes
if(empty($_POST) || empty($_GET)) {
    header('Location: ' . root);
    exit;  
}
?>