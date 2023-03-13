<?php
//Including the database connection.
require_once ("../config/db_Connection.php");

//Models.
require_once ("../models/models.php");

//Head of the page.
require_once ("../modules/head.php");

?>
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
                    <img src="../imgs/login/Picture.png" class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form action="" method="POST">
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
                            <button type="button" class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
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
$conn -> close();
//Footer of the page.
require_once ("../modules/footer.php");
?>