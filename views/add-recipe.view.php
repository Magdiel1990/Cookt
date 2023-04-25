<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");
?>

<main class="container p-4">
    <?php
//Messages
        if(isset($_SESSION['message'])){
        $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
        echo $message -> buttonMessage();           

//Unsetting the messages
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>
    <div class="m-2 justify-content-center row">
        <div class="col-auto col-xl-4">
            <div class="bg-form p-4 mb-4">  
<!--Form for adding the recipe-->     
                <h3 class="text-center">Agregar Receta</h3>            
                <form class="m-2 text-center" method="POST" action="/create">
                    <?php
//Object to determine if there are ingredients that have not been added to form the recipe
                    $num_rows = new IngredientList("reholder", "ingredients", "ingredient", $_SESSION['username']);                    
                    $num_rows = $num_rows -> ingQuantity();
                    
                    if($num_rows > 0) {
                    ?>                
                    <div class="input-group mb-3">
                        <label class="input-group-text is-required" for="quantity">Cantidad: </label>                    
                        <input class="form-control" type="number" name="quantity" id="quantity" max="1000" min="0" autofocus required>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="fraction">Fraction: </label>       
                        <select class="form-select" name="fraction" id="fraction">
                            <?php  
//Fraction of measurements to use 
                                $fraction = ["", "1/8", "1/4", "1/3", "1/2", "2/3", "3/4"];
                                for($i=0; $i < count($fraction); $i++){
                                    echo '<option value="' . $fraction[$i] . '">' . $fraction[$i] . '</option>';                          
                                }    
                            ?>
                        </select>                       
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="unit">Unidad: </label>                
                        <select class="form-select" name="unit" id="unit">
                            <?php
//Units
                            $unitOptions = new Units(null);
                            $unitOptions -> unitOptions();   
                            ?>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="ingredient">Ingrediente: </label>
                        <select class="form-select" name="ingredient" id="ingredient">
                            <?php
//Ingredients that haven't been added
                            $options = new IngredientList("reholder", "ingredients", "ingredient", $_SESSION['username']);
                            $options -> ingredientOptions();
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
//Link to go and add ingredients if there is no or all have been already picked
                    } else {
                    ?>
                    <div>
                        <a class="btn btn-primary" href="/ingredients">Ingredientes</a>
                    </div>
                    <?php
                    }
                    ?>
                </form>
            </div> 
        </div>
        <div class="col-auto col-xl-4">       
<!-- Ingredients that will conform the recipe-->
            <div class="p-2">
                <h3 class="text-center">Ingredientes</h3>
                <?php
                $sql = "SELECT re_id, concat_ws(' ', rh.quantity, rh.unit, 'de' , i.ingredient, rh.detail) as fullingredient FROM reholder rh JOIN ingredients i ON i.id = rh.ingredientid WHERE rh.username = '" . $_SESSION['username'] . "';";
                $result = $conn -> query($sql);

                $num_rows = $result -> num_rows;

                $html = "";

                if ($num_rows != 0) {
//If there are ingredients picked to form the recipe, the box for naming the recipe is shown 
                    $display = "";

                    $html .= "<ol>";            
                    while($row = $result -> fetch_assoc()){                    
                        $html .= "<li>";
//The ingredient is deleted if clicked on
                        $html .= "<a href='/delete?id=" . $row["re_id"] . "'>" . $row["fullingredient"];
                        $html .= "</a>";
                        $html .= "</li>";
                                    
                    }
                    $html .= "</ol>"; 

                    echo $html;
                }  else {   
//If there are no ingredients picked to form the recipe, the box for naming the recipe is hidden 
                    $display = "style = 'display: none;'";      

                    $html .= "<p>";
                    $html .= "Agrega los ingredientes...";
                    $html .= "</p>";
                    echo $html;                                                  
                }
                ?>            
            </div>            
        </div>
    <!-- Only displayed when there is at least one ingredient added to form the recipe-->
        <div class="col-auto col-xl-4" <?php  echo $display;?>>
            <form class="text-center form" enctype="multipart/form-data" method="POST" action="/create" onsubmit="return validationNumberText('cookingtime', 'recipename', /[a-zA-Z\t\h]+|(^$)/)">
            
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
//We retrieve the last chosen category
                        if(isset($_SESSION['category'])){
                            $sql = "SELECT category FROM categories WHERE NOT category = '" . $_SESSION['category'] . "' ORDER BY rand();";

                            $result = $conn -> query($sql);
//The first option will be the last chosen category
                           echo '<option value="' .  $_SESSION['category'] . '">' . ucfirst( $_SESSION['category']) . '</option>';
//If no category had been picked, random categories are shown                        
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
//Exiting connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>