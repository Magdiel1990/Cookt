<?php
//Head
require_once ("views/partials/head.php");

/************************************************************************************************/
/***************************************RECIPE DELETION CODE*************************************/
/************************************************************************************************/

if(isset($_GET['recipename'])){
    
//Getting the name.
$recipeName = $_GET['recipename'];

//Getting the id
$sql = "SELECT recipeid FROM recipe WHERE recipename = '$recipeName' AND username = '" . $_SESSION['username'] . "';";
$result = $conn -> query($sql);
$row = $result -> fetch_assoc();

$id = $row ["recipeid"];

//Deleting recipe img       
$target_dir = "imgs/recipes/". $_SESSION['username']. "/";

$files = new Directories($target_dir, $recipeName);
$ext = $files -> directoryFiles();

if($ext !== null) {
    $imageDir = $target_dir . $recipeName . "." . $ext;
    unlink($imageDir);
}  

//Deleting
    if($_SESSION['recycle'] == 1) {
        $sql = "UPDATE recipe SET state = 0 WHERE recipename = '$recipeName' AND username = '" . $_SESSION['username'] . "';";
        $result = $conn -> query($sql);

        if(!$result){
            $_SESSION['message'] = '¡Error al eliminar la receta!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root);
            exit;
        } else {
//Notification message        
            $log_message = "Has eliminado la receta \"" . $recipeName . "\".";       
            $type = "delete";

            if($_SESSION['notification'] == 1) {
                $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
                $conn -> query($sql);
            }

            $sql = "INSERT INTO recycle (name, type, username, elementid) VALUES ('$recipeName', 'Receta', '" . $_SESSION['username'] . "', '$id');";
            $conn -> query($sql);

            $_SESSION['message'] = '¡Receta eliminada!';
            $_SESSION['message_alert'] = "success";

//After the recipe has been deleted, the page is redirected to the index.php.
            header('Location: ' . root);
            exit;
        }
    } else {
        $sql = "DELETE FROM recipe WHERE recipename = '$recipeName' AND username = '" . $_SESSION['username'] . "';";
        $result = $conn -> query($sql);

        if(!$result){
            $_SESSION['message'] = '¡Error al eliminar la receta!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root);
            exit;
        } else {
//Notification message        
            $log_message = "Has eliminado la receta \"" . $recipeName . "\".";       
            $type = "delete";

            if($_SESSION['notification'] == 1) {
                $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
                $conn -> query($sql);
            }

            $_SESSION['message'] = '¡Receta eliminada!';
            $_SESSION['message_alert'] = "success";

//After the recipe has been deleted, the page is redirected to the index.php.
            header('Location: ' . root);
            exit;
        }
    }

}

/************************************************************************************************/
/***************************************CATEGORY DELETION CODE***********************************/
/************************************************************************************************/

