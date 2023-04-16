<?php
//Including the database connection.
require_once ("config/db_Connection.php");

//Models.
require_once ("models/models.php");

//Head of the page.
require_once ("views/partials/head.php");

//Navigation panel of the page
require_once ("views/partials/nav.php");

if(isset($_POST["category"])) {
    $_SESSION['categoryName'] = $_POST["category"];
}
?>

<main class="container p-4">
    <div class="row mt-2 text-center justify-content-center">
        <h3>Sugerencias</h3>
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
    
    $result = $conn -> query($sql);
    $num_rows = $result -> num_rows;

        if($num_rows == 0){
            echo "<p class='text-center'>¡No hay recetas disponibles para esta categoría!</p>";
        } else {
        $row = $result -> fetch_assoc();
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
        
        $row = $conn -> query($sql) -> fetch_assoc();
        $cookingtime = $row['cookingtime'];       
    ?>
    <div class="my-4">
        <a class="text-center d-block recipe_link" href='/Cookt/views/recipes.php?recipe=<?php echo $recipename;?>&username=<?php echo $_SESSION['username'];?>&path=<?php echo base64_encode(serialize($_SERVER['PHP_SELF']));?>'>
            <p class="text-info"> <?php echo $recipename . " (" . $cookingtime . " minutos)"; ?> </p>
            <?php
            $imageDir = "../imgs/recipes/" .  $_SESSION['username'] . "/";

            $files = new Directories($imageDir, $recipename);
            $recipeImageDir = $files -> directoryFiles();

            if(file_exists($recipeImageDir)) {
            ?>
            <img src="<?php echo $recipeImageDir?>" alt="Imangen de la receta" style="width:50%;height:850%;">
            <?php
            }
            ?>      
        </a>
        <?php
        }
        ?>     
    </div>
<?php
}
?>
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("views/partials/footer.php");
?>
