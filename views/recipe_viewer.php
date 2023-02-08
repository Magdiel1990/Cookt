<?php
//Head of the page.
require_once ("../modules/head.php");

//Including the database connection.
require_once ("../config/db_Connection.php");

//Navigation panel of the page
require_once ("../modules/nav.php");

$recipe = isset($_GET["recipe"]) ? $conn -> real_escape_string($_GET["recipe"]) : null;

$sql = "SELECT * FROM recipeview WHERE recipename = '$recipe'";

$result = $conn -> query($sql);
$row = $result->fetch_assoc();
?>
<main>
    <div class="jumbotron">
        <h1 class="display-4"> <?php echo $row["recipename"]; ?> </h1>
        <h7> (<?php echo $row["cookingtime"]; ?> minutos)</h7>
        <ul class="lead"> 
        <?php 
        while($row = $result->fetch_assoc()){
            echo "<li>" . $row["indications"]. "</li>";
        }        
        ?>   
        </ul>
        <hr class="my-4">
        <div class="lead">
            <div>
                <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                Preparación
                </a>
                <a href="../index.php">Regresar</a>
            </div>
            <?php
            $sql = "SELECT * FROM recipeinfoview WHERE recipename = '$recipe'";

            $result = $conn -> query($sql);
            $row = $result->fetch_assoc();
            ?>
            <div class="collapse" id="collapseExample">
                <div class="card card-body"> <?php echo $row["preparation"]; ?> </div>
            </div>        
        </div>
    </div>
</main>
<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>