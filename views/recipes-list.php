<?php
//Including the database connection.
require_once ("../config/db_Connection.php");

//Head of the page.
require_once ("../modules/head.php");

//Navigation panel of the page
require_once ("../modules/nav.php");

//Models.
require_once ("../models/models.php");

if(isset($_GET["username"])){
    $username = $_GET["username"];
}

$sql = "SELECT recipename FROM recipe WHERE username = '$username';";
$result = $conn -> query($sql);
?>
<main class="container p-4 mt-4">
    <div class="row justify-content-center table form">
        <div class="col-auto">
            <div class="text-center">
            <?php
                if($result -> num_rows > 0) {
                ?>
                <h3>Lista de Recetas del Usuario <?php echo $username;?></h3>
            </div>
            <ol>
            <?php
                while($row = $result -> fetch_assoc()){
                    $html = "<li>"; 
                    $html .= "<a href='recipes.php?recipe=" . $row['recipename'] . "&username=" . $username . "&path=" . base64_encode(serialize($_SERVER['PHP_SELF'])) . "'>"; 
                    $html .= $row['recipename'];
                    $html .= "</a>"; 
                    $html .= "</li>"; 
                    echo $html;    
                }
            ?>
            </ol>            
            <?php
            } else {
            ?>
            <div class="text-center col-auto">
                <p>¡El usuario <?php echo $username; ?> no ha agregado recetas aún!</p>
            </div>
            <?php
            }
            ?>
        </div>
               
    </div>
    <div class="text-center">
        <a class="btn btn-secondary" href="../views/add-users.php">Usuarios</a>
    </div> 
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("../modules/footer.php");
?>
