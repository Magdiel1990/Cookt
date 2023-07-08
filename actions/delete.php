<?php
//Head
require_once ("views/partials/head.php");

/************************************************************************************************/
/***************************************RECIPE DELETION CODE*************************************/
/************************************************************************************************/

if(isset($_GET['recipename'])){
    
//Getting the name.
$recipeName = $_GET['recipename'];

$sql = "DELETE FROM recipe WHERE recipename = '$recipeName' AND username = '" . $_SESSION['username'] . "';";
$result = $conn -> query($sql);

    if(!$result){
        $_SESSION['message'] = '¡Error al eliminar la receta!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root);
        exit;
    } else {
//Deleting recipe img       
        $target_dir = "imgs/recipes/". $_SESSION['username']. "/";

        $files = new Directories($target_dir, $recipeName);
        $ext = $files -> directoryFiles();

        if($ext !== null) {
            $imageDir = $target_dir . $recipeName . "." . $ext;

            unlink($imageDir);
        }  

        $_SESSION['message'] = '¡Receta eliminada!';
        $_SESSION['message_alert'] = "success";

//After the recipe has been deleted, the page is redirected to the index.php.
        header('Location: ' . root);
        exit;
    }
}

/************************************************************************************************/
/***************************************CATEGORY DELETION CODE***********************************/
/************************************************************************************************/

if(isset($_GET['categoryname'])){    
//Category.
$categoryName = $_GET['categoryname'];

//Category directory
$categoryDir = "imgs/categories/";

//Delete category image directory
$files = new Directories($categoryDir , $categoryName);
$categoryImgDir = $files -> directoryFiles();

    if($ext !== null) {
        $imageDir = $categoryDir . $categoryName . "." . $ext;

        unlink($imageDir);
    } 

//Deleting the register with the name received.
$sql = "DELETE FROM categories WHERE category = '$categoryName';";
$result = $conn -> query($sql);

    if($result !== true){
        $_SESSION['message'] = '¡Error al eliminar la categoría!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'categories');
        exit;
    } else {
        $_SESSION['message'] = '¡Categoría eliminada!';
        $_SESSION['message_alert'] = "success";

        header('Location: ' . root . 'categories');
        exit;
    }
} 

/************************************************************************************************/
/********************INGREDIENT DELETION WHEN ADDING THE RECIPE CODE*****************************/
/************************************************************************************************/

//Verifying that the id value comes with data.
if(isset($_GET['id'])){
    
//Getting the name.
$recipeid = $_GET['id'];

//Deleting recipe
$sql = "DELETE FROM reholder WHERE re_id = $recipeid AND username = '" . $_SESSION['username'] . "';";

$result = $conn -> query($sql);
    if(!$result){
        $_SESSION['message'] = '¡Error al eliminar ingrediente!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'add-recipe');
        exit;
    } else {
        $_SESSION['message'] = '¡Ingrediente eliminado!';
        $_SESSION['message_alert'] = "success";

        header('Location: ' . root . 'add-recipe');
        exit;
    }
}

/************************************************************************************************/
/***************************************INGREDIENT DELETION CODE*********************************/
/************************************************************************************************/

if(isset($_GET['ingredientname'])){
    
//Ingredient
$ingredientName = $_GET['ingredientname'];

$sql = "DELETE FROM ingredients WHERE ingredient = '$ingredientName' AND username = '" . $_SESSION['username'] . "';";
$result = $conn -> query($sql);

    if(!$result){
        $_SESSION['message'] = '¡Error al eliminar ingrediente!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'ingredients');
        exit;
    } else {
        $_SESSION['message'] = '¡Ingrediente eliminado!';
        $_SESSION['message_alert'] = "success";

        header('Location: ' . root . 'ingredients');
        exit;
    }
} 

/************************************************************************************************/
/***************************************CHOOSE BY INGREDIENT***********************************/
/************************************************************************************************/

if(isset($_GET['custom'])){
    
$customName = $_GET['custom'];
//Get the ingredient id of the custom page
$sql = "SELECT id FROM ingredients WHERE ingredient = ? AND username = ?;";
$stmt = $conn -> prepare($sql); 
$stmt->bind_param("ss", $customName, $_SESSION['username']);
$stmt->execute();

$result = $stmt -> get_result(); 
$row = $result -> fetch_assoc();   

$ingredientId = $row['id'];

//Delete ingredient
$sql = "DELETE FROM ingholder WHERE ingredientid = '$ingredientId' AND username = '" . $_SESSION['username'] . "';";

$result = $conn -> query($sql);

    if($result !== true){
        $_SESSION['message'] = '¡Error al eliminar el ingrediente!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'custom');
        exit;
    } else {
        $_SESSION['message'] = '¡Ingrediente eliminado!';
        $_SESSION['message_alert'] = "success";

        header('Location: ' . root . 'custom');
        exit;
    }
} 

/************************************************************************************************/
/***************************************USER DELETION CODE***************************************/
/************************************************************************************************/

if(isset($_GET['userid']) && isset($_GET['type'])) {
    
//Getting the id and type
$userId = $_GET['userid'];
$type = urldecode($_GET['type']);

//Verify how many Admin users there are
$sql = "SELECT userid FROM users WHERE type = 'Admin';";
$result = $conn -> query($sql);
$num_rows = $result -> num_rows;

//If there are more than 1 Admin users or the user to be deleted is not an Admins
    if($num_rows > 1 || $type != "Admin") {
//Username
    $sql = "SELECT username FROM users WHERE userid = ?;";
    $stmt = $conn -> prepare($sql); 
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt -> get_result(); 
    $row = $result -> fetch_assoc(); 

    $username = $row['username'];
//Delete user images directory
    $target_dir = "imgs/recipes/" . $username;
    
        if(file_exists($target_dir)) {
            unlink($target_dir);
        }
//Deleting the register with the id received.
        $sql = "DELETE FROM users WHERE userid = '$userId';";    
        $result = $conn -> query($sql);
    
        if(!$result){
            $_SESSION['message'] = '¡Error al eliminar usuario!';
            $_SESSION['message_alert'] = "danger";
    
            if($_SESSION["location"] == root . "profile") {
            header('Location: ' . root . 'profile'); 
            exit;   
            } else {
            header('Location: ' . root . 'user');
            exit;
            }
        } else {
            $_SESSION['message'] = '¡Usuario eliminado!';
            $_SESSION['message_alert'] = "success";
            
            if($_SESSION["location"] == root . "profile") {
                header('Location: ' . root . 'logout');  
                exit;  
            } else {
                header('Location: ' . root . 'user');
                exit;
            }
        }
    } else {        
        $_SESSION['message'] = '¡Este usuario no se puede eliminar!';
        $_SESSION['message_alert'] = "danger";

        header("Location: " . $_SESSION["location"]); 
        exit;   
    }
}

//Exiting db connection.
$conn -> close(); 

//Footer 
include("views/partials/footer.php");
?>