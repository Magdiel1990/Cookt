<?php
session_name("Login");

session_start();

unset ($_SESSION['location']);

//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

if(isset($_POST["email"])){

    $filter = new Filter ($_POST["email"], FILTER_SANITIZE_EMAIL);
    $email = $filter -> sanitization();

//Input validation object  
    $inputs = ["El correo electrónico" => [$email, [15,70], "incorrecto", false]]; 

    $message = new InputValidation ($inputs, "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]/");  
    $message = $message -> lengthValidation();

    if(count($message) > 0) {
        $_SESSION['message'] = $message [0];
        $_SESSION['message_alert'] = $message [1];          

        header('Location: ' . root . 'reactivate-account');
        exit;
    } 

    $stmt = $conn -> prepare("SELECT email_code FROM users WHERE email = ?;"); 
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt -> get_result(); 
    $num_rows  = $result -> num_rows;

    if($num_rows != 0) {
        $row = $result -> fetch_assoc();
        $email_code = $row ["email_code"];

        if($email_code == null) {
            $_SESSION['message'] = '¡Este correo ya está activado!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'reactivate-account');
            exit;

        } else {
//Confirmation link                            
            $confirmPassLink = "www.recipeholder.net". root ."email_confirm?code=". $email_code;
//Message
            $subject = "Reactivación de cuenta";                            
            $message = "<p>Has solicitado reactivar tu cuenta de recipeholder.net. Si has sido tú, haz click en el siguiente link.</p>";
            $message .= "<a href='" . $confirmPassLink . "'>" . $confirmPassLink . "</a>";                           
//set content-type header for sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
//additionals
            $headers .= "From: " .  $_SERVER['HTTP_REFERER'] . "\r\n" .
            "CC: magdielmagdiel01@gmail.com";
            
//Send email
            if (mail($email, $subject, $message, $headers)) {
                $_SESSION['message'] = '¡Un mensaje ha sido enviado a tu correo para reactivar la cuenta!';
                $_SESSION['message_alert'] = "success";

                header('Location: ' . root . 'reactivate-account');
                exit;
            } else {
                $_SESSION['message'] = '¡Error al reactivar cuenta!';
                $_SESSION['message_alert'] = "danger";

                header('Location: ' . root . 'reactivate-account');
                exit;
            }
        }
    } else {
        $_SESSION['message'] = '¡Este correo no está registrado!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'reactivate-account');
        exit;
    }
}

$conn -> close();  

//Verify that data comes
if(empty($_POST)) {
    header('Location: ' . root);
    exit;  
}
?>
