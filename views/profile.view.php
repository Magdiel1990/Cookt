<?php
//Including the database connection.
require_once ("config/db_Connection.php");

//Models.
require_once ("models/models.php");

//Head of the page.
require_once ("views/partials/head.php");

//Navigation panel of the page
require_once ("views/partials/nav.php");

$sql = "SELECT * FROM users WHERE userid = '". $_SESSION['userid']."';";

$row = $conn -> query($sql) -> fetch_assoc();

$userName = $row["username"];
$firstName=  $row["firstname"];
$lastName=  $row["lastname"];
$type = $row["type"];
$email = $row["email"];
$currentPassword = $row["password"];
$sex = $row["sex"];
?>

<main class="container p-4">
    <?php
        //Messages that are shown in the add_units page
            if(isset($_SESSION['message'])){
            $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
            echo $message -> buttonMessage();           

        //Unsetting the messages variables so the message fades after refreshing the page.
            unset($_SESSION['message_alert'], $_SESSION['message']);
            }
     
     
     
        $img_dir = "/Cookt/imgs/users/" . $_SESSION['username'];
        if(file_exists($img_dir)){

        }
        
        
        
    ?>
    <div class="row">
        <div class="col-auto card card-body">
            <div class="p-4">
                <img src="<?php ?>" alt="Foto de perfil">
                <h4><?php echo $userName;?></h4>
                <h4><?php echo $type;?></h4>
                <div>
                    <a class="btn btn-danger" href="">Eliminar cuenta</a>
                </div>
            </div>
        </div>
        <div class="col-auto card card-body">
            <div class="p-4">
                <form action="update.php?userid=<?php echo $userId; ?>" method="POST">

                    <div class="input-group mb-3">
                        <label class="input-group-text is-required" for="firstname">Nombre: </label>
                        <input class="form-control"  value="<?php echo $firstName; ?>" type="text" id="firstname" name="firstname"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="30">
                    </div>

                    <div class="input-group mb-3">
                        <label class="input-group-text is-required" for="lastname">Apellido: </label>
                        <input class="form-control"  value="<?php echo $lastName; ?>" type="text" id="lastname" name="lastname"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="40">
                    </div>

                    <div class="input-group mb-3">
                        <label class="input-group-text is-required" for="username">Usuario: </label>
                        <input class="form-control" value="<?php echo $userName; ?>" type="text" id="username" name="username"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="30">
                    </div>
                    
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="current_password">Contraseña actual: </label>
                        <input class="form-control" type="password" id="current_password" name="current_password"  minlength="8">
                    </div>      
                    
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="new_password">Nueva contraseña: </label>
                        <input class="form-control" type="password" id="new_password" name="new_password"  minlength="8">
                    </div>   

                    <div class="input-group mb-3">
                        <label class="input-group-text" for="repite_password">Repite nueva contraseña: </label>
                        <input class="form-control" type="password" id="repite_password" name="repite_password" minlength="8">
                    </div>  

                    <div class="input-group mb-3">
                        <label class="input-group-text" for="userImage">Foto de perfil: </label>
                        <input type="file" name="userImage" accept=".png, .jpeg, .jpg, .gif" class="form-control" id="userImage">
                    </div> 

                    <div class="input-group mb-3">
                        <label class="input-group-text" for="useremail">Email: </label>
                        <input class="form-control" value="<?php echo $email; ?>"  type="email" id="useremail" name="useremail" minlength="15" maxlength="70">
                    </div>

                    <div class="text-center mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sex" id="M" value="M" <?php if($sex == "M"){ echo "checked";}?> required>
                            <label class="form-check-label" for="M">M</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sex" id="F" value="F" <?php if($sex == "F"){ echo "checked";}?>>
                            <label class="form-check-label" for="F">F</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sex" id="O" value="O" <?php if($sex == "O"){ echo "checked";}?>>
                            <label class="form-check-label" for="O">O</label>
                        </div>
                    </div>                      

                    <div class="m-auto">
                        <div class="text-center">
                            <input  class="btn btn-primary" name="usersubmit" type="submit" value="Editar">
                            <a class="btn btn-secondary" href="views/add-users.php">Regresar</a>
                        </div>
                    </div>       
                </form>
           </div>
        </div>
    </div>
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("views/partials/footer.php");
?>