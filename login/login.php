<?php
//Including the database connection.
require_once ("../config/db_Connection.php");

//Models.
require_once ("../models/models.php");

//Head of the page.
require_once ("../modules/head.php");

?>
<link rel="stylesheet" href="../css/styles.css">

<main class="row justify-content-center p-5" id="loginBackground">

<?php
//Messages that are shown in the add_units page
    if(isset($_SESSION['message'])){
    buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

//Unsetting the messages variables so the message fades after refreshing the page.
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }
?>
    <div class="text-center col-auto form">
        <form class="mb-1">
        <!-- Username input -->
        <div class="form-outline mb-4">
            <input type="text" id="username" class="form-control" name="username"/>
            <label class="form-label" for="username">Usuario</label>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
            <input type="password" id="password" class="form-control" name="password"/>
            <label class="form-label" for="password">Contraseña</label>
        </div>

        <!-- 1 column grid layout for inline styling -->
        <div class="mb-4">
            <div>
            <!-- Simple link -->
            <a style = "text-decoration: none;" href="#!">Olvidaste la contraseña?</a>
            </div>
        </div>

        <!-- Submit button -->
        <div>
            <button type="button" class="btn btn-primary mb-4">Sign in</button>
        </div>
        <!-- Register buttons -->
        <div>
            <h6>No te ha registrado? <a href="#!" style = "text-decoration: none;">Regístrate</a></h6>
        </div>
        </form>
    </div>
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("../modules/footer.php");
?>