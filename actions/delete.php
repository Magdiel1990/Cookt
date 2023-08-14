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
$result = $conn -> query("SELECT recipeid FROM recipe WHERE recipename = '$recipeName' AND username = '" . $_SESSION['username'] . "';");
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
        $result = $conn -> query("UPDATE recipe SET state = 0 WHERE recipename = '$recipeName' AND username = '" . $_SESSION['username'] . "';");

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
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }

            $conn -> query("INSERT INTO recycle (name, type, username, elementid) VALUES ('$recipeName', 'Receta', '" . $_SESSION['username'] . "', '$id');");

            $_SESSION['message'] = '¡Receta eliminada!';
            $_SESSION['message_alert'] = "success";

//After the recipe has been deleted, the page is redirected to the index.php.
            header('Location: ' . root);
            exit;
        }
    } else {
        $result = $conn -> query("DELETE FROM recipe WHERE recipename = '$recipeName' AND username = '" . $_SESSION['username'] . "';");

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
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
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
$result = $conn -> query("SELECT categoryid FROM categories WHERE category = '$categoryName';");
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

        $result = $conn -> query("UPDATE categories SET state = 0 WHERE category = '$categoryName';");

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
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }

            $conn -> query("INSERT INTO recycle (name, type, username, elementid) VALUES ('$categoryName', 'Categoría', '" . $_SESSION['username'] . "', '$id');");

            $_SESSION['message'] = '¡Categoría eliminada!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'categories');
            exit;
        }
    } else {
    //Deleting the register with the name received.

        $result = $conn -> query("DELETE FROM categories WHERE category = '$categoryName';");
        
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
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }

            $conn -> query("INSERT INTO recycle (name, type, username, elementid) VALUES ('$categoryName', 'Categoría', '" . $_SESSION['username'] . "', '$id');");

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
$result = $conn -> query("SELECT id FROM ingredients WHERE ingredient = '$ingredientName' AND username = '" . $_SESSION['username'] . "';");
$row = $result -> fetch_assoc();

$id = $row ["id"];
    if($_SESSION['recycle'] == 1) {
        $result = $conn -> query("UPDATE ingredients SET state = 0 WHERE ingredient = '$ingredientName' AND username = '" . $_SESSION['username'] . "';");

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
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }

            if($_SESSION['recycle'] == 1) {
                $conn -> query("INSERT INTO recycle (name, type, username, elementid) VALUES ('$ingredientName', 'Ingrediente', '" . $_SESSION['username'] . "', '$id');");
            }

            $_SESSION['message'] = '¡Ingrediente eliminado!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'ingredients');
            exit;
        }
    } else {
        $result = $conn -> query("DELETE FROM ingredients WHERE ingredient = '$ingredientName' AND username = '" . $_SESSION['username'] . "';");

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
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }

            if($_SESSION['recycle'] == 1) {
                $conn -> query("INSERT INTO recycle (name, type, username, elementid) VALUES ('$ingredientName', 'Ingrediente', '" . $_SESSION['username'] . "', '$id');");
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
$stmt = $conn -> prepare("SELECT id FROM ingredients WHERE ingredient = ? AND username = ? AND state = 1;"); 
$stmt->bind_param("ss", $customName, $_SESSION['username']);
$stmt->execute();

$result = $stmt -> get_result(); 
$row = $result -> fetch_assoc();   

$ingredientId = $row['id'];

//Delete ingredient
$result = $conn -> query("DELETE FROM ingholder WHERE ingredientid = '$ingredientId' AND username = '" . $_SESSION['username'] . "';");

    if(!$result){
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
$result = $conn -> query("SELECT userid FROM users WHERE type = 'Admin';");
$num_rows = $result -> num_rows;

//If there are more than 1 Admin users or the user to be deleted is not an Admins
    if($num_rows > 1 || $type != "Admin") {
//Username
    $stmt = $conn -> prepare("SELECT username FROM users WHERE userid = ?;"); 
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
        $result = $conn -> query("INSERT INTO users (email_code) VALUES ('$uniqcode');");
    
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
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
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
        $result = $conn -> query("SELECT date FROM `log` WHERE id = '$messageid';");
        if($result -> num_rows == 0){
            header('Location: ' . root . 'error404');
            exit;
        } else {
            $row = $result -> fetch_assoc();
            $date = $row["date"];
//Selecting the id of the share information
            $result = $conn -> query("SELECT recipeid FROM shares WHERE date = '$date' AND share_to = '" . $_SESSION["username"] . "';");
            if($result -> num_rows == 0){
               $result = $conn -> query("DELETE FROM `log` WHERE id = '$messageid';");
               if($result) {
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
        $result = $conn -> query("SELECT id FROM `log` WHERE id = '$messageid' AND username = '" . $_SESSION["username"] . "';");

        if($result -> num_rows > 0){
            $result = $conn -> query("DELETE FROM `log` WHERE id = '$messageid';");

            if($result) {
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
    $tables = ["recipe", "ingredients", "categories", "diet"];

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
        case "diet":
            $idName = "id";
            break;                
        default:
            $idName = "categoryid";
    }
    
    $result = $conn -> query("DELETE FROM $table WHERE $idName = '$id' AND state = 0;");

    if($result) {
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

/************************************************************************************************/
/*****************************************DIET DELETION CODE*************************************/
/************************************************************************************************/

if(isset($_GET['dietid']) && isset($_GET['username'])) {
    $dietid = $_GET['dietid'];
    $username = $_GET['username'];

    if($dietid == "" || $username == "") {
        header('Location: ' . root . 'error404');
        exit;
    }

    $result = $conn -> query("SELECT dietname FROM diet WHERE id = '$dietid' AND username = '$username';");

    if($result -> num_rows > 0) {
//Diet name
        $row = $result -> fetch_assoc();
        $dietname = $row['dietname'];

        $result = $conn -> query("UPDATE diet SET state = 0 WHERE id = '$dietid';");
        if($result) {
//Notification message        
            $log_message = "Has eliminado la dieta \"" . $dietname . "\".";       
            $type = "diet";
//Verify if notifications are on
            if($_SESSION['notification'] == 1) {
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }

            $conn -> query("INSERT INTO recycle (name, type, username, elementid) VALUES ('$dietname', 'dieta', '" . $_SESSION['username'] . "', '$dietid');");
            
            $_SESSION['message'] = '¡Dieta eliminada!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'diet');
            exit;
        }
    } else {
        $_SESSION['message'] = '¡Esta dieta no existe!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'diet');
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