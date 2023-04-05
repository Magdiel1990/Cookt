<?php
//Including the database connection.
require_once ("../config/db_Connection.php");

//Head of the page.
require_once ("../modules/head.php");

//Models.
require_once ("../models/models.php");


/************************************************************************************************/
/***************************************RECIPE DELETION CODE*************************************/
/************************************************************************************************/


//Verifying that the id value comes with data.
if(isset($_GET['recipename'])){
    
//Getting the name.
$recipeName = $_GET['recipename'];

$sql = "DELETE FROM recipe WHERE recipename = '$recipeName' AND username = '" . $_SESSION['username'] . "';";
$result = $conn -> query($sql);

//If there's no record with that name, a message is sent.
    if(!$result){
//Creation of the message of error deleting the receta.
        $_SESSION['message'] = '¡Error al eliminar la receta!';
        $_SESSION['message_alert'] = "danger";

//The page is redirected to the index.php.
        header('Location: ../index.php');
    } else {
        $target_dir = "../imgs/recipes/". $_SESSION['username']  ."/";

        $files = new Directories($target_dir, $recipeName);
        $imgRecipeDir = $files -> directoryFiles();

        unlink($imgRecipeDir);
        
//Creation of the message of success deleting the receta.
        $_SESSION['message'] = '¡Receta eliminada!';
        $_SESSION['message_alert'] = "success";

//After the receta has been deleted, the page is redirected to the index.php.
        header('Location: ../index.php');
    }
}


/************************************************************************************************/
/***************************************UNIT DELETION CODE***************************************/
/************************************************************************************************/


//Verifying that the id value comes with data.
if(isset($_GET['unitname'])){
    
//Getting the name.
$unitname = $_GET['unitname'];

//Deleting the register with the name received.
$sql = "DELETE FROM units WHERE unit = '$unitname';";

$result = $conn -> query($sql);

//If there's no record with that name, a message is sent.

    if($result !== true){
//Creation of the message of error deleting the receta.
        $_SESSION['message'] = '¡Error al eliminar la unidad!';
        $_SESSION['message_alert'] = "danger";

//The page is redirected to the add-units.php
        header('Location: ../views/add-units.php');
    } else {
//Creation of the message of success deleting the receta.
        $_SESSION['message'] = '¡Unidad eliminada!';
        $_SESSION['message_alert'] = "success";

//After the receta has been deleted, the page is redirected to the add-units.php.
        header('Location: ../views/add-units.php');
    }
} 


/************************************************************************************************/
/***************************************CATEGORY DELETION CODE***************************************/
/************************************************************************************************/


//Verifying that the id value comes with data.
if(isset($_GET['categoryname'])){
    
//Getting the name.
$categoryName = $_GET['categoryname'];

$categoryDir = "../imgs/categories/";

//Function to get the image directory from the category
$files = new Directories($categoryDir , $categoryName);
$categoryImgDir = $files -> directoryFiles();

unlink($categoryImgDir);

//Deleting the register with the name received.
$sql = "DELETE FROM categories WHERE category = '$categoryName';";

$result = $conn -> query($sql);

//If there's no record with that name, a message is sent.

    if($result !== true){
    //Creation of the message of error deleting the receta.
        $_SESSION['message'] = '¡Error al eliminar la categoría!';
        $_SESSION['message_alert'] = "danger";

    //The page is redirected to the add_units.php
        header('Location: ../views/add-categories.php');
    } else {
    //Creation of the message of success deleting the receta.
        $_SESSION['message'] = '¡Categoría eliminada!';
        $_SESSION['message_alert'] = "success";

    //After the receta has been deleted, the page is redirected to the add_units.php.
        header('Location: ../views/add-categories.php');
    }
} 



/************************************************************************************************/
/********************INGREDIENT DELETION WHEN ADDING THE RECIPE CODE*****************************/
/************************************************************************************************/


//Verifying that the id value comes with data.
if(isset($_GET['id'])){
    
//Getting the name.
$recipeid = $_GET['id'];

//Deleting the register with the name received.
$sql = "DELETE FROM reholder WHERE re_id = $recipeid AND username = '" . $_SESSION['username'] . "';";

$result = $conn -> query($sql);

//If there's no record with that name, a message is sent.

    if(!$result){
//Creation of the message of error deleting the receta.
        $_SESSION['message'] = '¡Error al eliminar ingrediente!';
        $_SESSION['message_alert'] = "danger";

//The page is redirected to the add_units.php
        header('Location: ../views/add-recipe.php');
    } else {
//Creation of the message of success deleting the receta.
        $_SESSION['message'] = '¡Ingrediente eliminado!';
        $_SESSION['message_alert'] = "success";

//After the receta has been deleted, the page is redirected to the add_units.php.
        header('Location: ../views/add-recipe.php');
    }
}


/************************************************************************************************/
/***************************************INGREDIENT DELETION CODE*********************************/
/************************************************************************************************/


