<?php
//Head of the page.
require_once ("views/partials/head.php");


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

$sql = "SELECT categoryid FROM categories WHERE category = '$category';";
$row = $conn -> query($sql) -> fetch_assoc();

$categoryId = $row['categoryid'];

    if($newRecipeName == "" || $cookingTime == "" || $preparation == "" || $ingredients == ""){
//Message if the variable is null.
        $_SESSION['message'] = '¡Complete todos los campos!';
        $_SESSION['message_alert'] = "danger";
            
//The page is redirected to the edit.php
        header('Location: ' . root . 'edit?recipename='. $oldName . '&username=' . $userName);
        exit;
    } else {
//Cooking time verification    
        if($cookingTime >= 5 && $cookingTime <= 180){            

            if($recipeImage['name'] == null && $_POST["imageUrl"] == "") {
//Data update            
                $sql = "UPDATE recipe SET recipename = '$newRecipeName', preparation = '$preparation', ingredients = '$ingredients', cookingtime = '$cookingTime', categoryid = '$categoryId' WHERE recipename = '$oldName' AND username = '$userName';";
                if ($conn->query($sql)) {
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
                $sql = "UPDATE recipe SET recipename = '$newRecipeName', preparation = '$preparation', ingredients = '$ingredients', cookingtime = '$cookingTime', categoryid = '$categoryId' WHERE recipename = '$oldName' AND username = '$userName';";
                if ($conn->query($sql)) {
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
                        $recipeImagesDir = "imgs/recipes/". $_SESSION['username'];

                        if (!file_exists($recipeImagesDir)) {
                            mkdir($recipeImagesDir, 0777, true);
                        }
                    
                        $ext = pathinfo($url, PATHINFO_EXTENSION);
                        $uploadOk = "";
//Format verification
                        if($ext  != "jpg" && $ext  != "jpeg" && $ext != "png" && $ext  != "gif") {
                            $uploadOk = '¡Formato de imagen no admitido!';
                        }   
//Size verification                
                        if(array_change_key_case(get_headers($url,1))['content-length'] > 300000){
                            $uploadOk = '¡El tamaño debe ser menor que 300 KB!';
                        }
//Deleting the old img
                        $files = new Directories($recipeImagesDir, $oldName);
                        $imgOldRecipeDir = $files -> directoryFiles();

                        if($imgOldRecipeDir !== false) {
                            if(file_exists($imgOldRecipeDir)){
                                unlink($imgOldRecipeDir);
                            }
                        }
//New name for the saved image         
                        $recipeImagesDir = $recipeImagesDir . "/" . $newRecipeName . "." . $ext;

// Save image 
                        if(file_put_contents($recipeImagesDir, file_get_contents($url)) !== false){
                            $_SESSION['message'] = '¡Receta editada exitosamente!';
                            $_SESSION['message_alert'] = "success";

                            header('Location: ' . root . 'edit?recipename='. $newRecipeName .'&username=' . $userName);
                            exit;   
                        } else {
                            $_SESSION['message'] = $uploadOk;
                            $_SESSION['message_alert'] = "danger";

//The page is redirected to the edit.php
                            header('Location: ' . root . 'edit?recipename='. $oldName. '&username=' . $userName);
                            exit;
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
                $sql = "UPDATE recipe SET recipename = '$newRecipeName', preparation = '$preparation', ingredients = '$ingredients', cookingtime = '$cookingTime', categoryid = '$categoryId' WHERE recipename = '$oldName' AND username = '$userName';";
                
                $target_dir = "imgs/recipes/". $userName  ."/";
                
                $files = new Directories($target_dir, $oldName);
                $imgOldRecipeDir = $files -> directoryFiles();

//Delete the old img
                if($imgOldRecipeDir !== false) {
                    if(file_exists($imgOldRecipeDir)){
                        unlink($imgOldRecipeDir);
                    }
                }

                $fileExtension = strtolower(pathinfo($recipeImage["name"], PATHINFO_EXTENSION));
                $target_file = $target_dir . $newRecipeName . "." . $fileExtension;
                $uploadOk = "";

//Check if the new recipe img exists
                if(file_exists($target_file)){
                    unlink($target_file);
                }
                
// Check if image file is a actual image or fake image
                if(isset($_POST["edit"])) {
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
                if($fileExtension != "jpg" && $fileExtension != "jpeg" && $fileExtension != "png" && $fileExtension != "gif") {
                    $uploadOk = "¡Formato no admitido!";
                } 

                if ($uploadOk == "") {
                    if(move_uploaded_file($recipeImage["tmp_name"], $target_file) && $conn->query($sql)){
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
    }
}

/************************************************************************************************/
/******************************************CATEGORY UPDATE CODE***********************************/
/************************************************************************************************/


if(isset($_POST['categoryName']) || isset($_FILES["categoryImage"])){

$categoryId = $_GET["categoryid"];

$filter = new Filter ($_POST["categoryName"], FILTER_SANITIZE_STRING, $conn);  
$newCategoryName = $filter -> sanitization();

$categoryImage = $_FILES["categoryImage"];

$sql = "SELECT category FROM categories WHERE categoryid = '$categoryId';";
$row = $conn -> query($sql) -> fetch_assoc();
$oldCategoryName = $row['category'];


    if($newCategoryName == ""){
//Message if the variable is null.
        $_SESSION['message'] = '¡Llene todos los campos!';
        $_SESSION['message_alert'] = "danger";
            
//The page is redirected to the edit.php
        header('Location: ' . root . 'edit?categoryid=' . $categoryId);
        exit;
    } 

    if(strlen($newCategoryName) > 20 || strlen($newCategoryName) < 2) {
        $_SESSION['message'] = '¡Longitud de categoría incorrecta!';
        $_SESSION['message_alert'] = "danger";
            
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

                $sql = "UPDATE categories SET category = '$newCategoryName' WHERE categoryid = '$categoryId';";
                if ($conn->query($sql)) {
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
        $fileExtension = strtolower(pathinfo($categoryImage["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . $newCategoryName . "." . $fileExtension;
        $uploadOk = "";

        if(is_file($target_file)){
            unlink($target_file);
        }
        
// Check if image file is a actual image or fake image
        if(isset($_POST["categoryeditionsubmit"])) {
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
            if(move_uploaded_file($categoryImage["tmp_name"], $target_file) && $conn->query($sql)){
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
    $pattern = "/[a-zA-Z áéíóúÁÉÍÓÚñÑ\t\h]+|(^$)/";
 
    if($state == "yes") {
        $state = 1;        
    } else {
        $state = 0;
    }

    if($profileImg["name"] != ""){
        $target_dir = "imgs/users/";
//If the directory doesnt exist it's created
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $fileExtension = strtolower(pathinfo($profileImg["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . $_SESSION["username"] . "." . $fileExtension;
        $uploadOk = "";

        $files = new Directories($target_dir, $_SESSION["username"]);
        $imgProfileDir = $files -> directoryFiles();
        
//Formats
        $formats = array("jpg", "jpeg", "gif", "png");

        if(in_array(pathinfo($imgProfileDir, PATHINFO_EXTENSION), $formats)){
            unlink($imgProfileDir);
        }
// Check if image file is a actual image or fake image
        if(isset($_POST["usersubmit"])) {
            $check = getimagesize($profileImg["tmp_name"]);
            if($check == false) {
                $uploadOk = "¡Este archivo no es una imagen!";
            } 
        }
// Check if file already exists
        if (file_exists($target_file)) {
            $uploadOk = "¡Esta imagen ya existe!";
        }

// Check file size
        if ($profileImg["size"] > 300000) {
            $uploadOk = "¡El tamaño debe ser menor que 300 KB!";
        }

// Allow certain file formats
        if($fileExtension != "jpg" && $fileExtension != "png" && $fileExtension != "jpeg"
        && $fileExtension != "gif" ) {
            $uploadOk = "¡Formato no admitido!";
        } 

        if ($uploadOk == "") {
            move_uploaded_file($profileImg["tmp_name"], $target_file);
        }
    }

    if ($firstname == "" || $lastname == "" || $username == "" || $sex == "" || $userEmail == "") {
//Message if the variable is null.
        $_SESSION['message'] = '¡Complete todos los campos faltantes!';
        $_SESSION['message_alert'] = "danger";
            
//The page is redirected to the edit.php
        header('Location: ' . root . 'edit?userid='. $userId);
        exit;
    } else {
        if (!preg_match($pattern, $firstname) || !preg_match($pattern, $lastname) || !preg_match($pattern, $username)){
                $_SESSION['message'] = '¡Nombre, apellido o usuario incorrecto!';
                $_SESSION['message_alert'] = "danger";

//The page is redirected to the edit.php
                header('Location: ' . root . 'edit?userid='. $userId);
                exit;
            } else {
            if(strlen($firstname) < 2 || strlen($firstname) > 30 || strlen($lastname) < 2 || strlen($lastname) > 40 || strlen($username) < 2 || strlen($username) > 30 || strlen($userEmail) < 15 || strlen($userEmail) > 70) {
                $_SESSION['message'] = '¡Cantidad de caracteres no aceptada!';
                $_SESSION['message_alert'] = "danger";

//The page is redirected to the edit.php
                header('Location: ' . root . 'edit?userid='. $userId);
                exit;
            } else {
                if($actualPassword != "" && $newPassword != "" && $againNewPassword != ""){
                    if(strlen($actualPassword) < 8 ||  strlen($actualPassword) > 50 || strlen($newPassword) < 8 ||  strlen($newPassword) > 50 || strlen($againNewPassword) < 8 ||  strlen($againNewPassword)) {
                    $_SESSION['message'] = '¡Cantidad de caracteres no aceptada!';
                    $_SESSION['message_alert'] = "danger";

//The page is redirected to the edit.php
                    header('Location: ' . root . 'edit?userid='. $userId);
                    exit;
                    }

                    if($newPassword == $againNewPassword){
                        $sql = "SELECT password FROM users WHERE userid = '$userId ';";
                        $row = $conn -> query($sql) -> fetch_assoc();
                        if (password_verify($actualPassword, $row['password'])){
                            $hash_password = password_hash($newPassword, PASSWORD_DEFAULT);
                            $sql = "UPDATE users SET password = '$hash_password', firstname = '$firstname',  lastname = '$lastname', username = '$userName', type = '$userRol', email = '$userEmail', state='$state', sex = '$sex', updated_at = '$updateTime' WHERE userid = '$userId';";

                            if ($conn->query($sql)) {
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
                    $sql = "UPDATE users SET firstname = '$firstname', lastname = '$lastname', username = '$userName', type = '$userRol', email = '$userEmail', state='$state', sex = '$sex', updated_at = '$updateTime' WHERE userid = '$userId';";
                    
                    if ($conn->query($sql)) {
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
        }
    }    
?>
<?php
}

$conn->close();
?>