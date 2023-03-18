<?php
//Reviso el estado de la sesión.
session_name("Login");

//Inicio una nueva sesión.
session_start();

//Including the database connection.
require_once ("../config/db_Connection.php");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Verifico los datos del usuario.
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password';";
    $result = $conn -> query($sql);
    $num_rows =  $result -> num_rows;    
  
    //Si el usuario existe verifico la contraseña.
    if ($num_rows > 0) {

        $row = $result -> fetch_assoc();
       
        if ($password == $row['password'] && $row['state'] = 1) {
             //When a new user logs in, the index page is always the first page to load.
            if($_SESSION['username'] != $row['username']) {
                unset($_SESSION['lastpage']);
            }

            if(!isset($_SESSION['lastpage'])){
                $_SESSION['lastpage'] = "/Cookt/index.php";
            }

            //Creo la cookie.        
            session_set_cookie_params(0, "/", $_SERVER["HTTP_HOST"], 0);
            //Declaro las variables de la sesión.
            $_SESSION["login"] = "yes";
            //Calcula la hora y fecha del momento en el que se crea la sesión.
            $_SESSION["last_access"] = date("Y-n-j H:i:s");
            $_SESSION['userid'] = $row['userid'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['type'] = $row['type'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['state'] = $row['state'];       

            header("Location: ". $_SESSION['lastpage']);
        }
    } else {
        $_SESSION['message'] = "¡Usuario o contraseña incorrectos!";
        $_SESSION['message_alert'] = "danger";

        header("Location: /Cookt/login.php");
    }
} 
$conn -> close();
?>