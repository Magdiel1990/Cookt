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
            
    //The page is redirected to the add_recipe.php
        header('Location: edit.php');

    } else {
        $sql = "UPDATE reholder SET ingredient = '$ingredient', quantity = '$quantity', unit = '$unit' WHERE re_id = $id";
                
        if ($conn->query($sql)) {
         //Message if the variable is null.
        $_SESSION['message'] = '¡El ingrediente ha sido editado!';
        $_SESSION['message_alert'] = "success";
            
        //The page is redirected to the add_recipe.php
        header('Location: ../views/add_recipe.php');
        } else {
         //Message if the variable is null.
        $_SESSION['message'] = '¡Error al editar ingrediente!';
        $_SESSION['message_alert'] = "danger";
            
        //The page is redirected to the add_recipe.php
        header('Location: edit.php');
        }               
    }
}



/************************************************************************************************/
/******************************************RECIPE UPDATE CODE************************************/
/************************************************************************************************/


if(isset($_GET["editname"]) || isset($_POST["newRecipeName"]) || isset($_POST["category"])
|| isset($_POST["cookingTime"]) || isset($_POST["preparation"]) || isset($_POST["observation"])){

$oldName = $_GET["editname"];
$newRecipeName = sanitization($_POST["newRecipeName"], FILTER_SANITIZE_STRING, $conn);
$category = $_POST["category"];
$cookingTime = sanitization($_POST["cookingTime"], FILTER_SANITIZE_NUMBER_INT, $conn);
$preparation = sanitization($_POST["preparation"], FILTER_SANITIZE_STRING, $conn);
$observation = sanitization($_POST["observation"], FILTER_SANITIZE_STRING, $conn);

$sql = "SELECT categoryid FROM categories WHERE category = '$category';";
$row = $conn -> query($sql) -> fetch_assoc();

$categoryId = $row['categoryid'];

    if($newRecipeName == "" || $cookingTime == "" || $preparation == ""){
    //Message if the variable is null.
        $_SESSION['message'] = '¡Complete todos los campos!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add_recipe.php
        header("Location: edit.php?recipename=". $oldName);
    } else {
        if($cookingTime >= 5 && $cookingTime <= 180){
            
            $sql = "UPDATE recipe SET recipename = '$newRecipeName' WHERE recipename = '$oldName';";
            $sql .= "UPDATE recipe SET preparation = '$preparation' WHERE recipename = '$oldName';";
            $sql .= "UPDATE recipe SET cookingtime = '$cookingTime' WHERE recipename = '$oldName';";
            $sql .= "UPDATE recipe SET observation = '$observation' WHERE recipename = '$oldName';";
            $sql .= "UPDATE recipe SET categoryid = '$categoryId' WHERE recipename = '$oldName';";
            
            if ($conn->multi_query($sql)) {
            //Message if the variable is null.
            $_SESSION['message'] = '¡Receta editada correctamente!';
            $_SESSION['message_alert'] = "success";
                
            //The page is redirected to the add_recipe.php
            header("Location: edit.php?recipename=". $newRecipeName);
            } else {
            //Message if the variable is null.
            $_SESSION['message'] = '¡Error al editar receta!';
            $_SESSION['message_alert'] = "danger";

            //The page is redirected to the add_recipe.php
            header("Location: edit.php?recipename=". $oldName);
            }  
        } else {
            //Message if the variable is null.
            $_SESSION['message'] = '¡Tiempo de cocción incorrecto!';
            $_SESSION['message_alert'] = "danger";
                
            //The page is redirected to the add_recipe.php
            header("Location: edit.php?recipename=". $oldName);
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
            
    //The page is redirected to the add_recipe.php
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
                    header('Location: ../views/add_categories.php'); 
                } else {
                    //Message if the variable is null.
                    $_SESSION['message'] = '¡Error al editar categoría!';
                    $_SESSION['message_alert'] = "danger";
                        
                    //The page is redirected to the add_recipe.php
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
            $_SESSION['message'] = '¡Categoría editada con éxito!';
            $_SESSION['message_alert'] = "success";

            //The page is redirected to the add_units.php.
            header('Location: ../views/add_categories.php');    
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

?>

<?php
 $conn->close();
?>