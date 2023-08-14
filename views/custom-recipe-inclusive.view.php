<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

//Current location in order to come back
$_SESSION['location'] = $_SERVER["REQUEST_URI"];

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

    <div  class="row mt-2 text-center justify-content-center">
<!--Form for choosing the ingredients-->
        <h3>Elegir por Ingrediente</h3>
        <form class="m-3 col-auto" method="POST" action="<?php echo root;?>create">
<!-- List of ingredients -->
           <div class="input-group">
                <label class="input-group-text" for="customingredient">Ingredientes: </label>
                <?php
                $num_rows = new IngredientList("ingholder", "ingredients", "ingredient", $_SESSION['username']);
                $num_rows = $num_rows -> ingQuantity();

                if($num_rows > 0) {
                ?>
                <select class="form-select" name="customingredient" id="customingredient">
                    <?php 
                    $options = new IngredientList("ingholder", "ingredients", "ingredient", $_SESSION['username']);
                    $options -> ingredientOptions();                   
                    ?>
                </select> 
                <input type="hidden" name="uri" value="custom-inclusive">
                <input class="btn btn-primary" type="submit" value="Agregar"> 
                <?php
//If there is no ingredient added                
                } else {
                ?>
                <a class="btn btn-primary" href="<?php echo root;?>ingredients">Agregar</a>
                <?php 
                } 
                ?> 
            </div>
        </form>
    </div>
    <div class="row mt-4">
        <?php
//List of chosen ingredients 
        $result = $conn -> query("SELECT i.ingredient, ih.ingredientid FROM ingholder ih JOIN ingredients i ON i.id = ih.ingredientid WHERE ih.username = '" . $_SESSION['username'] . "' AND i.state = 1;");

        if($result -> num_rows == 0){
            echo "<p class='text-center'>Agregue los ingredientes para conseguir recetas...</p>";

        } else {
            $html = "<div class='col-auto'>";
            $html .= "<ul class='custom-list'>";
            while($row = $result -> fetch_assoc()) {
                $html .= "<li>";
                $html .= "<a href='" . root . "delete?custom=" . $row['ingredient'] . "&uri=custom-inclusive' " . "title='Eliminar' class='click-del-link'>";
                $html .= ucfirst($row["ingredient"]);
                $html .= "</a>";
                $html .= "</li>";
//Ingredients are added into an array                
                $ingArray[] = $row["ingredient"];
            }
            $html .= "</ul>";
            $html .= "</div>";     
            echo $html;
        }
        ?>
    </div>
            
    <div class="row mt-2">
        <?php
//Array containing the chosen recipes        
        if(isset($ingArray)){
        
        $arrayCount = count($ingArray);
//Recipes of the user
        $result = $conn -> query("SELECT r.recipename, r.ingredients, c.category FROM recipe r JOIN categories c ON r.categoryid = c.categoryid WHERE username = '" . $_SESSION['username'] . "' AND r.state = 1;");
        
            if($result -> num_rows != 0){
//Array to save the recipes that have the ingredients
                $recipes = [];

                while ($row = $result -> fetch_assoc()) {
//Users recipes
                    $ingredients = $row["ingredients"]; 
                                                         
                    for($i = 0; $i < $arrayCount; $i++){      
//Checking if the ingredient is in the recipe              
                        if(stripos($ingredients, $ingArray[$i]) !== false){
//Checking if the recipe has already been added in the array                                                   
                            if(!in_array($row["recipename"], $recipes)){
                                $recipes[] = $row["recipename"];
                                $category[] = $row["category"];
                            }    
                        }             
                    }                    
                }
//Recipes containing the ingredients                 
                if(isset($recipes) && count($recipes) != 0) {
                    $countRecipe = count($recipes);
                    $html = "";
                    for($i = 0; $i < $countRecipe; $i++){    
                        $html .= '<div class="my-2 py-2 col-auto">'; 
                        $html .= '<div class="card">';
                        $html .= '<h4 class="card-header">' . $category[$i] . '</h4>';
                        $html .= '<div class="card-body">';
                        $html .= '<p class="card-text">' . $recipes[$i] . '</p>';
                        $html .= "<a class='see-btn btn btn-primary' href='" . root . "recipes?recipe=" . $recipes[$i] . "&username=" . $_SESSION['username'] . "' title='receta'>Ver</a>";
                        $html .= '</div>'; 
                        $html .= '</div>';  
                        $html .= '</div>'; 
                    }
                    echo $html;
//If there is no match                  
                } else {
                    echo "<h4 class='mt-5 text-center'>Ninguna receta disponible...</h4>";
                }             
            } 
        }
        ?>
    </div>
</main>
<script>
deleteMessage("click-del", "ingrediente");   

//Delete message
function deleteMessage(button, pageName){
var deleteButtons = document.getElementsByClassName(button);

    for(var i = 0; i<deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function(event){    
            if(confirm("¿Desea eliminar este " + pageName + "?")) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        })
    }
}
</script>

<?php
//Exiting connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>