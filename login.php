<?php
//Reviso el estado de la sesión.
session_name("Login");

//Inicio una nueva sesión.
session_start();

//Models.
require_once ("models/models.php");

//Including the database connection.
require_once ("config/db_Connection.php");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Verifico los datos del usuario.
    $sql = "SELECT * FROM users WHERE username = '$username' AND `state` = 1;";
    $result = $conn -> query($sql);
    $num_rows =  $result -> num_rows;    
  
    //Si el usuario existe verifico la contraseña.
    if ($num_rows > 0) {

        $row = $result -> fetch_assoc();
       
        if (password_verify($password, $row['password'])) {
             //When a new user logs in, the index page is always the first page to load.
            if($_SESSION['username'] != $row['username']) {
                unset($_SESSION['lastpage']);
            }

            if(!isset($_SESSION['lastpage'])){
                $_SESSION['lastpage'] = "/cookt/";
            }

            //Creo la cookie.        
            session_set_cookie_params(0, "/", $_SERVER["HTTP_HOST"], 0);
            //Declaro las variables de la sesión.
            $_SESSION['userid'] = $row['userid'];
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['lastname'] = $row['lastname'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['type'] = $row['type'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['state'] = $row['state'];  

            //Calcula la hora y fecha del momento en el que se crea la sesión.
            $_SESSION["last_access"] = date("Y-n-j H:i:s");               
            
            switch ($row['sex']){
                case "M": $_SESSION['title'] = "Sr. ";
                break;

                case "F": $_SESSION['title'] = "Sra. ";
                break;

                default: $_SESSION['title'] = "";
            }
            
            $sql = "INSERT INTO access (userid) VALUES (" . $_SESSION['userid'] . ");";
            
            $conn -> query($sql);

            header("Location: ". $_SESSION['lastpage']);
        } else {
            $_SESSION['message'] = "¡Usuario o contraseña incorrectos!";
            $_SESSION['message_alert'] = "danger";
        }
    } else {
        $_SESSION['message'] = "¡Usuario o contraseña incorrectos!";
        $_SESSION['message_alert'] = "danger";
    }
} 

?>
<!DOCTYPE html>
<html lang="es" data-lt-installed="true">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Magdiel Castillo Mills">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="Keywords" content="receta, recipe, cocina, kitchen, sugerencias, recommendations">
    <meta name="ltm:project" content="recetaspersonalizadas">
    <meta property="og:type" content="website">
    <!--<meta name="ltm:domain" content="recipes23.com">-->
    <meta name="description" content="Encuentra la receta de cocina fácil que estás buscando personalizadas de acuerdo a los ingredientes que tengas en tu casa.">
    <title>Recipes23</title> <!-- It depends where I am in the site.-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="shortcut icon" href="/Cookt/imgs/logo/logo.png">
    <link rel="stylesheet" href="/Cookt/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@600;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/65a5e79025.js" crossorigin="anonymous"></script>
    <script src="/Cookt/js/scripts.js"></script>    
</head>
<body>
<main class="row justify-content-center p-5" id="loginBackground">

<?php
//Messages that are shown in the add_units page
    if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();          

//Unsetting the messages variables so the message fades after refreshing the page.
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }
?>  
    
    <section class="my-4">
        <div class="container-fluid h-custom my-4">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="/Cookt/imgs/login/Picture.png" class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1 mt-sm-4">
                    <form action="/cookt/login" method="POST">
                        <!-- Email input -->
                        <div class="form-outline mb-3">
                            <input type="text" id="username" class="form-control form-control-lg" name="username" autocomplete="off"/>
                            <label class="form-label" for="username">Usuario</label>
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-3">
                            <input type="password" id="password" class="form-control form-control-lg" name="password"  autocomplete="off"/>
                            <label class="form-label" for="password">Contraseña</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/cookt/recovery" class="text-body" style = "text-decoration: none;">¿Olvidaste la contraseña?</a>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <input type="submit" name="Login" value="Login" class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">
                            <p class="small fw-bold mt-2 pt-1 mb-0">¿No tienes cuenta? <a href="/cookt/signup"
                                style = "text-decoration: none;" class="link-danger">Regístrate</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
    </section>
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("views/partials/footer.php");
?>