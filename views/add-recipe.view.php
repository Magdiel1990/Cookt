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
            <form class="text-center form" id="add_recipe_form" enctype="multipart/form-data" method="POST" action="<?php echo root;?>create">
            
                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="recipename">Nombre: </label>
                    <input  class="form-control" type="text" id="recipename" name="recipename" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" max-length="50" min-length="7" required>             
                </div>
                <div class="input-group mb-3">
                    <div>
                        <label class="input-group-text" for="imageUrl">Url de la imagen</label>
                        <input class="form-control" type="url" name="imageUrl" id="imageUrl">
                    </div>
                    <div class="frame">
                        <div class="dropzone">
                            <img src="http://100dayscss.com/codepen/upload.svg" class="upload-icon" />
                            <input type="file" name="recipeImage" accept=".png, .jpeg, .jpg, .gif" class="upload-input form-control" id="recipeImage"/>
                        </div>
                    </div>
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
                    <input class="form-control" type="number" id="cookingtime" name="cookingtime" placeholder="en minutos" max="180" min="5">             
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
                <div class="mt-3" id="message"></div>               
            </form>
            
            <script>
                add_recipe_validation();
                textarea_indication();

//Recipe addition validation method              
                function add_recipe_validation() {
//Form   
                    var form = document.getElementById("add_recipe_form");
                    form.addEventListener("submit", function(event){

                    var regExp = /[a-zA-Z\t\h]+|(^$)/;
                    var recipename = document.getElementById("recipename").value;
                    var cookingtime = document.getElementById("cookingtime").value;
                    var ingredients = document.getElementById("ingredients").value;
                    var preparation = document.getElementById("preparation").value;
                    var recipeImage = document.getElementById("recipeImage");
                    var message = document.getElementById("message");
                    var file = recipeImage.files[0];
                    var weight = file.size;
                    var fileType = file.type;
                    var allowedImageTypes = ["image/jpeg", "image/gif", "image/png", "image/jpg"];

//Conditions
                    if(recipename == "" || preparation == "" || ingredients == ""){
                        event.preventDefault();
                        message.innerHTML = "Completar los campos requeridos";             
                        return false;
                    }
//Regular Expression    
                    if(!recipename.match(regExp)){
                        event.preventDefault();
                        message.innerHTML = "¡Nombre de receta incorrecto!";                 
                        return false;
                    }
//Cooking time parameters    
                    if(cookingtime > 180 || cookingtime < 5){
                        event.preventDefault();
                        message.innerHTML = "¡Tiempo de cocción debe estar entre 5 - 180 minutos!";  
                        return false;
                    }      
                    if (recipeImage.value != "") {
//Size in Bytes     
                        if(weight > 300000) {
                            event.preventDefault();
                            message.innerHTML = "¡El tamaño de la imagen debe ser menor que 300 KB!";  
                            return false;
                        }       
//Image format validation
                        if(!allowedImageTypes.includes(fileType)){
                            event.preventDefault();
                            message.innerHTML = "¡Formatos de imagen admitidos: jpg, png y gif!";
                            return false;
                        }
                    }
                        return true;                           
                    })
                }
//Textarea indications   
                function textarea_indication() {
                    var preparation = document.getElementById("preparation");
                    var ingredient = document.getElementById("ingredients");
                    var recipename = document.getElementById("recipename");

                    ingredient.addEventListener("focus", function(event){
                        ingredient.setAttribute("placeholder", "¡Finalice cada ingrediente con un punto y aparte!");
                        ingredient.spellcheck = true;     
                    })<
                    ingredient.addEventListener("blur", function(event){
                        ingredient.removeAttribute("placeholder");
                        ingredient.spellcheck = false;     
                    })
                    ingredient.addEventListener("focus", function(event){
                        preparation.spellcheck = true;     
                    })
                    ingredient.addEventListener("blur", function(event){
                        preparation.spellcheck = false;     
                    })
                      ingredient.addEventListener("focus", function(event){
                        recipename.spellcheck = true;     
                    })
                    ingredient.addEventListener("blur", function(event){
                        recipename.spellcheck = false;     
                    })
                }
            </script>            
        </div>
    </div>
</main>
<?php
//Exiting connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>