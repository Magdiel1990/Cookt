<?php
//Head of the page.
require_once ("../modules/head.php");

//Navigation panel of the page
require_once ("../modules/nav.php");

//Models
require_once ("../models/models.php");

//Including the database connection.
require_once ("../config/db_Connection.php");
?>

<link rel="stylesheet" href="../styles/styles.css">

<?php
/************************************************************************************************/
/***************************************INGREDIENTS (AGREGAR RECETA) EDITION CODE********************************/
/************************************************************************************************/


if(isset($_GET["id"])){

$id = $_GET["id"];

$sql = "SELECT * FROM reholder WHERE re_id = $id";

$result = $conn -> query($sql);

$row = $result -> fetch_assoc();

$num_rows = $result -> num_rows;

$quantity = $row["quantity"];
$ingredient = $row["ingredient"];
$unit = $row["unit"];

    if ($num_rows == 0) {
        //Message if the variable is null.
        $_SESSION['message'] = 'Este ingrediente no existe!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add_units.php
        header('Location: ../views/add_recipe.php');

    } else {
?>        
<main>
<?php
//Messages that are shown in the index page
    if(isset($_SESSION['message'])){
    buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

//Unsetting the messages variables so the message fades after refreshing the page.
    unset($_SESSION['message_alert'], $_SESSION['message']);
}
?>
    <h3>Editar ingrediente</h3>     
    <div class="card card-body">
        <form action="update.php?editid=<?php echo $id ?>" method="POST">

            <div>
                <label for="quantity">Cantidad: </label>
                <input type="number" name="quantity" value="<?php echo $quantity?>" class="form-control" id="quantity">
            </div>

            <div>
                <label for="unit">Unidad: </label>
                <select class="form-control" name="unit" id="unit">
                <?php
                $sql = "SELECT unit FROM units";

                $result = $conn -> query($sql);

                while($row = $result -> fetch_assoc()) {
                    echo '<option value="' . $row["unit"] . '">' . $row["unit"] . '</option>';
                }
                ?>
                
                </select>
            </div>
            
            <div>
                <label for="ingredient">Ingrediente: </label>
                <select class="form-control" name="ingredient" id="ingredient">
                <?php
                $sql = "SELECT ingredient FROM ingredients";

                $result = $conn -> query($sql);

                while($row = $result -> fetch_assoc()) {
                    echo '<option value="' . $row["ingredient"] . '">' . $row["ingredient"] . '</option>';
                }
                ?>
                </select>
            </div>                 
            
            <div>
                <input type="submit" name="edit" value="Actualizar"> 
                <a href='../views/add_recipe.php' class='btn btn-secondary' title="Regresar"><i class="fa-solid fa-right-from-bracket"></i></a>  
            </div>
        </form>
    </div>      
</main>
<?php
    }
}


/************************************************************************************************/
/******************************************RECIPE EDITION CODE***********************************/
/************************************************************************************************/


if(isset($_GET['recipename'])){
$recipeName = $_GET['recipename'];

$sql = "SELECT * FROM recipeinfoview WHERE recipename = '$recipeName';";

$result = $conn -> query($sql);
$row = $result -> fetch_assoc();

$cookingTime = $row["cookingtime"];
$preparation= $row["preparation"];
$observation = $row["observation"];
$category = $row["category"];

$sql = "SELECT indications FROM recipeview WHERE recipename = '$recipeName';";

$result = $conn -> query($sql);
$row = $result -> fetch_assoc();
?>
<main class="container p-4">
<?php
//Messages that are shown in the index page
    if(isset($_SESSION['message'])){
    buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

//Unsetting the messages variables so the message fades after refreshing the page.
    unset($_SESSION['message_alert'], $_SESSION['message']);
}
?>
    <div class="row mt-2 text-center justify-content-center">
        <h3>EDITAR RECETA</h3>     
        <div class="mt-3 col-auto">
            <form class="bg-form card card-body" action="update.php?editname=<?php echo $recipeName ?>" method="POST">

                <div class="input-group mb-3">
                    <label class="input-group-text" for="recipeName">Nombre: </label>
                    <input type="text" name="recipeName" value="<?php echo $recipeName?>" class="form-control" id="recipeName">
                </div>

                <div class="input-group mb-3 w-50">
                    <label class="input-group-text" for="category">Categoría: </label>                
                    <select class="form-select" name="category" id="category">
                        <?php
                        $sql = "SELECT category FROM categories WHERE NOT category='" . $category . "';";

                        $result = $conn -> query($sql);
                        echo '<option value="' . $category . '">' . $category . '</option>';
                        while($row = $result -> fetch_assoc()) {
                            echo '<option value="' . $row["category"]  . '">' . $row["category"] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text" for="cookingTime">Tiempo de cocción: </label>
                    <input type="number" name="cookingTime" value="<?php echo $cookingTime?>" class="form-control" id="cookingTime">
                </div>
                <div class="row">           
                    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
                        <label  class="form-label" for="preparation">Preparación: </label>
                        <textarea name="preparation"  cols="30" rows="10" class="form-control" id="preparation">
                            <?php echo $preparation?>
                        </textarea>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
                        <label  class="form-label" for="observation">Observación: </label>
                        <textarea name="observation"  cols="30" rows="10" class="form-control" id="observation">
                            <?php echo $observation?> 
                        </textarea>
                    </div>                 
                </div>
                
                <div class="mb-3">
                    <input class='btn btn-primary' type="submit" name="edit" value="Actualizar"> 
                    <a href='../index.php' class='btn btn-secondary' title="Regresar"><i class="fa-solid fa-right-from-bracket"></i></a>  
                </div>
            </form>
        </div> 
    </div>     
</main>

<?php

    
}
//Footer of the page.
require_once ("../modules/footer.php");
?>