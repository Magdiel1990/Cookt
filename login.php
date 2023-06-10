<?php
//Set the session name
session_name("Login");

//Initializing session
session_start();

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

//Check if username and password are set
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

//Check the data and if the user is active
    $sql = "SELECT * FROM users WHERE username = '$username' AND `state` = 1;";
    $result = $conn -> query($sql);
  
    if ($result -> num_rows > 0) {

        $row = $result -> fetch_assoc();
//Verify the password       
        if (password_verify($password, $row['password'])) {
            
//When a new user logs in, the index page is always the first page to load.
            if($_SESSION['username'] != $row['username']) {
                unset($_SESSION['location']);
            }

            if(!isset($_SESSION['location'])){
                $_SESSION['location'] = root;
            }

//Cookie creation      
            session_set_cookie_params(0, root, $_SERVER["HTTP_HOST"], 0);
            
//Session variables assignations
            $_SESSION['userid'] = $row['userid'];
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['lastname'] = $row['lastname'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['type'] = $row['type'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['state'] = $row['state'];

//Last login calculation.
            $_SESSION["last_access"] = date("Y-m-j H:i:s");        
            
//Object to determine the title of the user            
            $title = new TitleConvertor($row['sex']);
            $_SESSION['title'] = $title -> title(); 

//Check if the user has ever logged in
            $sql = "SELECT id FROM access WHERE userid=" . $_SESSION['userid'] . ";";  
            $result = $conn -> query($sql);

//Save the last access of the user      
            if($result -> num_rows == 0) {
                $sql = "INSERT INTO access (userid) VALUES (" . $_SESSION['userid'] . ");"; 
            } else {
                $sql = "UPDATE access SET lastlogin = DATE_FORMAT(now(), '%Y-%m-%d %h:%i:%s') WHERE userid = " . $_SESSION['userid'] . ";";           
            }            
                       
            $conn -> query($sql);
            
            header("Location: ". $_SESSION['location']);

           
        } else {
            $_SESSION['message'] = "¡Usuario o contraseña incorrectos!";
            $_SESSION['message_alert'] = "danger";
        }
    } else {
        $_SESSION['message'] = "¡Usuario o contraseña incorrectos!";
        $_SESSION['message_alert'] = "danger";
    }
} 

//Title of the page
$header = new PageHeaders($_SERVER["REQUEST_URI"]);
$header = $header -> pageHeader();

?>
<!DOCTYPE html>
<html lang="es" data-lt-installed="true">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Magdiel Castillo Mills">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="Keywords" content="receta, recipe, cocina, kitchen, sugerencias, recommendations">
    <meta name="ltm:project" content="recetas personalizadas">
    <meta property="og:type" content="website">
    <meta name="ltm:domain" content="recipeholder.net">
    <meta name="description" content="Encuentra la receta de cocina fácil que estás buscando personalizadas de acuerdo a los ingredientes que tengas en tu casa.">
    <title><?php echo $header;?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="shortcut icon" href="imgs/logo/logo2.png">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@600;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/65a5e79025.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>   
</head>
<body>
<main class="row justify-content-center p-5" id="loginBackground">
    
<?php
//Messages 
    if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();          

//Unsetting the messages
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }
?>  
    
    <section class="my-4">
        <div class="container-fluid h-custom my-4">
            <div class="row d-flex justify-content-center align-items-center">
<!-- Login image-->
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="imgs/login/Picture.png" class="img-fluid img-thumbnail" alt="Sample image">
                </div>
<!-- Login form -->        
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1 mt-4">
                    <form action="<?php echo root;?>login" method="POST">
<!-- Email input -->
                        <div class="form-outline mb-3">
                            <input type="text" id="username" class="form-control form-control-lg" name="username" autocomplete="off"/>
                            <label class="form-label" for="username">Usuario</label>
                        </div>
<!-- Password input -->
                        <div class="input-group mb-3">
                            <input type="password" id="password" class="form-control form-control-lg" name="password"  autocomplete="off"/>        
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary btn-lg" type="button">
                                    <i class="fa-solid fa-eye"></i>                                    
                                </button>        
                            </div>                                                      
                        </div>
                        <label class="form-label" for="password">Contraseña</label>  
<!-- Script to show and hide the password when clicking the button -->
                        <script>     
                            showpass();

                            function showpass(){
                                var button = document.getElementsByClassName("btn-outline-secondary")[0];
                                
                                button.addEventListener("click", function(){
                                    var tipo = document.getElementById("password");
                                    if(tipo.type == "password"){
                                        tipo.type = "text"; 
                                    } else {
                                        tipo.type = "password";                                                     
                                    }
                                })                               
                            }
                        </script>
<!-- Password recovery link -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?php echo root;?>recovery" class="text-body" style = "text-decoration: none;">¿Olvidaste la contraseña?</a>
                        </div>
<!-- Submittion -->
                        <div class="text-center text-lg-start mt-4 pt-2">
                            <input type="submit" name="Login" value="Login" class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">
                            <p class="small fw-bold mt-2 pt-1 mb-0">¿No tienes cuenta? <a href="<?php echo root;?>signup"
                                style = "text-decoration: none;" class="link-danger">Regístrate</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
    </section>
</main>
<?php
//Close connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>