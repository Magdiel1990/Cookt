<?php
//Head
require_once ("views/partials/head.php");

/************************************************************************************************/
/******************************************USER RESET CODE***************************************/
/************************************************************************************************/

if(isset($_GET['user_id']) && isset($_GET['reset'])) {

$userId = $_GET['user_id'];
//User data
$stmt = $conn -> prepare("SELECT * FROM users WHERE userid = ?;"); 
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt -> get_result(); 
$row = $result -> fetch_assoc();   

$username = $row['username'];
$password = $row['password'];
$firstname = $row['firstname'];
$lastname = $row['lastname'];
$type = $row['type'];
$email = $row['email'];
$state = $row['state'];
$sex = $row['sex'];

//User image directory
$target_dir = "imgs/recipes/" . $username;
//Delete user image directory
    if(file_exists($target_dir)) {
        unlink($target_dir);
    }
    
//Deleting the user account
$result = $conn -> query("DELETE FROM users WHERE username = '$username';");

//Inserting the user data back
    if($result){
        $result = $conn -> query ("INSERT INTO users (userid, username, firstname, lastname, `password`, `type`, email, `state`, sex)
        VALUES ($userId, '$username', '$firstname', '$lastname', '$password', '$type', '$email', $state, '$sex');");
      
        if($result){
//Notification message        
            $log_message = "Has reiniciado el usuario \"" . $username . "\".";       
            $type = "reset";

            if($_SESSION['notification'] == 1) {
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }

            $_SESSION['message'] = '¡Usuario reseteado!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'user');
            exit;
        } else {
            $_SESSION['message'] = '¡Error al resetear usuario!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root . 'user');
            exit;
        }
    }
}

$conn -> close(); 

//Verify that data comes
if(empty($_POST) || empty($_GET)) {
    header('Location: ' . root);
    exit;  
}
?>