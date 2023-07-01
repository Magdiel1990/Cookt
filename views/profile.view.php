<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

//Page location to come back
$_SESSION["location"] = root . "profile";

//Users data
$sql = "SELECT * FROM users WHERE userid = '". $_SESSION['userid']."';";
$row = $conn -> query($sql) -> fetch_assoc();

$userName = $row["username"];
$firstName =  $row["firstname"];
$lastName =  $row["lastname"];
$type = $row["type"];
$email = $row["email"] ? $row["email"] : "No tiene correo";
$currentPassword = $row["password"];
$sex = $row["sex"];
$date = date("d-m-Y", strtotime($row["created_at"]));
?>

<main class="container p-4">
<?php
//Messages
    if(isset($_SESSION['message'])){
        $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
        echo $message -> buttonMessage();           

//Unsetting the messages
        unset($_SESSION['message_alert'], $_SESSION['message']);
    } 
//Users images directory        
    $img_dir = "imgs/users/";

    if(!file_exists($img_dir)) {
        mkdir($img_dir, 0777, true);
    }  

//Delete an old image if it exists
    $files = new Directories($img_dir, $_SESSION['username']);
    $ext = $files -> directoryFiles();

//If the file doesn't exist the default photo is shown
    if($ext !== null) {
        $imgProfileDir = $img_dir . $_SESSION['username'] . "." . $ext;
        $path = "src = '" . $imgProfileDir . "'";

    } else {
        $path = "src = 'imgs/unknown/unknown_user.png'";
    }
?>
<!-- Profile info-->  
    <h3 class="text-center">Perfil</h3>
    <div class="row my-3 justify-content-center align-items-center">        
        <div class="col-xl-5 col-lg-6 col-md-7 col-sm-9">
            <div class="p-4 card card-body bg-form">
                <div class="row my-4 justify-content-center align-items-center">
                    <img <?php echo $path;?> alt="Foto de perfil" id="profile">
                </div>
                <div class="my-2">
                    <h4><span class="profile_description">Usuario: </span><?php echo $userName;?></h4>
                    <h4><span class="profile_description">Privilegio: </span><?php echo $type;?></h4>
                    <h4><span class="profile_description">Nombre: </span><?php echo $firstName . " " . $lastName;?></h4>
                    <h4><span class="profile_description">Email: </span><?php echo $email;?></h4>
                    <h4><span class="profile_description">Suscripción: </span><?php echo $date;?></h4>
                </div>
<!-- Delete and edit buttons--> 
                <div class="text-center">
                    <a class="btn btn-danger" href="<?php echo root;?>delete?userid=<?php echo $_SESSION['userid'];?>&type=<?php echo base64_encode(serialize($_SESSION['type']))?>">Eliminar cuenta</a>
                    <a class="btn btn-primary" href="<?php echo root;?>edit?userid=<?php echo $_SESSION['userid'];?>">Editar</a>
                </div>
            </div>
        </div>
    </div>
 </main>
 <script>
deleteMessage("btn-danger", "cuenta");   

//Delete message
function deleteMessage(button, pageName){
var deleteButtons = document.getElementsByClassName(button);

    for(var i = 0; i<deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function(event){    
            if(confirm("¿Desea eliminar su " + pageName + "?")) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        })
    }
}
</script>
<?php
//Exiting connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>