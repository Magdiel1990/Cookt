<?php
//Including the database connection.
require_once ("../config/db_Connection.php");

//Head of the page.
require_once ("../modules/head.php");

//Navigation panel of the page
require_once ("../modules/nav.php");

//Models.
require_once ("../models/models.php");

if(isset($_GET["recipe"]) && isset($_GET["username"]) && isset($_GET["path"])){
    $recipe = $_GET["recipe"];
    $username = $_GET["username"];
    
    if($_GET["path"] == "index"){
        $pathToReturn = "../index.php";        
    } else if (isset($_GET["ingredients"])) {
        $ingArray = $_GET["ingredients"];
        $pathToReturn = unserialize(base64_decode($_GET["path"])) . "?ingredients=". $ingArray ."&username=" . $username;

    } else {
        $pathToReturn = unserialize(base64_decode($_GET["path"])) . "?username=" . $username;
    }

    $imageDir = "../imgs/recipes/" . $username . "/";
    $recipeImageDir = directoryFiles($imageDir, $recipe);
}

$sql = "SELECT r.recipeid, 
r.recipename,
concat_ws(' ', ri.quantity, ri.unit, 'de' , i.ingredient, ri.detail) as indications, 
r.cookingtime,
r.date, 
r.preparation, 
c.category, 
r.username
from recipe r 
join recipeinfo ri 
on ri.recipeid = r.recipeid
join categories c 
on r.categoryid = c.categoryid
join ingredients i 
on i.id = ri.ingredientid
WHERE r.recipename = '$recipe' 
AND r.username = '$username';";

$result = $conn -> query($sql);
$num_rows = $result -> num_rows;
$row = $result->fetch_assoc();
?>
<main class="container p-2 mt-4">

<?php
if(isset($row["category"]) && isset($row["recipename"]) && isset($row["cookingtime"]) && isset($row["preparation"]) && isset($row["date"])) {
$category = $row["category"];
$recipeName = $row["recipename"];
$cookingTime = $row["cookingtime"];
$preparation = $row["preparation"];
$date = date ("d-M-Y", strtotime($row["date"]));

$categoryDir = "../imgs/categories/";

//Function to get the image directory from the category
$categoryImgDir = directoryFiles($categoryDir , $category);

?>
    <div style="background: url('<?php echo $categoryImgDir; ?>') center; background-size: auto;">
        <div class="jumbotron row justify-content-center">
            <div class="bg-form p-3 mt-3 col-sm-auto col-md-9 col-lg-8">
                <div class="text-center">
                    <h1 class="display-4 text-info"> <?php echo $recipeName; ?> </h1>
                    <h5 class="text-warning" style='font-size: 1.5rem;'> (<?php echo $cookingTime; ?> minutos)</h5>
                </div>
                <div class="d-flex flex-row justify-content-between">
                    <ul class="lead"> 
                    <?php            
                    $result = $conn -> query($sql);
                    
                    while($row = $result->fetch_assoc()){
                        echo "<li class='text-success' style='font-size: 1.5rem;'>" . $row["indications"]. ".</li>";
                    }        
                    ?>   
                    </ul>
                    <?php
                        if(file_exists($recipeImageDir)) {
                    ?>
                    <div>
                        <img src="<?php echo $recipeImageDir?>" alt="Imangen de la receta" style="width:auto;height:11rem;">
                    </div>                 
                    <?php
                        }
                    ?>
                </div>
                <hr class="my-2">
            </div>
            <div class="lead text-center">
                <div class="mt-3">
                    <a class="btn btn-primary" data-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Preparación
                    </a>
                    <a class="btn btn-secondary" href="<?php echo $pathToReturn;?>">Regresar</a>
                </div>
            </div>
            <div class="col-sm-8 col-md-8 py-4">
                <div class="collapse bg-form" id="collapse">
                    <div class="card card-body text-dark" style="font-size: 1.5rem;"> <?php echo ucfirst($preparation); ?> 
                        <span class="text-info mt-4 text-center"> <?php echo $date; ?> </span>            
                    </div>
                </div>        
            </div>        
        </div>
    </div>
<?php
} else {
?> 
    <div class="row justify-content-center">
        <p class="col-auto">¡Esta receta no tiene ingredientes!</p>
        <div class="col-auto">
            <a class="btn btn-warning" href="../actions/edit.php?recipename=<?php echo $recipe ?>&username=<?php echo $username;?>">
                <i class="fa-solid fa-plus" title="Agregar"></i>
            </a>
            <a class="btn btn-warning" href="<?php echo $pathToReturn;?>">
                <i class="fa-solid fa-backward-step" title="Regresar"></i>
            </a>                
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