if(isset($_GET['categoryname'])){    
//Category.
$categoryName = $_GET['categoryname'];

//Getting the id
$sql = "SELECT categoryid FROM categories WHERE category = '$categoryName';";
$result = $conn -> query($sql);
$row = $result -> fetch_assoc();

$id = $row["categoryid"];

//Category directory
$categoryDir = "imgs/categories/";

//Delete category image directory
$files = new Directories($categoryDir , $categoryName);
$categoryImgDir = $files -> directoryFiles();

if($ext !== null) {
    $imageDir = $categoryDir . $categoryName . "." . $ext;

    unlink($imageDir);
} 

    if($_SESSION['recycle'] == 1) {
    //Deleting the register with the name received.
        $sql = "UPDATE categories SET state = 0 WHERE category = '$categoryName';";
        $result = $conn -> query($sql);

        if(!$result){
            $_SESSION['message'] = '¡Error al eliminar la categoría!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root . 'categories');
            exit;
        } else {
    //Notification message        
            $log_message = "Has eliminado la categoría \"" . $categoryName . "\".";       
            $type = "delete";

            if($_SESSION['notification'] == 1) {
                $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
                $result = $conn -> query($sql);
            }

            $sql = "INSERT INTO recycle (name, type, username, elementid) VALUES ('$categoryName', 'Categoría', '" . $_SESSION['username'] . "', '$id');";
            $result = $conn -> query($sql);


            $_SESSION['message'] = '¡Categoría eliminada!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'categories');
            exit;
        }
    } else {
    //Deleting the register with the name received.
        $sql = "DELETE FROM categories WHERE category = '$categoryName';";
        $result = $conn -> query($sql);
        
        if(!$result){
            $_SESSION['message'] = '¡Error al eliminar la categoría!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root . 'categories');
            exit;
        } else {
    //Notification message        
            $log_message = "Has eliminado la categoría \"" . $categoryName . "\".";       
            $type = "delete";

            if($_SESSION['notification'] == 1) {
                $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
                $result = $conn -> query($sql);
            }

            $sql = "INSERT INTO recycle (name, type, username, elementid) VALUES ('$categoryName', 'Categoría', '" . $_SESSION['username'] . "', '$id');";
            $result = $conn -> query($sql);

            $_SESSION['message'] = '¡Categoría eliminada!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'categories');
            exit;
        }
    } 
}
/************************************************************************************************/
/***************************************INGREDIENT DELETION CODE*********************************/
/************************************************************************************************/

if(isset($_GET['ingredientname'])){
    
//Ingredient
$ingredientName = $_GET['ingredientname'];

//Getting the id
$sql = "SELECT id FROM ingredients WHERE ingredient = '$ingredientName' AND username = '" . $_SESSION['username'] . "';";
$result = $conn -> query($sql);
$row = $result -> fetch_assoc();

$id = $row ["id"];
    if($_SESSION['recycle'] == 1) {
        $sql = "UPDATE ingredients SET state = 0 WHERE ingredient = '$ingredientName' AND username = '" . $_SESSION['username'] . "';";
        $result = $conn -> query($sql);

        if(!$result){
            $_SESSION['message'] = '¡Error al eliminar ingrediente!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root . 'ingredients');
            exit;
        } else {
    //Notification message        
            $log_message = "Has eliminado el ingrediente \"" . $ingredientName . "\".";       
            $type = "delete";

            if($_SESSION['notification'] == 1) {
                $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
                $conn -> query($sql);
            }

            if($_SESSION['recycle'] == 1) {
                $sql = "INSERT INTO recycle (name, type, username, elementid) VALUES ('$ingredientName', 'Ingrediente', '" . $_SESSION['username'] . "', '$id');";
                $conn -> query($sql);
            }

            $_SESSION['message'] = '¡Ingrediente eliminado!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'ingredients');
            exit;
        }
    } else {
        $sql = "DELETE FROM ingredients WHERE ingredient = '$ingredientName' AND username = '" . $_SESSION['username'] . "';";
        $result = $conn -> query($sql);

        if(!$result){
            $_SESSION['message'] = '¡Error al eliminar ingrediente!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root . 'ingredients');
            exit;
        } else {
    //Notification message        
            $log_message = "Has eliminado el ingrediente \"" . $ingredientName . "\".";       
            $type = "delete";

            if($_SESSION['notification'] == 1) {
                $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
                $conn -> query($sql);
            }

            if($_SESSION['recycle'] == 1) {
                $sql = "INSERT INTO recycle (name, type, username, elementid) VALUES ('$ingredientName', 'Ingrediente', '" . $_SESSION['username'] . "', '$id');";
                $conn -> query($sql);
            }

            $_SESSION['message'] = '¡Ingrediente eliminado!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'ingredients');
            exit;
        }
    }
} 

/************************************************************************************************/
/***************************************CHOOSE BY INGREDIENT***********************************/
/************************************************************************************************/

