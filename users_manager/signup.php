<?php
session_name("signup");

session_start();

//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

if(!empty($_POST)) {
    $filter = new Filter ($_POST['firstname'], FILTER_SANITIZE_STRING, $conn);
    $firstname = $filter -> sanitization();

    $filter = new Filter ($_POST['lastname'], FILTER_SANITIZE_STRING, $conn);
    $lastname = $filter -> sanitization();

    $filter = new Filter ($_POST['username'], FILTER_SANITIZE_STRING, $conn);
    $username = $filter -> sanitization();

    $filter = new Filter ($_POST ["email"], FILTER_SANITIZE_EMAIL, $conn);
    $email = $filter -> sanitization();

    $filter = new Filter ($_POST ["password"], FILTER_SANITIZE_STRING, $conn);
    $password = $filter -> sanitization();

    $filter = new Filter ($_POST ["passrepeat"], FILTER_SANITIZE_STRING, $conn);
    $passrepeat = $filter -> sanitization();

    $sex = $_POST ["sex"];
    $terms = $_POST ["terms"];
    $rol = $_POST ["rol"];
    $state = $_POST ["state"];
    
    if ($firstname == "" || $lastname == "" || $username == "" || $password == ""  || $passrepeat == "" || $sex == "") {

        //Message if the variable is null.
        $_SESSION['message'] = '¡Complete o seleccione todos los campos por favor!';
        $_SESSION['message_alert'] = "danger";
            
    } else {
        $sql = "SELECT userid FROM users WHERE username = '$username';";
        $num_rows = $conn -> query($sql) -> num_rows;

        if($num_rows == 0){   
            if($password != $passrepeat) {
            //Message if the variable is null.
            $_SESSION['message'] = '¡Contraseñas no coinciden!';
            $_SESSION['message_alert'] = "danger";  
            
            } else {

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                if($state == "yes") {
                $state = 1;
                } else { 
                $state = 0;
                }

                $sql = "SELECT userid FROM users WHERE firstname = '" . $firstname . "' AND lastname = '" . $lastname . "' AND username = '" . $username . "' AND `password` = '$hashed_password';";

                $num_rows = $conn -> query($sql) -> num_rows;

                if($num_rows == 0) {
                
                $stmt = $conn -> prepare("INSERT INTO users (firstname, lastname, username, `password`, `type`, email, `state`, sex) VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
                $stmt->bind_param ("ssssssis", $firstname, $lastname, $username, $hashed_password, $rol, $email, $state, $sex);

                if ($stmt->execute()) {            
                //The page is redirected to the add-recipe.php
                header('Location: /login');
                } else {
                //Failure message.
                    $_SESSION['message'] = '¡Error al agregar usuario!';
                    $_SESSION['message_alert'] = "danger";
                }
                } else {
                //Success message.
                    $_SESSION['message'] = '¡Este usuario ya existe!';
                    $_SESSION['message_alert'] = "success";                        
                }
            }
        } else {
            //Failure message.
            $_SESSION['message'] = '¡Este usuario no está disponible!';
            $_SESSION['message_alert'] = "danger";
        }
    }
}

$header = new PageHeaders($_SERVER["REQUEST_URI"]);
$header = $header -> pageHeader();

/*$userCreation = new User($firstname, $lastname, $username, $password, $passrepeat, $sex, $email, $terms, $rol, $state, "", $url, $conn);
$userCreation -> newUser();*/
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
    <main class="bg-dark">        
        <div class="container py-4">
            <div class="row d-flex justify-content-center">
                <div class="col">
                    <div class="card card-registration my-4">
                        <div class="row align-items-center justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-5">
                                <img src="/imgs/login/Picture.png" class="img-fluid" alt="Sample image">
                            </div>
                            <div class="col-md-9 col-lg-6 col-xl-6">
                                <form action="/signup" method="POST" class="card-body p-md-5 text-black">
                                    <h3 class="mb-3 text-center">Regístrate</h3>                        
                                    <div class="row">                                   
                                        <div class="col-md-6 mb-3">
                                            <div class="form-outline">
                                                <input type="text" id="firstname" class="form-control form-control-md" name="firstname"/>
                                                <label class="form-label is-required" for="firstname">Nombre</label>
                                            </div>
                                        </div>

                                         <div class="col-md-6 mb-3">
                                            <div class="form-outline">
                                                <input type="text" id="lastname" class="form-control form-control-md" name="lastname"/>
                                                <label class="form-label is-required" for="lastname">Apellido</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-outline">
                                                <input type="text" id="username" class="form-control form-control-md" name="username"/>
                                                <label class="form-label is-required" for="username">Usuario</label>
                                            </div>
                                        </div>                                      

                                        <div class="input-group col-md-4 mb-3">
                                            <input type="password" id="password" class="form-control form-control-md" name="password"/>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" onclick="showpass('password')"><i id="watchpass" class="fa-solid fa-eye"></i></button>        
                                            </div>                                                
                                        </div>
                                        <label class="form-label is-required" for="password">Contraseña</label>

                                        <div class="input-group col-md-6 mb-3">
                                            <input type="password" id="passrepeat" class="form-control form-control-md" name="passrepeat"/>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" onclick="showpass('passrepeat')"><i id="watchpass" class="fa-solid fa-eye"></i></button>        
                                            </div>                                             
                                        </div>
                                        <label class="form-label is-required" for="passrepeat">Repita contraseña</label>

                                        <script>
                                            function showpass(id){
                                            var tipo = document.getElementById(id);
                                            
                                                if(tipo.type == "password"){
                                                    tipo.type = "text";                                                   
                                                } else {
                                                    tipo.type = "password";                                                                                   
                                                }
                                            }
                                        </script>
                                        <div class="form-outline col-md-6 mb-3">
                                            <input type="email" id="email" class="form-control form-control-md" name="email"/>
                                            <label class="form-label" for="email">Correo electrónico</label>
                                        </div>
                                    </div>

                                    <div class="d-md-flex justify-content-start align-items-center mb-3 py-2">
                                        <h6 class="me-4 is-required">Género: </h6>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sex" id="femaleGender"
                                            value="F" />
                                            <label class="form-check-label" for="femaleGender">Mujer</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sex" id="maleGender"
                                            value="M" />
                                            <label class="form-check-label" for="maleGender">Hombre</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sex" id="otherGender"
                                            value="O" />
                                            <label class="form-check-label" for="otherGender">Otro</label>
                                        </div>                                         
                                    </div>          
                                    <div class="form-check d-flex justify-content-center mb-3">
                                        <input class="form-check-input me-2" type="checkbox" value="yes" id="terms" name="terms" required/>
                                        <label class="form-check-label is-required" for="terms">
                                        Estoy de acuerdo con los <a href="#!">Términos de Servicio.</a>
                                        </label>
                                    </div> 

                                    <input type="hidden" id="rol" name="rol" value = "Viewer"/>

                                    <input type="hidden" id="state" name="state" value = ""/>

                                    <input type="hidden" id="url" name="url" value = "<?php echo $_SERVER["REQUEST_URI"];?>"/>

                                    <div class="d-flex justify-content-center">
                                        <h5 class="mt-2 pt-1">
                                            <a class="text-decoration-none px-2" href="/login">Login</a>
                                        </h5>
                                        <input type="reset" class="btn btn-light btn-lg" value="Limpiar todo">
                                        <input type="submit" class="btn btn-warning btn-lg ms-2" value="Registrarse">
                                    </div>
                                </form>
                                <?php
                                    //Messages that are shown in the add_units page
                                    if(isset($_SESSION['message'])){
                                    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
                                    echo $message -> buttonMessage();    

                                    //Unsetting the messages variables so the message fades after refreshing the page.
                                    unset($_SESSION['message_alert'], $_SESSION['message']);
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>       
    </main>
<?php
//Footer of the page.
require_once ("views/partials/footer.php");
?>