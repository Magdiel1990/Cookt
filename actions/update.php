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
        $_SESSION['message'] = 'Elija la cantidad por favor!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add_recipe.php
        header('Location: edit.php');

    } else {
        $sql = "UPDATE reholder SET ingredient = '$ingredient', quantity = '$quantity', unit = '$unit' WHERE re_id = $id";
                
        if ($conn->query($sql) === TRUE) {
         //Message if the variable is null.
        $_SESSION['message'] = 'El ingrediente ha sido editado!';
        $_SESSION['message_alert'] = "success";
            
        //The page is redirected to the add_recipe.php
        header('Location: ../views/add_recipe.php');
        } else {
         //Message if the variable is null.
        $_SESSION['message'] = 'Error al editar ingrediente!';
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
$newRecipeName = $_POST["newRecipeName"];
$category = $_POST["category"];
$cookingTime = $_POST["cookingTime"];
$preparation = $_POST["preparation"];
$observation = $_POST["observation"];

$sql = "SELECT categoryid FROM categories WHERE category = '$category';";
$row = $conn -> query($sql) -> fetch_assoc();

$categoryId = $row['categoryid'];

    if($newRecipeName == "" || $cookingTime == "" || $preparation == ""){
    //Message if the variable is null.
        $_SESSION['message'] = 'Complete todos los campos!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add_recipe.php
        header("Location: edit.php?recipename=". $oldName);
    } else {
        if($cookingTime > 0){
            
            $sql = "UPDATE recipe SET recipename = '$newRecipeName' WHERE recipename = '$oldName';";
            $sql .= "UPDATE recipe SET preparation = '$preparation' WHERE recipename = '$oldName';";
            $sql .= "UPDATE recipe SET cookingtime = '$cookingTime' WHERE recipename = '$oldName';";
            $sql .= "UPDATE recipe SET observation = '$observation' WHERE recipename = '$oldName';";
            $sql .= "UPDATE recipe SET categoryid = '$categoryId' WHERE recipename = '$oldName';";
            
            if ($conn->multi_query($sql) === TRUE) {
            //Message if the variable is null.
            $_SESSION['message'] = 'Receta editada correctamente!';
            $_SESSION['message_alert'] = "success";
                
            //The page is redirected to the add_recipe.php
            header("Location: edit.php?recipename=". $newRecipeName);
            } else {
            //Message if the variable is null.
            $_SESSION['message'] = 'Error al editar receta!';
            $_SESSION['message_alert'] = "danger";

            //The page is redirected to the add_recipe.php
            header("Location: edit.php?recipename=". $oldName);
            }  
        } else {
            //Message if the variable is null.
            $_SESSION['message'] = 'Tiempo de cocción incorrecto!';
            $_SESSION['message_alert'] = "danger";
                
            //The page is redirected to the add_recipe.php
            header("Location: edit.php?recipename=". $oldName);
        }
    }
}
?>

<?php
 $conn->close();
?>