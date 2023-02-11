<?php
//Head of the page.
require_once ("../modules/head.php");

//Including the database connection.
require_once ("../config/db_Connection.php");

//Models.
require_once ("../models/models.php");

//Navigation panel of the page
require_once ("../modules/nav.php");
?>
<link rel="stylesheet" href="../styles/styles.css">
<main class="container p-4">
    <div class="row mt-2 text-center justify-content-center">
        <h3>SUGERENCIAS</h3>
        <form action="" method="POST" class="mt-3 col-auto" id="upperinput">
            <div class="input-group mb-3">
                <label for="category" class="input-group-text">Categoría: </label>
                
                <select class="form-select" name="category" id="category">
                    <?php
                    $sql = "SELECT category FROM categories";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["category"] . '">' . $row["category"] . '</option>';
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

    $sql = "SELECT * FROM recipeinfoview WHERE category='$category' ORDER BY RAND() LIMIT 1;"; 
    $result = $conn -> query($sql);
    $row = $result -> fetch_assoc();

    $recipename = $row["recipename"];

    ?>
    <div class="jumbotron">
        <h1 class="display-4"> <?php echo $row["recipename"]; ?> </h1>
        <h7> <?php echo "(" . $row["cookingtime"] . " minutos)"; ?> </h7>
        <ul class="lead"> 
    <?php 
        $sql = "SELECT * FROM recipeview WHERE recipename ='$recipename';"; 
        $result = $conn -> query($sql);

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
            $sql = "SELECT * FROM recipeinfoview WHERE recipename = '$recipename';";

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
$conn -> close();
}  
//Footer of the page.
require_once ("../modules/footer.php");
?>