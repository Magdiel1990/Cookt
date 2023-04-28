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

    <div  class="row mt-2 text-center justify-content-center">
<!--Form for choosing the ingredients-->
        <h3>Elegir por Ingrediente</h3>
        <form class="m-3 col-auto" method="POST" action="/create">

           <div class="input-group">
                <label class="input-group-text" for="customingredient">Ingredientes: </label>
<!--  -->                
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
                <input class="btn btn-primary" type="submit" value="Agregar"> 
                <?php 
                } else {
                ?>
                <a class="btn btn-primary" href="/ingredients">Agregar</a>
                <?php 
                } 
                ?> 
            </div>
        </form>
    </div>
    <div class="row mt-4">
        <div class="col-auto">
        <?php
        $sql = "SELECT i.ingredient, ih.ingredientid FROM ingholder ih JOIN ingredients i ON i.id = ih.ingredientid WHERE ih.username = '" . $_SESSION['username'] . "';";

        $result = $conn -> query($sql);

        if($result -> num_rows == 0){
            echo "<p class='text-center'>Agregue los ingredientes para conseguir recetas...</p>";

        } else {
            $html = "<div>";
            $html .= "<ol>";
            while($row = $result -> fetch_assoc()) {
                $html .= "<li>";
                $html .= "<a href='/delete?custom=" . $row['ingredient'] . "' " . "title='Eliminar' class='ingredients'>";
                $html .= ucfirst($row["ingredient"]);
                $html .= "</a>";
                $html .= "</li>";
                $ingArray[] = $row["ingredient"];
            }
            $html .= "</ol>";
            $html .= "</div>";                   
            $html .= "</div>";      
            echo $html;
        }
        ?>
        </div>
            
        <div class="col-auto">
        <?php
        if(isset($ingArray)){
        
        $arrayCount = count($ingArray);

        $sql = "SELECT recipename, ingredients FROM recipe WHERE username = '" . $_SESSION['username'] . "'";
        $result = $conn -> query($sql);
        
            if($result -> num_rows != 0){
                $recipes = [];

                while ($row = $result -> fetch_assoc()) {
                    $ingredients = $row["ingredients"]; 
                                                         
                    for($i = 0; $i < $arrayCount; $i++){                    
                        if(stripos($ingredients, $ingArray[$i])){                            

                            if(!in_array($row["recipename"], $recipes)){
                                $recipes[] = $row["recipename"];
                            }    
                        }             
                    }                    
                }
                 
                if(isset($recipes)) {
                    $countRecipe = count($recipes);
                    $html = "";
                    $html .= "<div class='suggestion_container'>";
                    $html .= "<ul>";
                    for($i = 0; $i < $countRecipe; $i++){                    
                        $html .= "<li><a href='/recipes?recipe=" . $recipes[$i] . "&username=" . $_SESSION['username'] . "&path=" . base64_encode(serialize($_SERVER['REQUEST_URI'])). "' title='receta'>" . $recipes[$i] . "</a></li>";         
                    }
                    $html .= "</ul>";
                    $html .= "</div>";
                    echo $html;
                } else {
                    echo "<h4 class='mt-5 text-center'>Ninguna receta disponible...</h4>";
                }             
            } 
        }
        ?>
        </div>
    </div>
</main>

<?php
$conn -> close();
//Footer of the page.
require_once ("views/partials/footer.php");
?>