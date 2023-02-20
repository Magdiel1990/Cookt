<?php
//Head of the page.
require_once ($_SERVER["DOCUMENT_ROOT"]."/cookt/modules/head.php");

//Including the database connection.
require_once ($_SERVER["DOCUMENT_ROOT"]."/cookt/config/db_Connection.php");

//Navigation panel of the page
require_once ($_SERVER["DOCUMENT_ROOT"]."/cookt/modules/nav.php");

$recipe = isset($_GET["recipe"]) ? $conn -> real_escape_string($_GET["recipe"]) : null;

$sql = "SELECT * FROM recipeview WHERE recipename = '$recipe'";

$result = $conn -> query($sql);
$num_rows = $result -> num_rows;
$row = $result->fetch_assoc();

?>
<link rel="stylesheet" href="../styles/styles.css">
<main class="container p-4">
    <?php
    if($num_rows == 0) {
    ?>
    <div class="text-center">
        <p>Esta receta no tiene ingredientes 
            <a class="m-2" href="../actions/edit.php?recipename=<?php echo $recipe ?>">
                <i class="fa-solid fa-plus">                    
                </i>
            </a>
            <a href="../index.php">
                <i class="fa-regular fa-backward-fast">                    
                </i>
            </a>
        </p>        
    </div>
    <?php
    } else {   
    ?>
    <div class="jumbotron row justify-content-center">
        <div class="bg-form p-3 mt-3 col-sm-8 col-md-8">
            <div class="text-center">
                <h1 class="display-4 text-info"> <?php echo $row["recipename"]; ?> </h1>
                <h5 class="text-warning"> (<?php echo $row["cookingtime"]; ?> minutos)</h5>
            </div>
            <ul class="lead"> 
            <?php            
            $result = $conn -> query($sql);
            
            while($row = $result->fetch_assoc()){
                echo "<li class='text-success'>" . $row["indications"]. ".</li>";
            }        
            ?>   
            </ul>
            <hr class="my-4">
        </div>
        <div class="lead text-center">
            <div class="mt-3">
                <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                Preparación
                </a>
                <a class="btn btn-secondary" href="../index.php">Regresar</a>
            </div>
        </div>
        <div class="col-sm-8 col-md-8">
            <?php
            $sql = "SELECT * FROM recipeinfoview WHERE recipename = '$recipe'";

            $result = $conn -> query($sql);
            $row = $result->fetch_assoc();
            ?>
            <div class="collapse mt-3 bg-form" id="collapseExample">
                <div class="card card-body text-danger"> <?php echo ucfirst($row["preparation"]); ?> </div>
            </div>        
        </div>        
    </div>
    <?php
    }
    ?>
</main>
<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>