<?php
//Directory root
define("root", "/");

class DatabaseConnection {
//Database information
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
 
//Method to get the file in the directory    
    public function directoryFiles() {
        if(file_exists($this -> directory)) {
            $dir_handle = opendir($this -> directory);
            
            while(($file = readdir($dir_handle)) !== false) {
            $path = $this -> directory . $file;  
                if(is_file($path)) {
                $name = pathinfo($path, PATHINFO_FILENAME);                 
                    if($name == $this -> fileName) {
//File extension
                        return pathinfo($path, PATHINFO_EXTENSION);          
//Default extension
                    } else {
                        return null;
                    }
                }                  
            }
            closedir($dir_handle);
//The directory doesn't exist                 
        }
    }
//Directory size
    public function directorySize(){
        
        if(is_dir($this -> directory)) {
            $dir_handle = opendir($this -> directory);

            $sizeBytes = 0;
//Getting the total directory size in bytes
            while(($file = readdir($dir_handle)) !== false) {
            $path = $this -> directory . root . $file;
                if(is_file($path)) {
                    $sizeBytes += filesize($path);
                } 
            }
            closedir($dir_handle);
            
        } else {
            $sizeBytes = 0;
        }
//Size converted into MB, GB y TB
        if ($sizeBytes/1024/1024 < 1024) {
            $size = round ($sizeBytes/1024/1024, 2) . " MB";
        } else if ($sizeBytes/1024/1024/1024 < 1024) {
            $size = round ($sizeBytes/1024/1024/1024, 2) . " GB";
        } else {
            $size = round ($sizeBytes/1024/1024/1024/1024, 2) . " TB";
        }

        return $size;
    }
}

//Input sanitization
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
//Convert English abbreviation to Spanish month
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

//Title convertion depending the sex of the user
class TitleConvertor {
    public $sex;

    function __construct($sex){
        $this -> sex = $sex;    
    }
    
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
//Header of the pages
class PageHeaders {
    private $uri;

    function __construct($uri){
        $this -> uri = $uri;    
    }

    public function pageHeader(){
        $headerList = [
            root => "Home",    
            root . "login" => "Login",        
            root . "random" => "Aleatorio",
            root . "custom" => "Personalizado",
            root . "profile" => "Perfil",
            root . "units" => "Unidades",
            root . "ingredients" => "Ingredientes",
            root . "add-recipe" => "Agregar Recetas",
            root . "categories" => "Categorías",
            root . "user" => "Usuarios",
            root . "signup" => "Registrarse",
            root . "recovery" => "Recuperación",  
            root . "recipes" => "Recetas",
            root . "edit" => "Editar",
            root . "user-recipes" => "Datos Generales"
        ];

        switch ($this -> uri) {
            case array_key_exists($this -> uri, $headerList) === true: 
                return $headerList [$this -> uri];
                break;
            case stripos($this -> uri, root. "recipes") !== false:
                return "Recetas";
                break;
            case stripos($this -> uri, root. "edit") !== false:
                return "Editar";
                break;
            case stripos($this -> uri, root. "user-recipes") !== false:
                return "Datos Generales";
                break;
            default: 
                return "Error";
        }
    }
}
//List of ingredients
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

//Result of user ingredients except the ones already added for the recipe
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

//Url existance verification
class UrlVerification {
    public $url;

    function __construct($url){
        $this -> url = $url;
    }

    public function urlVerif() {
//Opening the URL in read mode
        $id = @fopen($this -> url,"r");
//Verification
        if ($id) $open = true;
        else $open = false;
//Return de value
        return $open;
//Exiting the file
        fclose($id);
    }        
}
?>