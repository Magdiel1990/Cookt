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
        <h3>EDITAR INGREDIENTE</h3>     
        <div class="mt-3 col-auto">
            <form class="bg-form card card-body" action="update.php?editid=<?php echo $id ?>" method="POST">

                <div class="input-group mb-3">
                    <label class="input-group-text" for="quantity">Cantidad: </label>
                    <input type="number" name="quantity" value="<?php echo $quantity?>" class="form-control" id="quantity">
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text" for="unit">Unidad: </label>
                    <select class="form-select" name="unit" id="unit">
                    <?php
                    $sql = "SELECT unit FROM units";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["unit"] . '">' . ucfirst($row["unit"]) . '</option>';
                    }
                    ?>
                    
                    </select>
                </div>
                
                <div class="input-group mb-3">
                    <label class="input-group-text" for="ingredient">Ingrediente: </label>
                    <select class="form-select" name="ingredient" id="ingredient">
                    <?php
                    $sql = "SELECT ingredient FROM ingredients";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["ingredient"] . '">' . ucfirst($row["ingredient"]) . '</option>';
                    }
                    ?>
                    </select>
                </div>                 
                
                <div class="mb-3">
                    <input class='btn btn-primary' type="submit" name="edit" value="Actualizar"> 
                    <a href='../views/add_recipe.php' class='btn btn-secondary' title="Regresar"><i class="fa-solid fa-right-from-bracket"></i></a>  
                </div>
            </form>
        </div>
    </div>      
</main>
<?php
    }
}


/************************************************************************************************/
/******************************************CATEGORY EDITION CODE***********************************/
/************************************************************************************************/


if(isset($_GET['categoryid'])){
$categoryId = $_GET['categoryid'];

$sql = "SELECT * FROM categories WHERE categoryid = '$categoryId';";

$row = $conn -> query($sql) -> fetch_assoc();

$category = $row["category"];

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
        <h3>EDITAR CATEGORÍA</h3>     
        <div class="mt-3 col-auto">
            <form  enctype="multipart/form-data" class="bg-form card card-body" action="update.php?categoryid=<?php echo $categoryId; ?>" method="POST">

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="categoryName">Nombre: </label>
                    <input type="text" name="categoryName" value="<?php echo $category;?>" class="form-control" id="categoryName" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" oninvalid="setCustomValidity('¡Solo letras por favor!')" max-length="20" min-length="2" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="categoryImage">Foto de la categoría</label>
                    <input type="file" name="categoryImage" accept=".png, .jpeg, .jpg, .gif" class="form-control" id="categoryImage">
                </div> 
                <div class="mt-2">
                    <input class="btn btn-primary" type="submit" value="Editar" name="categoryeditionsubmit">
                    <a href="../views/add_categories.php" class="btn btn-secondary">Regresar</a>
                </div>
                </form>
            </div>
       </div>                  
    </div>     
</main>

<?php
}
/************************************************************************************************/
/******************************************RECIPE EDITION CODE***********************************/
/************************************************************************************************/


