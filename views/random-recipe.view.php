<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

//Page location to come back
$_SESSION["location"] = $_SERVER["REQUEST_URI"];

//Last chosen category stored to be the first of the list
if(isset($_POST["category"])) {
    $_SESSION['categoryName'] = $_POST["category"];
}
?>
<!-- Form to choose the category for the recipe suggestion-->
<main class="container p-4">
    <div class="row mt-2 text-center justify-content-center">
        <h3>Sugerencias</h3>
        <form action= "<?php echo root;?>random" method="POST" class="mt-3 col-auto">
            <div class="input-group mb-3">
                <label for="category" class="input-group-text">Categoría: </label>
                
                <select class="form-select" name="category" id="category">
                    <?php
                    if(isset($_SESSION['categoryName'])){
                        $sql = "SELECT category FROM categories WHERE NOT category='" . $_SESSION['categoryName'] . "' AND state = 1;";                    
                        
                        echo "<option value='" . $_SESSION['categoryName'] . "'>" . ucfirst($_SESSION['categoryName']) . "</option>";
                    } else {
                        $sql = "SELECT category FROM categories WHERE state = 1;";                                      
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
//If a category is chosen
    if(isset($_POST["category"])) {
    $category = $_POST["category"];

//category id
    $sql = "SELECT categoryid FROM categories WHERE category= ? AND state = 1;"; 
    $stmt = $conn -> prepare($sql); 
    $stmt->bind_param("s", $category);
    $stmt->execute();

    $result = $stmt -> get_result(); 
    $row = $result -> fetch_assoc();   
    $categoryId = $row['categoryid'];

//Random recipe for that category
    $sql = "SELECT recipename FROM recipe WHERE categoryid = ?
    AND username = ? AND state = 1 ORDER BY rand() LIMIT 1;";

    $stmt = $conn -> prepare($sql); 
    $stmt->bind_param("is", $categoryId, $_SESSION['username']);
    $stmt->execute();

    $result = $stmt -> get_result(); 
    $num_rows = $result -> num_rows;
//If there is no recipe
        if($num_rows == 0){
            echo "<p class='text-center'>¡No hay recetas disponibles para esta categoría!</p>";
//If there is       
        } else {
        $row = $result -> fetch_assoc();
        $recipename= $row['recipename'];

        $sql = "SELECT DISTINCT cookingtime
                FROM recipe  
                WHERE recipename = ? AND username = ? AND state = 1";

        $stmt = $conn -> prepare($sql); 
        $stmt->bind_param("ss", $recipename, $_SESSION['username']);
        $stmt->execute();

        $result = $stmt -> get_result(); 
        $row = $result -> fetch_assoc();  

        $cookingtime = $row['cookingtime'];       
    ?>
    <div class="my-4">
        <a class="recipe_link" href='<?php echo root;?>recipes?recipe=<?php echo $recipename;?>&username=<?php echo $_SESSION['username'];?>'>
            <p class="text-info"> <?php echo $recipename . " (" . $cookingtime . " minutos)"; ?> </p>
            <?php
//Recipe image            
            $imageDir = "imgs/recipes/" .  $_SESSION['username'] . "/";

            $files = new Directories($imageDir, $recipename);
            $ext = $files -> directoryFiles();

            if($ext !== null) {
                $recipeImageDir = $imageDir . $recipename . "." . $ext;
            } else {
                $recipeImageDir = "";
            } 
            ?>
            <img src="<?php echo $recipeImageDir?>" title="receta" alt="Imangen de la receta" style="width:50%;height:850%;">
            <?php
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
//Exiting connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>
