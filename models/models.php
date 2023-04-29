<?php

class DatabaseConnection {
   /* static $hostname = "localhost";
    static $username = "u743896838_magdiel";
    static $password = ">Af=jh8E";
    static $database = "u743896838_foodbase";
*/
    static $hostname = "localhost:3306";
    static $username = "root";
    static $password = "123456";
    static $database = "foodbase";

    //Connection to the database.
    public static function dbConnection(){
        $conn = new mysqli(self::$hostname, self::$username, self::$password, self::$database);
        
        // Check connection
        if ($conn->connect_error) {
            die("Error en conexión: " . $conn->connect_error);
        }
        return $conn;
    }
}

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
    
        if(file_exists($this -> directory)) {
            $dir_handle = opendir($this -> directory);

            while(($file = readdir($dir_handle)) !== false) {
            $path = $this -> directory . '/' . $file;
                if(is_file($path)) {
                $name = pathinfo($path, PATHINFO_FILENAME);      
                    if($name == $this -> fileName) {
                        $ext = pathinfo($path, PATHINFO_EXTENSION); 
                    } 
                } else {
                    $ext = "unk";
                }
            }
            closedir($dir_handle);

            $imgDir = $this -> directory . $this -> fileName . "." . $ext;

            return $imgDir;
        } else {
            return false;
        }
    }


    //Directory size
    public function directorySize(){
        
        if(is_dir($this -> directory)) {
            $dir_handle = opendir($this -> directory);

            $sizeBytes = 0;

            while(($file = readdir($dir_handle)) !== false) {
            $path = $this -> directory . '/' . $file;
                if(is_file($path)) {
                    $sizeBytes += filesize($path);
                } 
            }
            closedir($dir_handle);
            
        } else {
            $sizeBytes = 0;
        }

        if($sizeBytes/1024 < 1024){
            $size = round ($sizeBytes/1024, 2) . " KB";
        } else if ($sizeBytes/1024/1024 < 1024) {
            $size = round ($sizeBytes/1024/1024, 2) . " MB";
        } else if ($sizeBytes/1024/1024/1024 < 1024) {
            $size = round ($sizeBytes/1024/1024/1024, 2) . " GB";
        } else {
            $size = round ($sizeBytes/1024/1024/1024/1024, 2) . " TB";
        }

        return $size;
    }

    public function directoryProfiles(){
    
        if(!file_exists($this -> directory)) {
            mkdir($this -> directory, 0777, true);
        }
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
}

class Filter {
public $input;
public $type; 
public $conn;

function __construct($input, $type, $conn){
    $this -> input = $input;
    $this -> type = $type;
    $this -> conn = $conn;
}

public function sanitization() {
    $this -> input = mysqli_real_escape_string($this -> conn, $this -> input);   
    $this -> input = htmlspecialchars($this -> input);
    $this -> input = filter_var($this -> input, $this -> type);
    $this -> input = trim($this -> input);
    $this -> input = stripslashes($this -> input);
    return $this -> input;
}
}

class TimeConvertor {
    public $abbr;

    function __construct($abbr){
        $this -> abbr = $abbr;    
    }
    
    //Function to convert to spanish units
    public function spanishmonth(){
        $monthList = [
        "Jan" => "Enero",           
        "Feb" => "Febrero",
        "Mar" => "Marzo",
        "Apr" => "Abril",
        "May" => "Mayo",
        "Jun" => "Junio",
        "Jul" => "Julio",
        "Aug" => "Agosto",
        "Sep" => "Septiembre",
        "Oct" => "Octubre",
        "Nov" => "Noviembre",
        "Dic" => "Diciembre"
        ];

        return $monthList [$this -> abbr];
    }
}

class TitleConvertor {
    public $sex;

    function __construct($sex){
        $this -> sex = $sex;    
    }
    
//Function to convert to spanish units
    public function title(){
        switch ($this -> sex){
            case "M": return "Sr. ";
            break;

            case "F": return "Sra. ";
            break;

            default: return "";
        }
    }
}

class PageHeaders {
    private $uri;

    function __construct($uri){
        $this -> uri = $uri;    
    }

    public function pageHeader(){
        $headerList = [
            "/" => "Home",    
            "/login" => "Login",        
            "/random" => "Aleatorio",
            "/custom" => "Personalizado",
            "/profile" => "Perfil",
            "/units" => "Unidades",
            "/ingredients" => "Ingredientes",
            "/add-recipe" => "Agregar Recetas",
            "/categories" => "Categorías",
            "/user" => "Usuarios",
            "/signup" => "Registrarse",
            "/recovery" => "Recuperación",  
            "/recipes" => "Recetas",
            "/edit" => "Editar",
            "/user-recipes" => "Datos Generales"
        ];

        switch ($this -> uri) {
            case array_key_exists($this -> uri, $headerList) === true: 
                return $headerList [$this -> uri];
                break;
            case stripos($this -> uri, "/recipes") !== false:
                return "Recetas";
                break;
            case stripos($this -> uri, "/edit") !== false:
                return "Editar";
                break;
            case stripos($this -> uri, "/user-recipes") !== false:
                return "Datos Generales";
                break;
            default: 
                return "Error";
        }
    }
}

