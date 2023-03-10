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

    $sql = "SELECT category FROM recipeinfoview WHERE category='$category';"; 
    $num_rows = $conn -> query($sql) -> num_rows;
    
    if($num_rows == 0){
        die("<p class='text-center'>¡No hay recetas disponibles para esta categoría!</p>");
    }
    $sql = "SELECT * FROM recipeinfoview WHERE category='$category' AND username = '" . $_SESSION['username'] . "' ORDER BY RAND() LIMIT 1;"; 
    $result = $conn -> query($sql);
    $row = $result -> fetch_assoc();

    $recipename = $row["recipename"];

    ?>
    <div class="jumbotron row justify-content-center">
        <div class="bg-form p-3 mt-3 col-sm-8 col-md-8">
            <div class="text-center">
                <h1 class="display-4 text-info"> <?php echo $row["recipename"]; ?> </h1>
                <h5 class="text-warning"> <?php echo "(" . $row["cookingtime"] . " minutos)"; ?> </h5>
            </div>
            <ul class="lead"> 
            <?php 
            $sql = "SELECT * FROM recipeview WHERE recipename ='$recipename';"; 
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
            $sql = "SELECT * FROM recipeinfoview WHERE recipename = '$recipename' AND username = '" . $_SESSION['username'] . "';";

            $row = $conn -> query($sql) -> fetch_assoc();
            ?>
            <div class="collapse mt-3 bg-form" id="collapseExample">
                <h5 class="card card-body text-danger"> <?php echo $row["preparation"]; ?> </h5>
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