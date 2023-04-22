<?php
//Head of the page.
require_once ("views/partials/head.php");

//Navigation panel of the page
require_once ("views/partials/nav.php");

$_SESSION["location"] = "/profile";

$sql = "SELECT * FROM users WHERE userid = '". $_SESSION['userid']."';";

$row = $conn -> query($sql) -> fetch_assoc();

$userName = $row["username"];
$firstName =  $row["firstname"];
$lastName =  $row["lastname"];
$type = $row["type"];
$email = $row["email"];
$currentPassword = $row["password"];
$sex = $row["sex"];
$date = date("d-m-Y", strtotime($row["created_at"]));
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
        
    $img_dir = "imgs/users/";
    
    $files = new Directories($img_dir, $_SESSION['username']);
    $imgProfileDir = $files -> directoryProfiles();

    if(pathinfo($imgProfileDir, PATHINFO_EXTENSION) == ""){
        $path = "src = 'imgs/unknown/unknown_user.png'";
    } else {
         $path = "src = '" . $imgProfileDir . "'";
    }
?>  
    <h3 class="text-center">Perfil</h3>
    <div class="row my-3 justify-content-center align-items-center">        
        <div class="col-xl-5 col-lg-6 col-md-7 col-sm-9">
            <div class="p-4 card card-body bg-form">
                <div class="row my-4 justify-content-center align-items-center">
                    <img <?php echo $path;?> alt="Foto de perfil" class="profile">
                </div>
                <div class="my-2">
                    <h4><span>Usuario: </span><?php echo $userName;?></h4>
                    <h4><span>Privilegio: </span><?php echo $type;?></h4>
                    <h4><span>Nombre: </span><?php echo $firstName . " " . $lastName;?></h4>
                    <h4><span>Email: </span><?php echo $email;?></h4>
                    <h4><span>Suscripción: </span><?php echo $date;?></h4>
                </div>
                <div class="text-center">
                    <a class="btn btn-danger" href="/delete?userid=<?php echo $_SESSION['userid'];?>">Eliminar cuenta</a>
                    <a class="btn btn-primary" href="/edit?userid=<?php echo $_SESSION['userid'];?>">Editar</a>
                </div>
            </div>
        </div>
    </div>
 </main>
<?php
$conn -> close();
//Footer of the page.
require_once ("views/partials/footer.php");
?>