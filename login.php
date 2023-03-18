<?php
//Reviso el estado de la sesión.
session_name("Login");

//Inicio una nueva sesión.
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
<body>
<main class="row justify-content-center p-5" id="loginBackground">

<?php
//Messages that are shown in the add_units page
    if(isset($_SESSION['message'])){
    buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

//Unsetting the messages variables so the message fades after refreshing the page.
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }
?>  
    
    <section class="my-4">
        <div class="container-fluid h-custom my-4">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="imgs/login/Picture.png" class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form action="/Cookt/user-handler/login-verif.php" method="POST">
                        <!-- Email input -->
                        <div class="form-outline mb-3">
                            <input type="text" id="username" class="form-control form-control-lg" name="username"/>
                            <label class="form-label" for="username">Usuario</label>
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-3">
                            <input type="password" id="password" class="form-control form-control-lg" name="password"/>
                            <label class="form-label" for="password">Contraseña</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="#!" class="text-body" style = "text-decoration: none;">¿Olvidaste la contraseña?</a>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <input type="submit" name="Login" value="Login" class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">
                            <p class="small fw-bold mt-2 pt-1 mb-0">¿No tienes cuenta? <a href="#!"
                                style = "text-decoration: none;" class="link-danger">Regístrate</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
    </section>
</main>
<?php
//Footer of the page.
require_once ("modules/footer.php");
?>