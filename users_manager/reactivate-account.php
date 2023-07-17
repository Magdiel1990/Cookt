<?php
session_name("signup");

session_start();

//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

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
<body class="bg-primary">
    <main class="container">
        <div class="p-5 m-4">                
        </div>
        <div class="p-2">
            <h2 class="text-center">Reactivar cuenta</h2>                
        </div>
        <div class="row p-4 align-items-center justify-content-center">
            <div class="col-auto">
                <form class="text-center recovery-form" action="<?php echo root;?>reactivate" method="POST" id="reactivate_form">
<!-- Email input -->
                    <div class="form-outline mb-3">
                        <label class="form-label mb-4" for="email">Email</label>
                        <input type="email" id="email" class="form-control" name="email" size="35" maxlength="70" minlength="15" placeholder="Escribe tu correo electrónico" required/>                        
                    </div>

                    <?php
//Messages
                    if(isset($_SESSION['message'])){
                    echo "<div class='my-1'>";

                    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
                    echo $message -> textMessage();  

//Unsetting the messages variables so the message fades after refreshing the page.
                    unset($_SESSION['message_alert'], $_SESSION['message']);

                    echo "</div>";
                    } else {
                        echo "<div class='mt-4'></div>";
                    }
                    ?>                        
                    <div class="text-center">
                        <input type="submit" name="reactivate" value="Reactivar" class="btn btn-primary">                              
                        <p class="small fw-bold mb-0 mt-4">
                            <a class="text-decoration-none px-2" href="<?php echo root;?>login">Login</a>
                            ¿Si no tienes cuenta? 
                            <a href="<?php echo root;?>signup" style = "text-decoration: none;" class="link-danger px-2">Regístrate</a>
                        </p>                  
                    </div>                                    
                </form>
            </div>
        </div>
    </main>
    <script>
    emailValidation(); 

//Form validation
    function emailValidation(){
    var form = document.getElementById("reactivate_form");    

        form.addEventListener("submit", function(event){ 
            var email = document.getElementById("email").value;        

            if(email == "") {
                event.preventDefault();
                message.innerHTML = "¡Escriba el correo!";             
                return false;
            }

            if(email.length < 15 || email.length > 70){
                event.preventDefault();                        
                message.innerHTML = "¡El email debe tener de 15 a 70 caracteres!";                 
                return false;
            }                
            return true;
        })
    }
    </script>
<?php
//Footer of the page.
require_once ("views/partials/footer.php");
?>