if(isset($_GET['custom']) && isset($_GET['uri'])){
    
$customName = $_GET['custom'];
$uri = $_GET['uri'];

//Get the ingredient id of the custom page
$sql = "SELECT id FROM ingredients WHERE ingredient = ? AND username = ? AND state = 1;";
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

        header('Location: ' . root . $uri);
        exit;
    } else {
        $_SESSION['message'] = '¡Ingrediente eliminado!';
        $_SESSION['message_alert'] = "success";

        header('Location: ' . root . $uri);
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

//Unique code for disabling the account        
        $uniqcode = md5(uniqid(mt_rand()));
//Inserting the code so the user can't get in
        $sql = "INSERT INTO users (email_code) VALUES ('$uniqcode');";
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
//Notification message        
            $log_message = "Has eliminado el usuario \"" . $username . "\".";       
            $type = "delete";

            if($_SESSION['notification'] == 1) {
                $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);";
                $conn -> query($sql);
            }
//Confirmation link                            
            $confirmPassLink = "www.recipeholder.net". root ."email_confirm?code=". $uniqcode;
//Message
            $subject = "Confirmación de desactivación de cuenta";                            
            $message = "<p>Has desactivado tu cuenta de recipeholder.net. Si deseas reactivarla en el futuro, haz click en el siguiente link.</p>";
            $message .= "<a href='" . $confirmPassLink . "'>" . $confirmPassLink . "</a>";                           
//set content-type header for sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
//additionals
            $headers .= "From: " .  $_SERVER['HTTP_REFERER'] . "\r\n" .
            "CC: magdielmagdiel01@gmail.com";
            
//Send email
            mail($email, $subject, $message, $headers);
    
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

/************************************************************************************************/
/***********************************NOTIFICATION DELETION CODE***********************************/
/************************************************************************************************/

if(isset($_GET['messageid']) && isset($_GET['type'])) {
    $messageid = $_GET['messageid'];
    $type = $_GET['type'];
//If it is a share notification
    if($type == "share") {
        $sql = "SELECT date FROM `log` WHERE id = '$messageid';";
        $result = $conn -> query($sql);
        if($result -> num_rows == 0){
            header('Location: ' . root . 'error404');
            exit;
        } else {
            $row = $result -> fetch_assoc();
            $date = $row["date"];
//Selecting the id of the share information
            $sql = "SELECT recipeid FROM shares WHERE date = '$date' AND share_to = '" . $_SESSION["username"] . "';";
            $result = $conn -> query($sql);
            if($result -> num_rows == 0){
               $sql = "DELETE FROM `log` WHERE id = '$messageid';";
               if($conn -> query($sql)) {
                    $_SESSION['message'] = '¡Notificación eliminada!';
                    $_SESSION['message_alert'] = "success";

//The page is redirected to the notifications
                    header('Location: ' . root . 'notifications');
                    exit;
               } else {
                    $_SESSION['message'] = '¡Error al eliminar notificación!';
                    $_SESSION['message_alert'] = "danger";

//The page is redirected to the notifications
                    header('Location: ' . root . 'notifications');
                    exit;
               }
//If the record is only in the share and not in the log               
            } else {
                $row = $result -> fetch_assoc();
                $sql = "DELETE FROM shares WHERE recipeid = '" . $row["recipeid"] . "';";
                $sql .= "DELETE FROM `log` WHERE id = '$messageid';";

                 if($conn -> multi_query($sql)) {
                    $_SESSION['message'] = '¡Notificación eliminada!';
                    $_SESSION['message_alert'] = "success";

//The page is redirected to the notifications
                    header('Location: ' . root . 'notifications');
                    exit;
                } else {
                    $_SESSION['message'] = '¡Error al eliminar notificación!';
                    $_SESSION['message_alert'] = "danger";

//The page is redirected to the notifications
                    header('Location: ' . root . 'notifications');
                    exit;
               }
            }
        }
    } else {
        $sql = "SELECT id FROM `log` WHERE id = '$messageid' AND username = '" . $_SESSION["username"] . "';";
        $result = $conn -> query($sql);

        if($result -> num_rows > 0){
            $sql = "DELETE FROM `log` WHERE id = '$messageid';";
            if($conn -> query($sql)) {
                $_SESSION['message'] = '¡Notificación eliminada!';
                $_SESSION['message_alert'] = "success";

//The page is redirected to the notifications
                header('Location: ' . root . 'notifications');
                exit;
            } else {
                $_SESSION['message'] = '¡Error al eliminar notificación!';
                $_SESSION['message_alert'] = "danger";

//The page is redirected to the notifications
                header('Location: ' . root . 'notifications');
                exit;
            }
//If the record is only in the share and not in the log               
        } else {
            header('Location: ' . root . 'error404');
            exit;
        }
    }
}

