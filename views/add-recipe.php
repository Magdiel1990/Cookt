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

<main class="container p-4">
     <?php
        //Messages that are shown in the add_units page
            if(isset($_SESSION['message'])){
            buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

        //Unsetting the messages variables so the message fades after refreshing the page.
            unset($_SESSION['message_alert'], $_SESSION['message']);
            }
    ?>
    <div class="m-2 justify-content-center row">
        <div class="col-auto col-xl-4">
            <div class="bg-form p-4 mb-4">       
                <h3 class="text-center">Agregar Receta</h3>
            <!--Form for filtering the database info-->
                <form class="m-2 text-center" method="POST" action="../actions/create.php">
                    <?php
                    $sql = "SELECT i.ingredient FROM reholder rh JOIN ingredients i ON i.id = rh.ingredientid WHERE rh.username = '" . $_SESSION['username'] . "';";
                    $result = $conn -> query($sql);
                    $num_rows = $result -> num_rows;

                    if ($num_rows == 0) {
                        $where = "WHERE username = '" . $_SESSION['username'] . "' ORDER BY ingredient";                                               
                    } else {
                        $where = "WHERE NOT ingredient IN (";

                        while($row = $result -> fetch_assoc()) {
                            $where .= "'" . $row["ingredient"] . "', ";
                        }
                        
                        $where = substr_replace($where, "", -2);
                        $where .= ") AND username = '" . $_SESSION['username'] . "' ORDER BY ingredient";                        
                    }
                    $sql = "SELECT ingredient FROM ingredients $where;";                         
                    $result = $conn -> query($sql);
                    $num_rows = $result -> num_rows;

                    if($num_rows > 0) {
                    ?>                
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
                            $sql = "SELECT ingredient FROM ingredients $where;";                         
                            $result = $conn -> query($sql);
                            while($row = $result -> fetch_assoc()) {
                                echo '<option value="' . $row["ingredient"] . '">' . $row["ingredient"] . '</option>';
                            }
                            ?>
                        </select> 
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="detail">Detalle:</label>
                        <input class="form-control" type="text" name="detail" id="detail" maxlength="100">
                    </div>
                    <div>         
                        <input class="btn btn-primary" type="submit" value="Agregar">
                    </div>                     
                    <?php
                    } else {
                    ?>
                    <div>
                        <a class="btn btn-primary" href="add-ingredients.php">Ingredientes</a>
                    </div>
                    <?php
                    }
                    ?>
                </form>
            </div> 
        </div>
        <div class="col-auto col-xl-4">       
            <!-- List with ingredients that will conform the recipe-->
            <div class="p-2">
                <h3 class="text-center">Ingredientes</h3>
                <?php
                $sql = "SELECT re_id, concat_ws(' ', rh.quantity, rh.unit, 'de' , i.ingredient, rh.detail) as fullingredient FROM reholder rh JOIN ingredients i ON i.id = rh.ingredientid WHERE rh.username = '" . $_SESSION['username'] . "';";

                $result = $conn -> query($sql);

                $num_rows = $result -> num_rows;

                $html = "";

                if ($num_rows != 0) {
                    $html .= "<ol>";            
                    while($row = $result -> fetch_assoc()){                    
                        $html .= "<li>";
                        $html .= "<a href='../actions/delete.php?id=" . $row["re_id"] . "'>" . $row["fullingredient"];
                        $html .= "</a>";
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
        </div>
        <div class="col-auto col-xl-4">
            <form class="text-center form" enctype="multipart/form-data" method="POST" action="../actions/create.php" onsubmit="return validationNumberText('cookingtime', 'recipename', /[a-zA-Z\t\h]+|(^$)/)">
            
                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="recipename">Nombre: </label>
                    <input  class="form-control" type="text" id="recipename" name="recipename" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" oninvalid="setCustomValidity('¡Solo letras por favor!')" max-length="50" min-length="7" required>             
                </div>
                
                <div class="mb-3">
                    <label class="form-label" for="recipeImage">Foto de la receta</label>
                    <input type="file" name="recipeImage" accept=".png, .jpeg, .jpg, .gif" class="form-control" id="recipeImage">
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
            
                <div class="row mb-3">
                    <label for="preparation" class="form-label is-required">Preparación: </label>
                    <textarea class="form-control" name="preparation" id="preparation" cols="5" rows="4" required></textarea>
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