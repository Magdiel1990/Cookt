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

echo $sql;

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
    $name = $_GET['unitname'];
    
    //Deleting the register with the name received.
    $sql = "DELETE FROM units WHERE unit = '$name';";
    
    echo $sql;
    
    $result = $conn -> multi_query($sql);
    
    //If there's no record with that name, a message is sent.
    
        if(!$result){
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
    }

//Exiting the connection to the database.
$conn -> close(); 

//We include the footer (jquery, bootstrap and popper scripts).
include("../modules/footer.php");
?>