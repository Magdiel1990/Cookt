
<?php
/************************************************************************************************/
/***************************************PASSWORD RECOVERY CODE***********************************/
/************************************************************************************************/

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

$filter = new Filter ($_POST['userpassword'], FILTER_SANITIZE_STRING);
$password = $filter -> sanitization();

$filter = new Filter ($_POST['passrepeat'], FILTER_SANITIZE_STRING);
$passrepeat = $filter -> sanitization();

$id = $_GET['id'];
$key = $_GET['pass'];


//Input validation object  
  $inputs = ["La contraseña" => [$password, [8,50], "incorrecta", false], 
  "La contraseña" => [$passrepeat, [8,50], "incorrecta", false]];

  $message = new InputValidation ($inputs, "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]/");  
  $message = $message -> lengthValidation();

    if(count($message) > 0) {
      $_SESSION['message'] = $message [0];
      $_SESSION['message_alert'] = $message [1];          
//The page is redirected to the recovery-page.php
        header('Location: ' . root . 'recovery-page?id=' . $id . '&pass=' . $key);
        exit;
    } 

    if ($password == $passrepeat){
        $hash_password = password_hash($password, PASSWORD_DEFAULT);          

        $result = $conn -> query("UPDATE users SET password = '$hash_password' WHERE userid = '$id';");  

        if ($result) {
            $result = $conn -> query("DELETE FROM recovery WHERE userid = '$id';");                              
            if($result){
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

//Exit connection
$conn->close();

//Verify that data comes
if(empty($_POST) || empty($_GET)) {
    header('Location: ' . root);
    exit;  
}
?>