/************************************************************************************************/
/***********************************NOTIFICATION TOTAL DELETION CODE***********************************/
/************************************************************************************************/

if(isset($_GET['not_del'])) {
    if($_GET['not_del'] === base64_encode("yes")) {
        $sql = "DELETE FROM shares;";
        $sql .= "DELETE FROM `log`;";

        if($conn -> multi_query($sql)) {
            $_SESSION['message'] = '¡Todas las notificaciones eliminadas!';
            $_SESSION['message_alert'] = "success";

//The page is redirected to the notifications
            header('Location: ' . root . 'notifications');
            exit;
        } else {
            $_SESSION['message'] = '¡Error al eliminar las notificaciones!';
            $_SESSION['message_alert'] = "danger";

//The page is redirected to the notifications
            header('Location: ' . root . 'notifications');
            exit;
        }   
    } else {
        header('Location: ' . root . 'error404');
        exit;
    }   
}

/************************************************************************************************/
/***************************************RECYCLE DELETION CODE************************************/
/************************************************************************************************/

if(isset($_GET['id']) && isset($_GET['table'])) {
    $id = $_GET['id'];
    $table = $_GET['table'];

//Possible tables
    $tables = ["recipe", "ingredients", "categories"];

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
        default:
            $idName = "categoryid";
    }
    
    $sql = "DELETE FROM $table WHERE $idName = '$id' AND state = 0;";

    if($conn -> query($sql)) {
        $_SESSION['message'] = '¡Elemento eliminado exitosamente!';
        $_SESSION['message_alert'] = "success";

        header('Location: ' . root . 'recycle');
        exit;
    } else {
        $_SESSION['message'] = '¡Error al eliminar elemento!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'recycle');
        exit;
    }
}

/************************************************************************************************/
/***************************************EMPTY RECYCLE CODE************************************/
/************************************************************************************************/

if(isset($_GET['empty'])) {
    if($_GET['empty'] === base64_encode("yes")) {
        $sql = "DELETE FROM recipe WHERE state = 0 AND username = '" . $_SESSION['username'] . "';";
        $sql .= "DELETE FROM ingredients WHERE state = 0 AND username = '" . $_SESSION['username'] . "';";
        $sql .= "DELETE FROM categories WHERE state = 0;";

        if($conn -> multi_query($sql)) {
            $_SESSION['message'] = '¡Todos los elementos han sido eliminados!';
            $_SESSION['message_alert'] = "success";

//The page is redirected to the notifications
            header('Location: ' . root . 'recycle');
            exit;
        } else {
            $_SESSION['message'] = '¡Error al eliminar todos los elementos!';
            $_SESSION['message_alert'] = "danger";

//The page is redirected to the notifications
            header('Location: ' . root . 'recycle');
            exit;
        }   
    } else {
        header('Location: ' . root . 'error404');
        exit;
    }   
}

//Exiting db connection.
$conn -> close(); 

//Footer 
include("views/partials/footer.php");

//Verify that data comes
if(empty($_POST) || empty($_GET)) {
    header('Location: ' . root);
    exit;  
}
?>