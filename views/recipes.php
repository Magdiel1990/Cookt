<?php
//Including the database connection.
require_once ("../config/db_Connection.php");

//Head of the page.
require_once ("../modules/head.php");

//Navigation panel of the page
require_once ("../modules/nav.php");

//Models.
require_once ("../models/models.php");

if(isset($_GET["recipe"])){
    $recipe = $_GET["recipe"];

    $recipeImageDir = "../imgs/recipes/" . $_SESSION['username'] . "/". $recipe . ".jpg";
}

$sql = "SELECT * FROM recipeview WHERE recipename = '$recipe' AND username = '" . $_SESSION['username'] . "';";

$result = $conn -> query($sql);
$num_rows = $result -> num_rows;
$row = $result->fetch_assoc();
$category = $row['category'];

$categoryDir = "../imgs/categories/";

//Function to get the image directory from the category
$categoryImgDir = directoryFiles($categoryDir , $category);

?>

<link rel="stylesheet" href="../css/styles.css">

<main class="container p-2 my-4"  style="background: url('<?php echo $categoryImgDir ?>') center;">
    <?php
    if($num_rows == 0) {
    ?>

    <div class="row justify-content-center">
        <p class="col-auto">¡Esta receta no tiene ingredientes!</p>
        <div class="col-auto">
            <a class="btn btn-warning" href="../actions/edit.php?recipename=<?php echo $recipe ?>">
                <i class="fa-solid fa-plus" title="Agregar"></i>
            </a>
            <a class="btn btn-warning" href="../index.php">
                <i class="fa-solid fa-backward-step" title="Regresar"></i>
            </a>                
        </div>
        </div> 
    <?php
    } else {   
    ?>
    <div class="jumbotron row justify-content-center">
        <div class="bg-form p-3 mt-3 col-sm-auto col-md-9 col-lg-8">
            <div class="text-center">
                <h1 class="display-4 text-info"> <?php echo $row["recipename"]; ?> </h1>
                <h5 class="text-warning"> (<?php echo $row["cookingtime"]; ?> minutos)</h5>
            </div>
            <div class="d-flex flex-row justify-content-between">
                <ul class="lead"> 
                <?php            
                $result = $conn -> query($sql);
                
                while($row = $result->fetch_assoc()){
                    echo "<li class='text-success'>" . $row["indications"]. ".</li>";
                }        
                ?>   
                </ul>
                <?php
                    if(file_exists($recipeImageDir)) {

                ?>
                <div class="">
                    <img src="<?php echo $recipeImageDir?>" alt="Imangen de la receta" style="width:auto;height:11rem;">
                </div>                 
                <?php
                }
                ?>
            </div>
            <hr class="my-4">
        </div>
        <div class="lead text-center">
            <div class="mt-3">
                <a class="btn btn-primary" data-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapseExample">
                Preparación
                </a>
                <a class="btn btn-secondary" href="../index.php">Regresar</a>
            </div>
        </div>
        <div class="col-sm-8 col-md-8">
            <?php
            $sql = "SELECT * FROM recipeinfoview WHERE recipename = '$recipe' AND username = '" . $_SESSION['username'] . "';";

            $row = $conn -> query($sql) -> fetch_assoc();

            ?>
            <div class="collapse mt-3 bg-form" id="collapse">
                <div class="card card-body text-danger"> <?php echo ucfirst($row["preparation"]); ?> </div>
            </div>        
        </div>        
    </div>
    <?php
    }
    ?>
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("../modules/footer.php");
?>
