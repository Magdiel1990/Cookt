<?php
//Head of the page.
require_once ("../modules/head.php");

//Including the database connection.
require_once ("../config/db_Connection.php");


/************************************************************************************************/
/***************************************RECIPE DELETION CODE*************************************/
/************************************************************************************************/


//Verifying that the id value comes with data.
if(isset($_GET['recipename'])){
    
//Getting the name.
$name = $_GET['recipename'];

//Deleting the register with the name received.
$sql = "DELETE FROM recipeinfo WHERE recipename = '$name';";
$sql .= "DELETE FROM recipe WHERE recipename = '$name';";

$result = $conn -> multi_query($sql);

//If there's no record with that name, a message is sent.

    if(!$result){
//Creation of the message of error deleting the receta.
        $_SESSION['message'] = 'Error al eliminar la receta!';
        $_SESSION['message_alert'] = "danger";

//The page is redirected to the index.php.
        header('Location: ../index.php');
    } else {
//Creation of the message of success deleting the receta.
        $_SESSION['message'] = 'Receta eliminada!';
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

$sql = "SELECT count(*) as total FROM recipeinfo WHERE unit = '$unitname';";
$row = $conn -> query($sql) -> fetch_assoc();
$total = $row['total']; 

$sql = "SELECT count(*) as total FROM reholder WHERE unit = '$unitname';";
$row = $conn -> query($sql) -> fetch_assoc();
$total = $total + $row['total']; 

    if($total == 0) {

    //Deleting the register with the name received.
    $sql = "DELETE FROM units WHERE unit = '$unitname';";

    $result = $conn -> query($sql);

    //If there's no record with that name, a message is sent.

        if($result !== true){
    //Creation of the message of error deleting the receta.
            $_SESSION['message'] = 'Error al eliminar la unidad!';
            $_SESSION['message_alert'] = "danger";

    //The page is redirected to the add_units.php
            header('Location: ../views/add_units.php');
        } else {
    //Creation of the message of success deleting the receta.
            $_SESSION['message'] = 'Unidad eliminada!';
            $_SESSION['message_alert'] = "success";

    //After the receta has been deleted, the page is redirected to the add_units.php.
            header('Location: ../views/add_units.php');
        }
    } else {
        //Creation of the message of error deleting the receta.
            $_SESSION['message'] = 'Esta unidad está siendo usada!';
            $_SESSION['message_alert'] = "danger";

    //The page is redirected to the add_units.php
            header('Location: ../views/add_units.php');
    }
}


/************************************************************************************************/
/***************************************RECIPE DELETION CODE*************************************/
/************************************************************************************************/

//Verifying that the id value comes with data.
if(isset($_GET['id'])){
    
//Getting the name.
$recipeid = $_GET['id'];

//Deleting the register with the name received.
$sql = "DELETE FROM reholder WHERE re_id = $recipeid;";

$result = $conn -> query($sql);

//If there's no record with that name, a message is sent.

    if(!$result){
//Creation of the message of error deleting the receta.
        $_SESSION['message'] = 'Error al eliminar ingrediente!';
        $_SESSION['message_alert'] = "danger";

//The page is redirected to the add_units.php
        header('Location: ../views/add_recipe.php');
    } else {
//Creation of the message of success deleting the receta.
        $_SESSION['message'] = 'Ingrediente eliminado!';
        $_SESSION['message_alert'] = "success";

//After the receta has been deleted, the page is redirected to the add_units.php.
        header('Location: ../views/add_recipe.php');
    }
}


/************************************************************************************************/
/***************************************INGREDIENT DELETION CODE*********************************/
/************************************************************************************************/


//Verifying that the ingredientname value comes with data.
if(isset($_GET['ingredientname'])){
    
//Getting the name.
$ingredientName = $_GET['ingredientname'];

$sql = "SELECT count(*) as total FROM recipeinfo WHERE ingredient = '$ingredientName';";
$row = $conn -> query($sql) -> fetch_assoc();
$total = $row['total']; 

$sql = "SELECT count(*) as total FROM reholder WHERE ingredient = '$ingredientName';";
$row = $conn -> query($sql) -> fetch_assoc();
$total = $total + $row['total']; 

$sql = "SELECT count(*) as total FROM ingholder WHERE ingredient = '$ingredientName';";
$row = $conn -> query($sql) -> fetch_assoc();
$total = $total + $row['total']; 

    if($total == 0) {

    $sql = "DELETE FROM ingredients WHERE ingredient = '$ingredientName';";

    $result = $conn -> query($sql);

    //If there's no record with that name, a message is sent.

        if(!$result){
        //Creation of the message of error deleting the receta.
            $_SESSION['message'] = 'Error al eliminar ingrediente!';
            $_SESSION['message_alert'] = "danger";

        //The page is redirected to the add_units.php
            header('Location: ../views/add_ingredients.php');
        } else {
        //Creation of the message of success deleting the receta.
            $_SESSION['message'] = 'Ingrediente eliminado!';
            $_SESSION['message_alert'] = "success";

        //After the receta has been deleted, the page is redirected to the add_units.php.
            header('Location: ../views/add_ingredients.php');
        }
    } else {
    //Creation of the message of error deleting the receta.
        $_SESSION['message'] = 'Este ingrediente está en uso!';
        $_SESSION['message_alert'] = "danger";

    //The page is redirected to the add_units.php
        header('Location: ../views/add_ingredients.php');

    }
}

//Exiting the connection to the database.
$conn -> close(); 

//We include the footer (jquery, bootstrap and popper scripts).
include("../modules/footer.php");
?>