//Verifying that the ingredientname value comes with data.
if(isset($_GET['ingredientname'])){
    
//Getting the name.
$ingredientName = $_GET['ingredientname'];

$sql = "DELETE FROM ingredients WHERE ingredient = '$ingredientName' AND username = '" . $_SESSION['username'] . "';";
$result = $conn -> query($sql);

//If there's no record with that name, a message is sent.

    if(!$result){
    //Creation of the message of error deleting the receta.
        $_SESSION['message'] = '¡Error al eliminar ingrediente!';
        $_SESSION['message_alert'] = "danger";

    //The page is redirected to the add_units.php
        header('Location: ../views/add-ingredients.php');
    } else {
    //Creation of the message of success deleting the receta.
        $_SESSION['message'] = '¡Ingrediente eliminado!';
        $_SESSION['message_alert'] = "success";

    //After the receta has been deleted, the page is redirected to the add_units.php.
        header('Location: ../views/add-ingredients.php');
    }
} 

/************************************************************************************************/
/***************************************CHOOSE BY INGREDIENT***********************************/
/************************************************************************************************/

if(isset($_GET['custom'])){
    
//Getting the name.
$customName = $_GET['custom'];

$sql = "SELECT id FROM ingredients WHERE ingredient = '$customName' AND username = '" . $_SESSION['username'] . "';";
$row = $conn -> query($sql) -> fetch_assoc();
$ingredientId = $row['id'];

$sql = "DELETE FROM ingholder WHERE ingredientid = '$ingredientId' AND username = '" . $_SESSION['username'] . "';";

$result = $conn -> query($sql);

//If there's no record with that name, a message is sent.

    if($result !== true){
//Creation of the message of error deleting the receta.
        $_SESSION['message'] = '¡Error al eliminar el ingrediente!';
        $_SESSION['message_alert'] = "danger";

//The page is redirected to the add_units.php
        header('Location: ../views/custom-recipe.php');
    } else {
//Creation of the message of success deleting the receta.
        $_SESSION['message'] = '¡Ingrediente eliminado!';
        $_SESSION['message_alert'] = "success";

//After the receta has been deleted, the page is redirected to the add_units.php.
        header('Location: ../views/custom-recipe.php');
    }
} 


/************************************************************************************************/
/*****************************INGREDIENT DELETION FROM RECIPE CODE*******************************/
/************************************************************************************************/


//Verifying that the id value comes with data.
if(isset($_GET['indication']) && isset($_GET['rpename']) && isset($_GET['username'])){
    
//Getting the name.
$ingredientFullName = $_GET['indication'];
$recipeName = $_GET['rpename'];
$ingredientFullNameArray = explode(" ",$ingredientFullName);
$quantity = $ingredientFullNameArray[0];
$unit = $ingredientFullNameArray[1];
$ingredient = $ingredientFullNameArray[3];
$userName = $_GET['username'];

$sql = "SELECT recipeid FROM recipe WHERE recipename = '$recipeName' AND username = '" . $_SESSION['username'] . "';";
$row = $conn -> query($sql) -> fetch_assoc();
$recipeId = $row['recipeid'];

$sql = "SELECT id FROM ingredients WHERE ingredient = '$ingredient' AND username = '" . $_SESSION['username'] . "';";
$row = $conn -> query($sql) -> fetch_assoc();
$ingredientId = $row['id'];

//Deleting the register with the name received.
$sql = "DELETE FROM recipeinfo WHERE recipeid = '$recipeId' AND unit = '$unit' AND quantity = '$quantity' AND ingredientid = '$ingredientId';";

$result = $conn -> query($sql);

//If there's no record with that name, a message is sent.

    if(!$result){
//Creation of the message of error deleting the receta.
        $_SESSION['message'] = '¡Error al eliminar ingrediente!';
        $_SESSION['message_alert'] = "danger";

//The page is redirected to the add_units.php
        header("Location: edit.php?recipename=" . $recipeName . '&username=' . $userName);
    } else {
//Creation of the message of success deleting the receta.
        $_SESSION['message'] = '¡Ingrediente eliminado!';
        $_SESSION['message_alert'] = "success";

//After the receta has been deleted, the page is redirected to the add_units.php.
        header("Location: edit.php?recipename=". $recipeName . '&username=' . $userName);
    }
}


/************************************************************************************************/
/***************************************USER DELETION CODE***************************************/
/************************************************************************************************/


//Verifying that the id value comes with data.
if(isset($_GET['userid'])) {
    
//Getting the name.
$userId = $_GET['userid'];

$sql = "SELECT username FROM users WHERE userid = '$userId';";
$row = $conn -> query($sql) -> fetch_assoc();
$username = $row['username'];
$target_dir = "../imgs/recipes/" . $username;

    if(file_exists($target_dir)) {
        unlink($target_dir);
    }
    
    //Deleting the register with the name received.
    $sql = "DELETE FROM users WHERE userid = '$userId';";

    $result = $conn -> query($sql);

    if(!$result){
//Creation of the message of error deleting the receta.
        $_SESSION['message'] = '¡Error al eliminar usuario!';
        $_SESSION['message_alert'] = "danger";

//The page is redirected to the add_units.php
        header("Location: ../views/add-users.php");
    } else {
//Creation of the message of success deleting the receta.
        $_SESSION['message'] = '¡Usuario eliminado!';
        $_SESSION['message_alert'] = "success";

//After the receta has been deleted, the page is redirected to the add_units.php.
        header("Location: ../views/add-users.php");
    }
}
//Exiting the connection to the database.
$conn -> close(); 

//We include the footer (jquery, bootstrap and popper scripts).
include("../modules/footer.php");
?>