<?php
//Including the database connection.
require_once ("../config/db_Connection.php");


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
        $_SESSION['message'] = 'Ingrediente completo editado!';
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

        $conn->close();
    }
}
?>