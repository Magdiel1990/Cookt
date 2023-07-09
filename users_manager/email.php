<?php
//Session
session_name("recovery");

session_start();

//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

if(isset($_POST['email'])){    

    if($_POST['email'] != ""){
//Sanitize        
    $filter = new Filter ($_POST['email'], FILTER_SANITIZE_EMAIL, $conn);
    $email = $filter -> sanitization();
//Email existance    
    $sql = "SELECT userid, email, sex, firstname, lastname FROM users WHERE email = ?;";

    $stmt = $conn -> prepare($sql); 
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt -> get_result(); 
    $num_rows = $result -> num_rows;
    
        if ($num_rows != 0) {
            $uniqcode = md5(uniqid(mt_rand()));
//Link to reset password
            $resetPassLink = "www.recipeholder.net". root ."reset-password?r_code=". $uniqcode;

            $row = $result -> fetch_assoc();
            $sex = $row ["sex"];
            $email = $row ["email"];
            $fullname = $row ["firstname"] . " " . $row ["lastname"];
            $userid = $row ["userid"];
            
//Delete the old codes
            $sql = "SELECT userid FROM recovery WHERE userid = ?;";

            $stmt = $conn -> prepare($sql); 
            $stmt->bind_param("i", $userid);
            $stmt->execute();

            $result = $stmt -> get_result(); 
            $num_rows = $result -> num_rows;

            if($num_rows > 0) {
                $sql = "DELETE FROM recovery WHERE userid = '$userid';";
                $conn -> query($sql);             
            }

//User title
            switch($sex)
            {
                case "M":
                    $title = "Querido";
                    break;
                case "F":
                    $title = "Querida";
                    break;
                default:
                    $title = "Queride";
            }
//Message
            $subject = "Reestablecimiento de Contraseña";
            $message = $title . " <b>" . $fullname . ".</b>"; 
            $message .= "<p>Hemos recibido un pedido de cambio de contraseña de tu cuenta. Si has sido tú, haz click en el siguiente enlace.</p>";
            $message .= "<a href='" . $resetPassLink . "'>" . $resetPassLink . "</a>";
            $message .= "<p>Si no necesitas cambiar la contraseña, o no has sido quien lo ha solicitado, simplemente ignora este mensaje.</p>";
            //set content-type header for sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            //additionals
            $headers .= "From: " .  $_SERVER['HTTP_REFERER'] . "\r\n" .
            "CC: magdielmagdiel01@gmail.com";
//Send email
            if (mail($email, $subject, $message, $headers) !== false) {
            $stmt = $conn -> prepare("INSERT INTO recovery (userid, forgot_pass_identity) VALUES (?, ?);");
            $stmt->bind_param ("is", $userid, $uniqcode);
            $stmt -> execute();
            $stmt -> close(); 

            //Message if the variable is null.
            $_SESSION['message'] = "¡Revisa tu correo, un mensaje ha sido enviado!";
            $_SESSION['alert'] = "success";

            //The page is redirected to the add-recipe.php
            header('Location: ' . root . 'recovery');
            } else {
            //Message if the variable is null.
            $_SESSION['message'] = "¡Error al enviar mensaje!";
            $_SESSION['alert'] = "danger";

            //The page is redirected to the add-recipe.php
            header('Location: ' . root . 'recovery');
            }            
        } else {
        //Message if the variable is null.
        $_SESSION['message'] = "¡Este correo no está registrado!";
        $_SESSION['alert'] = "danger";

        //The page is redirected to the add-recipe.php
        header('Location: ' . root . 'recovery');
        }
    } else {
        //Message if the variable is null.
        $_SESSION['message'] = "¡Escribe tu correo!";
        $_SESSION['alert'] = "danger";

        //The page is redirected to the add-recipe.php
        header('Location: ' . root . 'recovery');
    }
}
$conn -> close();
?>