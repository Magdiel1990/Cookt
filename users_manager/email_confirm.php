<?php
session_name("signup");

session_start();

//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

if(isset($_GET["code"])){
    $confirm_code = $_GET["code"];

    if(strlen($confirm_code) != 32) {
        header('Location: ' . root . 'not-found');
        exit;
    } else {
        $sql = "SELECT userid FROM users WHERE email_code = ?;";        
        $stmt = $conn -> prepare($sql); 
        $stmt->bind_param("s", $confirm_code);
        $stmt->execute();

        $result = $stmt -> get_result(); 
        $row = $result -> fetch_assoc();
        $id = $row["userid"];

        $num_rows  = $result -> num_rows;
        if($num_rows != 0) {
            $sql = "UPDATE users SET email_code = null WHERE userid = '$id';";
            if($conn -> query($sql)){ 
                $_SESSION['message'] = '¡Correo verificado correctamente!';
                $_SESSION['message_alert'] = "success";

                header('Location: ' . root . 'login');
                exit;
            }
        } else {
            header('Location: ' . root . 'not-found');
            exit;
        }
    } 
}
?>
