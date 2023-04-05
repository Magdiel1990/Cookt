<?php

class Messages {
    public $message;
    public $message_alert;

    function __construct($message, $message_alert){
        $this -> message = $message;
        $this -> message_alert = $message_alert;
    }    

    //Method for the button message.
    public function buttonMessage() {
        if(isset($this -> message_alert)){
            $html = "<div class='row justify-content-center'>";
            $html .= "<div class='col-auto alert alert-" . $this -> message_alert . " alert-dismissible fade show' role='alert'>";
            $html .= "<button type='button' class='close border-0' data-dismiss='alert' aria-label='Close'>";
            $html .= "<span>" . $this -> message . "</span>";        
            $html .= "</button>";
            $html .= "</div>"; 
            $html .= "</div>";   
            return $html;             
        }
    }

    //Method for the text message.
    public function textMessage() {
        if(isset($this -> message_alert)){
            $html = "<p class='pb-2 mb-0 pb-0 text-" . $this -> message_alert . " small'>";
            $html .= $this -> message;
            $html .= "</p>"; 
            return $html;             
        }
    }    
}


class Directories {
    public $directory;
    public $fileName;

    function __construct($directory, $fileName){
        $this -> directory = $directory;
        $this -> fileName = $fileName;
    }
    
    public function directoryFiles(){

    $dir_handle = opendir($this -> directory);

    while(($file = readdir($dir_handle)) !== false) {
    $path = $this -> directory . '/' . $file;
        if(is_file($path)) {
        $name = pathinfo($path, PATHINFO_FILENAME);      
            if($name == $this -> fileName) {
            $ext = pathinfo($path, PATHINFO_EXTENSION); 
            } 
        } else {
            $ext = "";
        }
    }
    closedir($dir_handle);

    $imgDir = $this -> directory . $this -> fileName . "." . $ext;

    return $imgDir;
    }


    //Directory size
    public function directorySize(){
        
    if(is_dir($this -> directory)) {
        $dir_handle = opendir($this -> directory);

        $size = 0;

        while(($file = readdir($dir_handle)) !== false) {
        $path = $this -> directory . '/' . $file;
            if(is_file($path)) {
                $size += filesize($path);
            } 
        }
        closedir($dir_handle);

        $sizeMegabites = round($size/1048576, 2);
    } else {
        $sizeMegabites = 0;
    }

    return $sizeMegabites;
    }
}

function sanitization($input, $type, $conn) {
    $input = mysqli_real_escape_string($conn, $input);   
    $input = htmlspecialchars($input);
    $input = filter_var($input, $type);
    $input = trim($input);
    $input = stripslashes($input);
    return $input;
}

//Function to convert to spanish months
function spanishMonth ($month){
    switch($month){
        case "Jan": return "Enero";
        break;
        case "Feb": return "Febrero";
        break;
        case "Mar": return "Marzo";
        break;
        case "Apr": return "Abril";
        break;
        case "May": return "Mayo";
        break;
        case "Jun": return "Junio";
        break;
        case "Jul": return "Julio";
        break;
        case "Aug": return "Agosto";
        break;
        case "Sep": return "Septiembre";
        break;
        case "Oct": return "Octubre";
        break;
        case "Nov": return "Noviembre";
        break;
        default: return "Diciembre";
    }
}


/*class User {
public $firstname;
public $lastname;
public $username;
public $password;
public $passrepeat;
public $sex;
public $email;
public $terms;
public $rol;
public $state;
public $sessionUser;
public $url;
public $conn;

function __construct ($firstname, $lastname, $username, $password, $passrepeat, $sex, $email, $terms, $rol, $state, $sessionUser, $url, $conn) {
    $this -> firstname = $firstname;
    $this -> lastname = $lastname;
    $this -> username = $username;
    $this -> password = $password;
    $this -> passrepeat = $passrepeat;
    $this -> sex = $sex;
    $this -> email = $email;
    $this -> terms = $terms;
    $this -> rol = $rol;
    $this -> state = $state;
    $this -> sessionUser =  $sessionUser;
    $this -> url = $url;
    $this -> conn = $conn;
}

public function sessionAdminVerif() {
    $sql = "SELECT userid, `type` FROM users WHERE username ='$this -> sessionUser';";
    $row = $this -> conn -> query($sql) -> fetch_assoc();
    $sessionUserType = $row['type'];

    if($sessionUserType != 'Admin') {
    //The page is redirected to the add-recipe.php
        header('Location: /Cookt/error/error.php');
    } 
}
public function newUser() {
    if ($this -> firstname == "" || $this -> lastname == "" || $this -> username == "" || $this -> password == ""  || $this -> passrepeat == "" || $this -> sex == "") {

        //Message if the variable is null.
        $_SESSION['message'] = '¡Complete o seleccione todos los campos por favor!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add-recipe.php
        header('Location: ' . $this -> url);
    } else {   
        if($this -> password != $this -> passrepeat) {
        //Message if the variable is null.
        $_SESSION['message'] = '¡Contraseñas no coinciden!';
        $_SESSION['message_alert'] = "danger";  
        
        //The page is redirected to the add-recipe.php
        header('Location: ' . $this -> url);
        } else {

            $hashed_password = password_hash($this -> password, PASSWORD_DEFAULT);

            if($this -> state == "yes") {
            $this -> state = 1;
            } else { 
            $this -> state = 0;
            }

            $sql = "SELECT userid FROM users WHERE firstname = '" . $this -> firstname . "' AND lastname = '" . $this -> lastname . "' AND username = '" . $this -> username . "' AND `password` = '$hashed_password';";

            $num_rows = $this -> conn -> query($sql) -> num_rows;

            if($num_rows == 0) {
            
            $stmt = $this -> conn -> prepare("INSERT INTO users (firstname, lastname, username, `password`, `type`, email, `state`, reportsto, sex) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");
            $stmt->bind_param ("ssssssiis", $this -> firstname, $this -> lastname, $this -> username, $hashed_password, $this -> rol, $this -> email, $this -> state, $this -> sessionUserId, $this -> sex);

            if ($stmt->execute()) {
            //Success message.
                $_SESSION['message'] = '¡Usuario agregado con éxito!';
                $_SESSION['message_alert'] = "success";

                $stmt->close();
                    
            //The page is redirected to the ingredients.php.
                header('Location: ' . $this -> url);

                } else {
            //Failure message.
                $_SESSION['message'] = '¡Error al agregar usuario!';
                $_SESSION['message_alert'] = "danger";
                    
            //The page is redirected to the ingredients.php.
                header('Location: ' . $this -> url);
            }
            } else {
            //Success message.
                $_SESSION['message'] = '¡Este usuario ya existe!';
                $_SESSION['message_alert'] = "success";
                    
            //The page is redirected to the ingredients.php.
                header('Location: ' . $this -> url);
            }
        }
    }
}
}*/
?>