class IngredientList {
    public $table1;
    public $table2;
    public $column;
    public $username;

    function __construct($table1,$table2,$column,$username){
        $this -> table1 = $table1;
        $this -> table2 = $table2;
        $this -> column = $column;
        $this -> username = $username;
    }
  
    //Results of ingredients for adding recipes
    protected function ingForRecipe(){

    $conn = DatabaseConnection::dbConnection();

    $sql = "SELECT " . $this -> table2 ."." . $this -> column . " FROM " . $this -> table1 . " JOIN " . $this -> table2 . " ON " . $this -> table2 . ".id = " . $this -> table1 . ".ingredientid WHERE " . $this -> table1 . ".username = '" . $this -> username . "';";
    $result = $conn -> query($sql);
    
    return $result;
    }

    public function ingForRecipeResult(){
        $result = $this -> ingForRecipe();
        return $result;
    }

    //Condition for selecting ingredients different from the one already added for adding recipes
    public function ingConditions() {
        
        $result = $this -> ingForRecipeResult();
        $num_rows = $result -> num_rows;        

        if ($num_rows == 0) {
            $where = "WHERE username = '" . $this -> username . "'";                                               
        } else {
            $where = "WHERE NOT ingredient IN (";

            while($row = $result -> fetch_assoc()) {
                $where .= "'" . $row["ingredient"] . "', ";
            }
            
            $where = substr_replace($where, "", -2);
            $where .= ") AND username = '" . $this -> username . "' ORDER BY " . $this -> column;                        
        }
        return $where;
    }  

    //result of user ingredients except the ones already added for the recipe
    public function ingResults() {

    $conn = DatabaseConnection::dbConnection();
    $where = $this -> ingConditions();

    $sql = "SELECT " . $this -> column . " FROM " . $this -> table2 . " " . $where;
    $result = $conn -> query($sql);

    return $result;
    }   
    
    //Quantity of user ingredients except the ones already added for the recipe
    public function ingQuantity() {
        $num_rows = $this -> ingResults() -> num_rows;
        return $num_rows;
    }

    public function ingredientOptions(){
        $result = $this -> ingResults();
        while($row = $result -> fetch_assoc()) {
            echo '<option value="' . $row["ingredient"] . '">' . ucfirst($row["ingredient"]) . '</option>';
        }
    }
}

class IngredientListChild extends IngredientList {
    public $table3;
    public $recipename;

    function __construct($table1, $table2, $table3, $column, $recipename, $username){
        parent::__construct($table1, $table2, $column, $username);      
        $this -> table3 = $table3;
        $this -> recipename = $recipename;        
    }

    //Results of ingredients for adding recipes
    public function ingForRecipe(){

    $conn = DatabaseConnection::dbConnection();

    $sql = "SELECT " . $this -> table2 . "." . $this -> column . " FROM " . $this -> table1 . " JOIN " . $this -> table3 . " ON " . $this -> table1 . ".recipeid = " . $this -> table3 . ".recipeid JOIN " . $this -> table2 . " ON " . $this -> table2 . ".id = " . $this -> table1 . ".ingredientid WHERE " . $this -> table3 . ".recipename = '" . $this -> recipename . "' AND " . $this -> table3 . ".username = '" . $this -> username . "';";
    $result = $conn -> query($sql);    
    return $result;
    }    
}

class Units {
    public $unit;

    function __construct($unit){
        $this -> unit = $unit;
    }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
    
    public function unitQuery(){
        $conn = DatabaseConnection::dbConnection();        
        $sql = "SELECT unit FROM units ORDER BY unit;";
        $result = $conn -> query($sql);
        return $result;
    }
    
    public function unitOptions(){
        $result = $this -> unitQuery();
        
        while($row = $result -> fetch_assoc()) {
            echo '<option value="' . $row["unit"] . '">' . $row["unit"] . '</option>';
        }
    }

    private function unitQuery2(){
        $conn = DatabaseConnection::dbConnection();   
        $sql = "SELECT unit FROM units WHERE unit = '" . $this -> unit . "';";
        $result = $conn -> query($sql);
        return $result;
    }

    public function unitCount(){
        $result = $this -> unitQuery();        
        $num_rows = $result -> num_rows;
        return $num_rows;
    } 

    public function unitCount2(){
        $result = $this -> unitQuery2();        
        $num_rows = $result -> num_rows;
        return $num_rows;
    }   
}

function Server(){
    echo "<pre>";
    var_dump($_SERVER);
    echo "</pre>";
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
    $sql = "SELECT userid, `type` FROM users WHERE username ='".$this -> sessionUser."';";
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