if(isset($_GET['recipename'])) {
$recipeName = $_GET['recipename'];

$sql = "SELECT * FROM recipeinfoview WHERE recipename = '$recipeName';";

$result = $conn -> query($sql);
$row = $result -> fetch_assoc();

$cookingTime = $row["cookingtime"];
$preparation=  sanitization($row["preparation"], FILTER_SANITIZE_STRING, $conn);
$observation = sanitization($row["observation"], FILTER_SANITIZE_STRING, $conn);
$category = $row["category"];

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
            <form class="bg-form card card-body" action="update.php?editname=<?php echo $recipeName ?>" method="POST" onsubmit="return validationNumberText('cookingTime', 'newRecipeName', /[a-zA-Z\t\h]+|(^$)/)">

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="newRecipeName">Nombre: </label>
                    <input type="text" name="newRecipeName" value="<?php echo $recipeName;?>" class="form-control" id="newRecipeName" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" oninvalid="setCustomValidity('¡Solo letras por favor!')" max-length="50" min-length="7" required>
                </div>

                <div class="input-group mb-3 w-50">
                    <label class="input-group-text" for="category">Categoría: </label>                
                    <select class="form-select" name="category" id="category">
                        <?php
                        $sql = "SELECT category FROM categories WHERE NOT category='" . $category . "';";

                        $result = $conn -> query($sql);
                        echo '<option value="' . $category . '">' .  ucfirst($category) . '</option>';
                        while($row = $result -> fetch_assoc()) {
                            echo '<option value="' . $row["category"]  . '">' . ucfirst($row["category"]) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text" for="cookingTime">Tiempo de cocción: </label>
                    <input type="number" name="cookingTime" value="<?php echo $cookingTime;?>" class="form-control" id="cookingTime" min="5" max="180">
                </div>
                <div class="row">           
                    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
                        <label  class="form-label is-required" for="preparation">Preparación: </label>
                        <textarea name="preparation"  cols="30" rows="10" class="form-control" id="preparation" required>
                            <?php echo $preparation;?>
                        </textarea>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
                        <label  class="form-label" for="observation">Observación: </label>
                        <textarea name="observation"  cols="30" rows="10" class="form-control" id="observation">
                            <?php echo $observation;?> 
                        </textarea>
                    </div>                 
                </div>
                
                <div class="mb-3">
                    <input class='btn btn-primary' type="submit" name="edit" value="Actualizar"> 
                    <a href='../index.php' class='btn btn-secondary' title="Regresar"><i class="fa-solid fa-right-from-bracket"></i></a>  
                </div>
            </form>
        </div>
         
        <div class="mt-3 bg-form card card-body col-auto">
            <h3 class="text-center">Editar Ingredientes</h3>
            <div class="mt-2">
            <?php
            $sql = "SELECT indications FROM recipeview WHERE recipename = '$recipeName';";

            $result = $conn -> query($sql);
            
            $html = "<ul>";
            while($row = $result -> fetch_assoc()){
                $html .= "<li class='my-2'><i>". $row['indications'] .".</i>";
                $html .= "<a class='btn btn-danger mx-2' href='delete.php?indication=" . $row['indications'] . "&rpename=" . $recipeName . "'>Eliminar</a>";
                $html .= "</li>";
            }
            $html .= "</ul>";
            echo $html;
            ?>
            </div>
            <div class="mb-4 mt-2 text-center m-auto">
                <form method="POST" action="create.php?rname=<?php echo $recipeName;?>" onsubmit="return validationNumber('quantity')">

                    <div class="input-group mb-3">
                        <label class="input-group-text is-required" for="quantity">Cantidad: </label>                    
                        <input class="form-control" type="number" name="qty" id="quantity" step="0.05" max="1000" min="0" required>
                    </div>

                    <div class="input-group mb-3">
                        <label class="input-group-text" for="unit">Unidad: </label>                
                        <select class="form-select" name="units" id="unit">
                            <?php
                            $sql = "SELECT unit FROM units";

                            $result = $conn -> query($sql);

                            while($row = $result -> fetch_assoc()) {
                                echo '<option value="' . $row["unit"] . '">' . $row["unit"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <label class="input-group-text" for="ingredient">Ingrediente: </label>                
                        <select class="form-select" name="ing" id="ingredient">
                            <?php
                            $sql = "SELECT ingredient FROM ingredients";

                            $result = $conn -> query($sql);

                            while($row = $result -> fetch_assoc()) {
                                echo '<option value="' . $row["ingredient"] . '">' . $row["ingredient"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
            
                    <input class="btn btn-primary" type="submit" value="Agregar ingrediente">
                </form>
            </div>
       </div>                  
    </div>     
</main>

<?php
}
$conn -> close();    

//Footer of the page.
require_once ("../modules/footer.php");
?>