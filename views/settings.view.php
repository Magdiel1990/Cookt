<?php
//Head
require_once ("views/partials/head.php");

//Nav of the page
require_once ("views/partials/nav.php");

//Current location in order to come back
$_SESSION['location'] = $_SERVER["REQUEST_URI"];

//Verify the user settings
$sql = "SELECT notification, recycle, shares, reminders FROM users WHERE username = '" . $_SESSION['username'] . "';";
$result = $conn -> query($sql);
$row = $result -> fetch_assoc();

//Settings variables
$notification = $row["notification"];
$shares = $row["shares"];
$recycle = $row["recycle"];
$reminder = $row["reminders"];

//random id
$id = rand(0,100);
?>

<main class="container p-4">
    <div class="row p-4">
       <?php 
//Messages
        if(isset($_SESSION['message'])){
        $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
        echo $message -> buttonMessage();         

//Unsetting the messages
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
        ?>
  
        <h3 class="my-4 text-center">Configuración</h3>

        <form id="setting_form" class="col-auto" method="POST" action="<?php echo root . "update?settingid=" . $id;?>" autocomplete="off">
            
            <div class="form-check form-switch my-4">
                <label class="form-check-label" for="notification">Recibir notificaciones</label>
                <input class="form-check-input" type="checkbox" role="switch" id="notification" name="notification" <?php if($notification == 1) {echo "checked";}?>>
            </div>

            <div class="form-check form-switch my-4">
                <label class="form-check-label" for="recycle">Enviar archivos eliminados a papelera</label>
                <input class="form-check-input" type="checkbox" role="switch" id="recycle" name="recycle" <?php if($recycle == 1) {echo "checked";}?>>
            </div>

             <div class="form-check form-switch my-4">
                <label class="form-check-label" for="share">Aceptar que otros usuarios me compartan recetas</label>
                <input class="form-check-input" type="checkbox" role="switch" id="share" name="share" <?php if($shares == 1) {echo "checked";}?>>
            </div>

            <div class="form-check form-switch my-4">
                <label class="form-check-label" for="reminder">Aceptar recomendaciones por el correo</label>
                <input class="form-check-input" type="checkbox" role="switch" id="reminder" name="reminder" <?php if($reminder == 1) {echo "checked";}?>>
            </div>

            <div class="my-4 text-center">
                <input  class="btn btn-success" name="submit" type="submit" value="Aceptar">
            </div>

        </form>
    </div>
</main>
<?php
//Close db connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>