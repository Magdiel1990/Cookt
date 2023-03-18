<?php
//Head of the page.
require_once ("../modules/head.php");

//Models
require_once ("../models/models.php");

//Including the database connection.
require_once ("../config/db_Connection.php");


/************************************************************************************************/
/******************************************REHOLDER UPDATE CODE***********************************/
/************************************************************************************************/


if(isset($_GET["editid"])){

$id = $_GET["editid"];
$quantity = $_POST["quantity"];
$unit = $_POST["unit"];
$ingredient = $_POST["ingredient"];

    if($quantity == "" || $quantity <= 0){
    //Message if the variable is null.
        $_SESSION['message'] = '¡Elija la cantidad por favor!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add-recipe.php
        header('Location: edit.php');

    } else {
        $sql = "SELECT id FROM ingredients WHERE ingredient = '$ingredient' AND username = '" . $_SESSION['username'] . "';";
        $row = $conn -> query($sql) -> fetch_assoc();
        $ingredientId = $row['id'];

        $sql = "UPDATE reholder SET ingredientid = '$ingredientId', quantity = '$quantity', unit = '$unit' WHERE re_id = $id AND username = '" . $_SESSION['username'] . "';";
                
        if ($conn->query($sql)) {
         //Message if the variable is null.
        $_SESSION['message'] = '¡El ingrediente ha sido editado!';
        $_SESSION['message_alert'] = "success";
            
        //The page is redirected to the add-recipe.php
        header('Location: ../views/add-recipe.php');
        } else {
         //Message if the variable is null.
        $_SESSION['message'] = '¡Error al editar ingrediente!';
        $_SESSION['message_alert'] = "danger";
            
        //The page is redirected to the add-recipe.php
        header('Location: edit.php?id='.$id);
        }               
    }
}



/************************************************************************************************/
/******************************************RECIPE UPDATE CODE************************************/
/************************************************************************************************/


if(isset($_GET["editname"]) || isset($_GET["username"]) || isset($_FILES["recipeImage"]) || isset($_POST["newRecipeName"]) || isset($_POST["category"])
|| isset($_POST["cookingTime"]) || isset($_POST["preparation"]) || isset($_POST["observation"])){

$oldName = $_GET["editname"];
$newRecipeName = sanitization($_POST["newRecipeName"], FILTER_SANITIZE_STRING, $conn);
$category = $_POST["category"];
$cookingTime = sanitization($_POST["cookingTime"], FILTER_SANITIZE_NUMBER_INT, $conn);
$preparation = sanitization($_POST["preparation"], FILTER_SANITIZE_STRING, $conn);
$observation = sanitization($_POST["observation"], FILTER_SANITIZE_STRING, $conn);
$recipeImage = $_FILES["recipeImage"];
$userName = $_GET["username"];

$sql = "SELECT categoryid FROM categories WHERE category = '$category';";
$row = $conn -> query($sql) -> fetch_assoc();

$categoryId = $row['categoryid'];

    if($newRecipeName == "" || $cookingTime == "" || $preparation == ""){
    //Message if the variable is null.
        $_SESSION['message'] = '¡Complete todos los campos!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add-recipe.php
        header("Location: edit.php?recipename=". $oldName . '&username=' . $userName);
    } else {
        if($cookingTime >= 5 && $cookingTime <= 180){

            if($recipeImage['name'] == null) {            
                $sql = "UPDATE recipe SET recipename = '$newRecipeName', preparation = '$preparation', cookingtime = '$cookingTime', observation = '$observation', categoryid = '$categoryId' WHERE recipename = '$oldName' AND username = '$userName';";
                if ($conn->query($sql)) {
                    //Message if the variable is null.
                    $_SESSION['message'] = '¡Receta editada con éxito!';
                    $_SESSION['message_alert'] = "success";
                        
                    //The page is redirected to the add-recipe.php
                    header("Location: edit.php?recipename=". $newRecipeName .'&username=' . $userName);
                    } else {
                    //Message if the variable is null.
                    $_SESSION['message'] = '¡Error al editar receta!';
                    $_SESSION['message_alert'] = "danger";

                    //The page is redirected to the add-recipe.php
                    header("Location: edit.php?recipename=". $oldName. '&username=' . $userName);
                    }  
                } else {
                $sql = "UPDATE recipe SET recipename = '$newRecipeName', preparation = '$preparation', cookingtime = '$cookingTime', observation = '$observation', categoryid = '$categoryId' WHERE recipename = '$oldName' AND username = '$userName';";
                
                $target_dir = "../imgs/recipes/". $userName  ."/";

                $imgOldRecipeDir = directoryFiles($target_dir, $oldName);
                unlink($imgOldRecipeDir);

                $fileExtension = strtolower(pathinfo($recipeImage["name"], PATHINFO_EXTENSION));
                $target_file = $target_dir . $newRecipeName . "." . $fileExtension;
                $uploadOk = "";

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

                    //The page is redirected to the add-recipe.php
                    header("Location: edit.php?recipename=". $newRecipeName. '&username=' . $userName);   
                    } else {
                    //Failure message.
                    $_SESSION['message'] = '¡Error al editar receta!';
                    $_SESSION['message_alert'] = "danger";

                    //The page is redirected to the add_units.php.
                    header('Location: edit.php?recipename=' . $oldName. '&username=' . $userName);
                }
                } else {
                    //Failure message.
                    $_SESSION['message'] = $uploadOk;
                    $_SESSION['message_alert'] = "danger";

                    //The page is redirected to the add_units.php.
                    header('Location: edit.php?recipename=' . $oldName. '&username=' . $userName);
                }     
            }
        } else {
                //Message if the variable is null.
                $_SESSION['message'] = '¡Tiempo de cocción incorrecto!';
                $_SESSION['message_alert'] = "danger";
                    
                //The page is redirected to the add-recipe.php
                header("Location: edit.php?recipename=". $oldName. '&username=' . $userName);
        }       
    }
}


