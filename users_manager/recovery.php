
<?php
session_name("recovery");

session_start();
//Models.
require_once ("models/models.php");

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
<body class="bg-primary">
    <main>
        <section class="container">
            <div class="p-5 m-4">                
            </div>
            <div class="p-2">
                <h2 class="text-center">Reestablecer Contraseña</h2>                
            </div>
            <div class="row p-4 align-items-center justify-content-center">
                <div class="col-auto">
                    <form class="text-center recovery-form" action="/cookt/email" method="POST">
                        <!-- Email input -->
                        <div class="form-outline mb-3">
                            <label class="form-label mb-4" for="email">¿Olvidaste tu contraseña?</label>
                            <input type="email" id="email" class="form-control" name="email" size="35" placeholder="Escribe tu correo electrónico"/>                        
                        </div>
                        <?php
                        //Messages that are shown in the add_units page
                        if(isset($_SESSION['message'])){
                        echo "<div class='my-1'>";

                        $message = new Messages ($_SESSION['message'], $_SESSION['alert']);
                        echo $message -> textMessage();  

                        //Unsetting the messages variables so the message fades after refreshing the page.
                        unset($_SESSION['alert'], $_SESSION['message']);
                       
                        session_destroy();

                        echo "</div>";
                        } else {
                            echo "<div class='mt-4'></div>";
                        }
                        ?>
                           
                        <div class="text-center">
                            <input type="submit" name="Recovery" value="Reestablecer contraseña" class="btn btn-primary">                              
                            <p class="small fw-bold mb-0 mt-4">
                                <a class="text-decoration-none px-2" href="/cookt/login">Login</a>
                                ¿Si no tienes cuenta? 
                                <a href="/cookt/signup" style = "text-decoration: none;" class="link-danger px-2">Regístrate</a>
                            </p>                  
                        </div>                                    
                    </form>
                </div>
            </div>
        </section>
    </main>
<?php
//Footer of the page.
require_once ("views/partials/footer.php");
?>