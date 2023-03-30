
<?php
session_name("recovery");

session_start();
//Models.
require_once ("../models/models.php");

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
    <main class="bg-dark">
        <div class="container py-4">
            <div class="row d-flex justify-content-center">
                <div class="col">
                    <div class="card card-registration my-4">
                        <div class="row align-items-center justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-5">
                                <img src="/Cookt/imgs/login/Picture.png" class="img-fluid" alt="Sample image">
                            </div>
                            <div class="col-md-9 col-lg-6 col-xl-5">
                                <form action="" method="POST" class="card-body p-md-5 text-black">
                                    <h3 class="mb-3 text-center">Regístrate</h3>                                
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-outline">
                                            <input type="text" id="form3Example1m" class="form-control form-control-md" />
                                            <label class="form-label" for="form3Example1m">Usuario</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-outline">
                                            <input type="text" id="form3Example1n" class="form-control form-control-md" />
                                            <label class="form-label" for="form3Example1n">Nombre completo</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-md-flex justify-content-start align-items-center mb-3 py-2">
                                        <h6 class="me-4">Género: </h6>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="femaleGender"
                                            value="option1" />
                                            <label class="form-check-label" for="femaleGender">Mujer</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="maleGender"
                                            value="option2" />
                                            <label class="form-check-label" for="maleGender">Hombre</label>
                                        </div>                                    
                                    </div>

                                    <div class="form-outline mb-3">
                                        <input type="password" id="form3Example9" class="form-control form-control-md" />
                                        <label class="form-label" for="form3Example9">Contraseña</label>
                                    </div>

                                    <div class="form-outline mb-3">
                                        <input type="email" id="form3Example90" class="form-control form-control-md" />
                                        <label class="form-label" for="form3Example90">Correo electrónico</label>
                                    </div>

                                    <div class="form-check d-flex justify-content-center mb-3">
                                        <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c" />
                                        <label class="form-check-label" for="form2Example3">
                                        Estoy de acuerdo con los <a href="#!">Términos de servicio.</a>
                                        </label>
                                    </div> 

                                    <div class="d-flex justify-content-center">
                                        <input type="reset" class="btn btn-light btn-lg" value="Limpiar todo">
                                        <input type="submit" class="btn btn-warning btn-lg ms-2" value="Registrarse">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>       
    </main>
<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>