/************************************************************************************************/
/******************************************CATEGORY UPDATE CODE***********************************/
/************************************************************************************************/


if(isset($_POST['categoryName']) || isset($_FILES["categoryImage"])){

$categoryId = $_GET["categoryid"];
$newCategoryName = sanitization($_POST["categoryName"], FILTER_SANITIZE_STRING, $conn);
$categoryImage = $_FILES["categoryImage"];

$sql = "SELECT category FROM categories WHERE categoryid = '$categoryId';";
$row = $conn -> query($sql) -> fetch_assoc();
$oldCategoryName = $row['category'];


    if($newCategoryName == ""){
    //Message if the variable is null.
        $_SESSION['message'] = '¡Llene todos los campos!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add-recipe.php
        header('Location: edit.php?categoryid=' . $categoryId);

    } 
    if($categoryImage['name'] == null) {            
        $arrCategoryFiles = array();
        $iterator = new FilesystemIterator("../imgs/categories");

        foreach($iterator as $fileName) {
            $arrCategoryFiles[] = pathinfo($fileName->getFilename(), PATHINFO_FILENAME);
            $arrCategoryExt[] = pathinfo($fileName->getFilename(), PATHINFO_EXTENSION);
        }

        if (in_array($oldCategoryName, $arrCategoryFiles)){
            $fileIndex = array_search($oldCategoryName, $arrCategoryFiles);
            $fileExt = $arrCategoryExt[$fileIndex];

            $categoryDir = "../imgs/categories/" . $oldCategoryName . "." . $fileExt;
            $newCategoryDir = "../imgs/categories/" . $newCategoryName . "." . $fileExt;
            
            if(is_file($categoryDir)){

                rename($categoryDir, $newCategoryDir);

                $sql = "UPDATE categories SET category = '$newCategoryName' WHERE categoryid = '$categoryId';";
                if ($conn->query($sql)) {
                    //Message if the variable is null.
                    $_SESSION['message'] = '¡La categoría ha sido editada!';
                    $_SESSION['message_alert'] = "success";
                        
                    //The page is redirected to the add_units.php.
                    header('Location: ../views/add-categories.php'); 
                } else {
                    //Message if the variable is null.
                    $_SESSION['message'] = '¡Error al editar categoría!';
                    $_SESSION['message_alert'] = "danger";
                        
                    //The page is redirected to the add-recipe.php
                    header('Location: edit.php?categoryid=' . $categoryId);
                }
            }
        }
    } else {
        $target_dir = "../imgs/categories/";
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
            header('Location: ../views/add-categories.php');    
            } else {
            //Failure message.
            $_SESSION['message'] = '¡Error al editar categoría!';
            $_SESSION['message_alert'] = "danger";

            //The page is redirected to the add_units.php.
            header('Location: edit.php?categoryid=' . $categoryId);
        }
        } else {
            //Failure message.
            $_SESSION['message'] = $uploadOk;
            $_SESSION['message_alert'] = "danger";

            //The page is redirected to the add_units.php.
            header('Location: edit.php?categoryid=' . $categoryId);
        }
    }    
}

/************************************************************************************************/
/******************************************USER UPDATE CODE*************************************/
/************************************************************************************************/


//receive the data
if(isset($_POST['userfullname']) || isset($_POST['username']) || isset($_POST['userpassword']) || isset($_POST['userrol']) || isset($_POST['useremail'])){
  $userId = $_GET['userid'];  
  $fullName = $_POST['userfullname'];
  $userName=  $_POST['username'];
  $userPassword = $_POST['userpassword'];
  $userRol = $_POST['userrol'];
  $userEmail = $_POST['useremail'];
  $state = $_POST['activeuser'];


    if($state == "yes") {
        $state = 1;        
    } else {
        $state = 0;
    }

    if ($fullName == "" || $userName == "" || $userPassword == "") {
    //Message if the variable is null.
        $_SESSION['message'] = '¡Complete todos los campos faltantes!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add-recipe.php
        header('Location: edit.php?userid='. $userId);
    } 

    $sql = "UPDATE users SET fullname = '$fullName', username = '$userName', password = '$userPassword', type = '$userRol', email = '$userEmail', state='$state' WHERE userid = '$userId';";

    if ($conn->query($sql)) {
    //Message if the variable is null.
    $_SESSION['message'] = '¡Usuario editado correctamente!';
    $_SESSION['message_alert'] = "success";
        
    //The page is redirected to the add-recipe.php
    header("Location: ../views/add-users.php");
    } else {
    //Message if the variable is null.
    $_SESSION['message'] = '¡Error al editar usuario!';
    $_SESSION['message_alert'] = "danger";

    //The page is redirected to the add-recipe.php
    header("Location: edit.php?userid=". $userId);
    }  
?>

<?php
}

$conn->close();
?>