<?php
if(isset($_GET["id"]) && isset($_GET["pass"])) {
//Models.
require_once ("models/models.php");

    if(strlen($_GET["pass"]) != 32) {
        header('Location: ' . root . 'not-found');
        exit;    
    }

session_name("recovery");

session_start();

//Including the database connection.
$conn = DatabaseConnection::dbConnection();
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
    <title>Recovery page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="shortcut icon" href="imgs/logo/logo2.png">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@600;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/65a5e79025.js" crossorigin="anonymous"></script>
    <script>
    //Avoid resubmission form  
    if (window.history.replaceState) { 
        window.history.replaceState(null, null, window.location.href);
    }
    </script>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body class="bg-primary">
    <main class="container p-4 my-3">
        <div class="row justify-content-center">
        <?php
    //Messages
            if(isset($_SESSION['message'])){
            $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
            echo $message -> buttonMessage();         

    //Unsetting the messages
            unset($_SESSION['message_alert'], $_SESSION['message']);
            }
        ?>  <div class="col-auto order-last my-4">
    <!--Form for adding the users-->
                <h2 class="text-center mb-3">Editar usuario</h2>
                <form method="POST" action="<?php echo root;?>password-change?id=<?php echo $_GET["id"];?>&pass=<?php echo $_GET["pass"];?>" id="user_form" class="text-center recovery-form">
                    <div class="input-group mb-3">
                        <label class="input-group-text is-required" for="userpassword">Nueva contraseña: </label>
                        <input class="form-control" type="password" id="userpassword" name="userpassword" minlength="8" maxlength="50" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary btn-lg" type="button">
                                <i class="fa-solid fa-eye"></i>                                    
                            </button>        
                        </div>  
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text is-required" for="passrepeat">Repetir contraseña: </label>
                        <input class="form-control" type="password" id="passrepeat" name="passrepeat" minlength="8" maxlength="50" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary btn-lg" type="button">
                                <i class="fa-solid fa-eye"></i>                                    
                            </button>        
                        </div>  
                    </div>
                    <div class="text-center">
                        <input type="submit" name="Recovery" value="Confirmar" class="btn btn-primary">              
                    </div>            
                </form>
                <script>
                    userValidation(); 
                    showpass();

// Script to show and hide the password when clicking the button
                    function showpass(){
                        var fButton = document.getElementsByClassName("btn-outline-secondary")[0];
                        var sButton = document.getElementsByClassName("btn-outline-secondary")[1];
                        
                        fButton.addEventListener("click", function(){
                            var tipo = document.getElementById("userpassword");
                            if(tipo.type == "password"){
                                tipo.type = "text"; 
                            } else {
                                tipo.type = "password";                                                     
                            }
                        });
                        
                        sButton.addEventListener("click", function(){
                            var tipo = document.getElementById("passrepeat");
                            if(tipo.type == "password"){
                                tipo.type = "text"; 
                            } else {
                                tipo.type = "password";                                                     
                            }
                        });
                    }

    //Form validation
                    function userValidation(){
                    var form = document.getElementById("user_form");    

                    form.addEventListener("submit", function(event){ 
                        var password = document.getElementById("userpassword").value;
                        var passrepeat = document.getElementById("passrepeat").value;
 
                        if(password == ""  || passrepeat == "") {
                            event.preventDefault();
                            confirm ("¡Completar los campos requeridos!");             
                            return false;
                        }
                        
                        if(password != passrepeat) {
                            event.preventDefault();
                            confirm ("¡Contraseñas no coinciden!");        
                            return false;
                        }
//Regular Expression    
                        if(password.length < 8 || password.length > 50){
                            event.preventDefault();
                            confirm ("¡La contraseña debe tener de 8 a 50 caracteres!");                 
                            return false;
                        }         
                        return true;
                    })
                }
                </script> 
            </div>  
        </div>
    </main>
<?php
//Footer of the page.
require_once ("views/partials/footer.php");
} else {
    header('Location: ' . root . 'not-found');
    exit;
}
?>