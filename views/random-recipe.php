<?php
//Including the database connection.
require_once ("../config/db_Connection.php");

//Models.
require_once ("../models/models.php");

//Head of the page.
require_once ("../modules/head.php");

//Navigation panel of the page
require_once ("../modules/nav.php");

if(isset($_POST["category"])) {
    $_SESSION['categoryName'] = $_POST["category"];
}
?>

<main class="container p-4">
    <div class="row mt-2 text-center justify-content-center">
        <h3>SUGERENCIAS</h3>
        <form action= "<?php echo $_SERVER ['PHP_SELF'] ?>" method="POST" class="mt-3 col-auto">
            <div class="input-group mb-3">
                <label for="category" class="input-group-text">Categoría: </label>
                
                <select class="form-select" name="category" id="category">
                    <?php
                    if(isset($_SESSION['categoryName'])){
                        $sql = "SELECT category FROM categories WHERE NOT category='" . $_SESSION['categoryName'] . "';";                    
                        
                        echo "<option value='" . $_SESSION['categoryName'] . "'>" . ucfirst($_SESSION['categoryName']) . "</option>";
                    } else {
                        $sql = "SELECT category FROM categories;";                                      
                    }

                    $result = $conn -> query($sql); 

                    while($row = $result -> fetch_assoc()) {
                        echo "<option value='" . $row["category"] . "'>" . ucfirst($row["category"]) . "</option>";
                    }

                    ?>
                </select>

                <input class="btn btn-primary" type="submit" value="Sugerir">
            </div>
        </form>
    </div>

    <?php
    if(isset($_POST["category"])) {
    $category = $_POST["category"];

    $sql = "SELECT categoryid FROM categories WHERE category='$category';"; 
    $row = $conn -> query($sql) -> fetch_assoc();
    $categoryId = $row['categoryid'];


    $sql = "SELECT recipename FROM recipe WHERE categoryid = '$categoryId'
    AND username = '" . $_SESSION['username'] . "' ORDER BY rand() LIMIT 1;";

    $row = $conn -> query($sql) -> fetch_assoc();
    $recipename= $row['recipename'];

    $sql = "SELECT DISTINCT
            r.cookingtime,
            concat_ws(' ', ri.quantity, ri.unit, 'de' , i.ingredient) as indications,
            r.preparation 
            from recipe r 
            join recipeinfo ri 
            on ri.recipeid = r.recipeid
            join ingredients i 
            on i.id = ri.ingredientid
            WHERE r.recipename = '$recipename' AND r.username = '" . $_SESSION['username'] . "'";
    $result = $conn -> query($sql);    
    $num_rows = $result -> num_rows;

        if($num_rows == 0){
            echo "<p class='text-center'>¡No hay recetas disponibles para esta categoría!</p>";
        } else {
        $row = $result -> fetch_assoc();
        $cookingtime = $row['cookingtime'];
        $preparation = $row["preparation"];        
    ?>
    <div class="jumbotron row justify-content-center">
        <div class="bg-form p-3 mt-3 col-sm-8 col-md-8">
            <div class="text-center">
                <h1 class="display-4 text-info"> <?php echo $recipename; ?> </h1>
                <h5 class="text-warning"> <?php echo "(" . $cookingtime . " minutos)"; ?> </h5>
            </div>
            <div class="d-flex flex-row justify-content-between">
            <ul class="lead"> 
            <?php 
            $result = $conn -> query($sql);      

            while($row = $result->fetch_assoc()){
                echo "<li class='text-success'>" . $row["indications"] . ".</li>";
            }
            ?>   
            </ul>
            <hr class="my-4">
            <?php
                $imageDir = "../imgs/recipes/" .  $_SESSION['username'] . "/";
                $recipeImageDir = directoryFiles($imageDir, $recipename);

                if(file_exists($recipeImageDir)) {
            ?>
            <div>
                <img src="<?php echo $recipeImageDir?>" alt="Imangen de la receta" style="width:auto;height:11rem;">
            </div>                 
            <?php
                }
            ?>
            </div>
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
            <div class="collapse mt-3 bg-form" id="collapseExample">
                <h5 class="card card-body text-danger"> <?php echo $preparation; ?> </h5>
            </div>     
        </div>
    </div>
<?php
        }
    }
?>
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("../modules/footer.php");
?>