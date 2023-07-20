
<?php
/************************************************************************************************/
/***************************************PASSWORD RECOVERY CODE***********************************/
/************************************************************************************************/

//Verify that data comes
/*if(empty($_POST) || empty($_GET)) {
    header('Location: ' . root);
    exit;  
}*/

//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

//Set the session name
session_name("Login");

//Initializing session
session_start();

$_SESSION['location'] = root;

if(isset($_POST['userpassword']) && isset($_POST['passrepeat']) && isset($_GET['id']) && isset($_GET['pass'])){

$filter = new Filter ($_POST['userpassword'], FILTER_SANITIZE_STRING, $conn);
$password = $filter -> sanitization();

$filter = new Filter ($_POST['passrepeat'], FILTER_SANITIZE_STRING, $conn);
$passrepeat = $filter -> sanitization();

$id = $_GET['id'];
$key = $_GET['pass'];

    if($password != "" && $passrepeat != ""){
        if (strlen($password) < 8 ||  strlen($password) > 50 || strlen($passrepeat) < 8 || strlen($passrepeat) > 50) {
            $_SESSION['message'] = '¡Cantidad de caracteres no aceptada!';
            $_SESSION['message_alert'] = "danger";

//The page is redirected to the recovery-page.php
            header('Location: ' . root . 'recovery-page?id=' . $id . '&pass=' . $key);
            exit;
        } else {    
            if ($password == $passrepeat){
                $hash_password = password_hash($password, PASSWORD_DEFAULT);          

                $sql = "UPDATE users SET password = '$hash_password' WHERE userid = '$id';";

                if ($conn->query($sql)) {
                    $sql = "DELETE FROM recovery WHERE userid = '$id';";               
                    if($conn -> query($sql)){
//Message if the variable is null.
                        $_SESSION['message'] = '¡Usuario editado correctamente!';
                        $_SESSION['message_alert'] = "success";

//The page is redirected to the edit.php
                        header('Location: ' . root . 'login');
                        exit;
                    } else {
                    $_SESSION['message'] = '¡Error al editar contraseña!';
                    $_SESSION['message_alert'] = "danger";

//The page is redirected to the recovery-page.php
                    header('Location: ' . root . 'recovery-page?id=' . $id . '&pass=' . $key);
                    exit;

                    }
                } else {
                    $_SESSION['message'] = '¡Error al editar contraseña!';
                    $_SESSION['message_alert'] = "danger";

//The page is redirected to the recovery-page.php
                    header('Location: ' . root . 'recovery-page?id=' . $id . '&pass=' . $key);
                    exit;
                }
            } else {
                $_SESSION['message'] = '¡Contraseñas no coinciden!';
                $_SESSION['message_alert'] = "danger";

//The page is redirected to the recovery-page.php
                header('Location: ' . root . 'recovery-page?id=' . $id . '&pass=' . $key);
                exit;
            } 
        }        
    } else {
        $_SESSION['message'] = '¡Complete todos los campos!';
        $_SESSION['message_alert'] = "danger";

//The page is redirected to the recovery-page.php
        header('Location: ' . root . 'recovery-page?id=' . $id . '&pass=' . $key);
        exit;
    }
}
$conn->close();
?>