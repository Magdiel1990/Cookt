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
        <h3 class="text-center mb-3">Agregar Receta</h3>
        <div class="col-lg-6 col-md-8 col-sm-9 col-xl-5">
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
                
                <div class="mb-3">
                    <label for="ingredients" class="form-label is-required">Ingredientes: </label>
                    <textarea class="form-control" name="ingredients" id="ingredients" cols="10" rows="10" required></textarea>
                </div>            
            
                <div class="mb-3">
                    <label for="preparation" class="form-label is-required">Preparación: </label>
                    <textarea class="form-control" name="preparation" id="preparation" cols="10" rows="10" required></textarea>
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