<?php
//Including the database connection.
require_once ("../config/db_Connection.php");

//Models.
require_once ("../models/models.php");

//Head of the page.
require_once ("../modules/head.php");

//Navigation panel of the page
require_once ("../modules/nav.php");
?>

<link rel="stylesheet" href="../css/styles.css">

<main class="container p-4">
    <div class="row m-2 justify-content-center">
        <div class="bg-form p-3 mb-4 col-auto">
        <?php
        //Messages that are shown in the add_units page
            if(isset($_SESSION['message'])){
            buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

        //Unsetting the messages variables so the message fades after refreshing the page.
            unset($_SESSION['message_alert'], $_SESSION['message']);
            }
        ?>
            <h3 class="text-center">AGREGAR RECETA</h3>
        <!--Form for filtering the database info-->
            <form class="m-4 text-center" method="POST" action="../actions/create.php">
                <div class="d-sm-flex justify-content-around">
                    <div class="input-group mb-3">
                        <label class="input-group-text is-required" for="quantity">Cantidad: </label>                    
                        <input class="form-control" type="number" name="quantity" id="quantity" step="0.05" max="1000" min="0" autofocus required>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="unit">Unidad: </label>                
                        <select class="form-select" name="unit" id="unit">
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
                        <select class="form-select" name="ingredient" id="ingredient">
                            <?php
                            $sql = "SELECT ingredient FROM ingredients";

                            $result = $conn -> query($sql);

                            while($row = $result -> fetch_assoc()) {
                                echo '<option value="' . $row["ingredient"] . '">' . $row["ingredient"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>            
                <input class="btn btn-primary" type="submit" value="Agregar ingrediente">
            </form>
        </div>
        <div class="row justify-content-center">
            <!-- List with ingredients that will conform the recipe-->
            <div class="m-2 p-2 col-auto">
                <h3 class="text-center">Ingredientes</h3>
                <?php
                $sql = "SELECT re_id, concat_ws(' ', rh.quantity, rh.unit, 'de' , i.ingredient) as fullingredient FROM reholder rh JOIN ingredients i ON i.id = rh.ingredientid WHERE rh.username = '" . $_SESSION['username'] . "';";

                $result = $conn -> query($sql);

                $num_rows = $result -> num_rows;

                $html = "";

                if ($num_rows != 0) {
                    $html .= "<ol>";            
                    while($row = $result -> fetch_assoc()){                    
                        $html .= "<li>";
                        $html .= "<a href='../actions/edit.php?id=" . $row["re_id"] . "'>" . $row["fullingredient"] . ".";
                        $html .= "</a>";
                        $html .= "<a class='btn btn-danger' href='../actions/delete.php?id=" . $row["re_id"] . "'>";
                        $html .= "Eliminar"; 
                        $html .=  "</a>";
                        $html .= "</li>";
                                    
                    }
                    $html .= "</ol>"; 

                    echo $html;
                }  else {                          
                    $html .= "<p>";
                    $html .= "Agrega los ingredientes...";
                    $html .= "</p>";
                    echo $html;                                   
                }
                ?>
            
            </div>


            
<?php
                /*
        $target_dir = "../imgs/categories/";
        $fileExtension = strtolower(pathinfo($categoryImage["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . $newCategoryName . "." . $fileExtension;
        $uploadOk = "";

        if(is_file($target_file)){
            unlink($target_file);
        }
        
        // Check if image file is a actual image or fake image
        if(isset($_POST["categoryeditionsubmit"])) {
            $check = getimagesize($categoryImage["tmp_name"]);
            if($check == false) {
                $uploadOk = "¡Este archivo no es una imagen!";
            } 
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            $uploadOk = "¡Esta imagen ya existe!";
        }

        // Check file size
        if ($categoryImage["size"] > 2000000) {
            $uploadOk = "¡Esta imagen ya existe!";
        }

        // Allow certain file formats
        if($fileExtension != "jpg" && $fileExtension != "png" && $fileExtension != "jpeg"
        && $fileExtension != "gif" ) {
            $uploadOk = "¡Formato no admitido!";
        } 

        if ($uploadOk == "") {
            if(move_uploaded_file($categoryImage["tmp_name"], $target_file) && $conn->query($sql)){
            //Success message.
            $_SESSION['message'] = '¡Categoría editada con éxito!';
            $_SESSION['message_alert'] = "success";

            //The page is redirected to the add_units.php.
            header('Location: ../views/add-categories.php');    
            } else {
            //Failure message.
            $_SESSION['message'] = '¡Error al editar categoría!';
            $_SESSION['message_alert'] = "danger";

            //The page is redirected to the add_units.php.
            header('Location: edit.php?categoryid=' . $categoryId);
        }
        } else {
            //Failure message.
            $_SESSION['message'] = $uploadOk;
            $_SESSION['message_alert'] = "danger";

            //The page is redirected to the add_units.php.
            header('Location: edit.php?categoryid=' . $categoryId);
        }
    }    
 */
?>










            <form class="m-4 col-auto text-center form" method="POST" action="../actions/create.php" onsubmit="return validationNumberText('cookingtime', 'recipename', /[a-zA-Z\t\h]+|(^$)/)">
            
                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="recipename">Nombre: </label>
                    <input  class="form-control" type="text" id="recipename" name="recipename" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" oninvalid="setCustomValidity('¡Solo letras por favor!')" max-length="50" min-length="7" required>             
                </div>
                
                <div class="input-group mb-3">
                    <label class="input-group-text" for="category">Categoría: </label>                
                    <select class="form-select" name="category" id="category">
                        <?php
                        if(isset($_SESSION['category'])){
                            $sql = "SELECT category FROM categories WHERE NOT category = '" . $_SESSION['category'] . "' ORDER BY rand();";

                            $result = $conn -> query($sql);

                            echo '<option value="' .  $_SESSION['category'] . '">' . ucfirst( $_SESSION['category']) . '</option>';
                        } else {
                            $sql = "SELECT category FROM categories ORDER BY rand();";

                            $result = $conn -> query($sql);
                        }

                        while($row = $result -> fetch_assoc()) {
                            echo '<option value="' . $row["category"] . '">' . ucfirst($row["category"]) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text" for="cookingtime">Tiempo de cocción: </label>
                    <input class="form-control" type="number" id="cookingtime" name="cookingtime" max="180" min="5"  placeholder="en minutos">             
                </div>            
            
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
                        <label for="preparation" class="form-label is-required">Preparación: </label>
                        <textarea class="form-control" name="preparation" id="preparation" cols="30" rows="10" required></textarea>
                    </div>           
                    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
                        <label for="observation" class="form-label">Observaciones: </label>
                        <textarea class="form-control" name="observation" id="observation" cols="2" rows="2"></textarea>
                    </div>
                </div>
            
                <div>
                    <input class="btn btn-primary" type="submit" value="Agregar receta" name="addrecipe">
                </div>
            </form>
        </div>
    </div>
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("../modules/footer.php");
?>