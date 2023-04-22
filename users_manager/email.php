<?php
session_name("recovery");

session_start();
//Including the database connection.
require_once ("config/db_Connection.php");

if(isset($_POST['email'])){
    $email = $_POST['email'];

    if(!empty($email)){
    
    $sql = "SELECT email, sex, firstname, lastname FROM users WHERE email = '$email';";
    $result = $conn -> query($sql);
    $num_rows = $result -> num_rows;
    
        if ($num_rows != 0) {
            $uniqcode = md5(uniqid(mt_rand()));
            //Modificar este enlace por el del servidor
            $resetPassLink = "/reset-password?r_code=". $uniqcode;

            $row = $result -> fetch_assoc();
            $sex = $row ["sex"];
            $email = $row ["email"];
            $fullname = $row ["firstname"] . " " . $row ["lastname"];

            switch($sex)
            {
                case "M":
                    $title = "Querido";
                    break;
                default:
                    $title = "Querida";
            }

            $subject = "Reestablecimiento de Contraseña";
            $message = $title . " " . $fullname . "."; 
                "<br/>Hemos recibido un pedido de cambio de contraseña de tu cuenta. Si has sido tú, haz click en el siguiente enlace.
                <br/><a href='".$resetPassLink."'>".$resetPassLink."</a>
                <br/>Si no necesitas cambiar la contraseña, o no has sido quien lo ha solicitado, simplemente ignora este mensaje.";
            //set content-type header for sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            //additionals
            $headers .= "From: " .  $_SERVER['HTTP_REFERER'] . "\r\n" .
            "CC: magdielmagdiel01@gmail.com";

            //send email



            
          /*******************************phpmailer */




            if(mail($email,$subject,$message,$headers) !== false) {
            //Message if the variable is null.
            $_SESSION['message'] = "¡Revisa tu correo, un mensaje ha sido enviado!";
            $_SESSION['alert'] = "success";

            //The page is redirected to the add-recipe.php
            header('Location: /recovery');
            }    

        } else {
        //Message if the variable is null.
        $_SESSION['message'] = "¡Este correo no está registrado!";
        $_SESSION['alert'] = "danger";

        //The page is redirected to the add-recipe.php
        header('Location: /recovery');
        }
    } else {
        //Message if the variable is null.
        $_SESSION['message'] = "¡Escribe tu correo!";
        $_SESSION['alert'] = "danger";

        //The page is redirected to the add-recipe.php
        header('Location: /recovery');
    }
}

$conn -> close();
?>