<?php

/************************************************************************************************/
/******************************************USER RESET CODE***************************************/
/************************************************************************************************/

//Verifying that the id value comes with data.
if(isset($_GET['user_id']) && isset($_GET['reset'])) {
    
//Getting the name.
$userId = $_GET['user_id'];

$sql = "SELECT * FROM users WHERE userid = '$userId';";
$row = $conn -> query($sql) -> fetch_assoc();

$username = $row['username'];
$password = $row['password'];
$firstname = $row['firstname'];
$lastname = $row['lastname'];
$type = $row['type'];
$email = $row['email'];
$state = $row['state'];
$sex = $row['sex'];

$target_dir = "imgs/recipes/" . $username;

    if(file_exists($target_dir)) {
        unlink($target_dir);
    }
    
//Deleting the register with the name received.
$sql = "DELETE FROM users WHERE username = '$username';";

    if($conn -> query($sql)){
        $sql = "INSERT INTO users (userid, username, firstname, lastname, `password`, `type`, email, `state`, sex)
        VALUES ($userId, '$username', '$firstname', '$lastname', '$password', '$type', '$email', $state, '$sex');";

        if($conn -> query($sql) === TRUE){
            //Creation of the message of success deleting the receta.
            $_SESSION['message'] = '¡Usuario reseteado!';
            $_SESSION['message_alert'] = "success";

    //After the receta has been deleted, the page is redirected to the add_units.php.
            header("Location: /user");

        } else {
        //Creation of the message of error deleting the receta.
            $_SESSION['message'] = '¡Error al resetear usuario!';
            $_SESSION['message_alert'] = "danger";

    //The page is redirected to the add_units.php
            header("Location: /user");
        }
    }
}
?>