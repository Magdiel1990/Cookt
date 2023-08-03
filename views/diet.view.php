<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

$_SESSION["lastcheck"] = 3;

//Messages
    if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();           

//Unsetting the messages
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }

?>
<main class="container p-4">

<?php
    if(isset($_POST["generate"])) {   

//Receiving how many meals a day  
    $amount = $_POST["generate"];
    $_SESSION["lastcheck"] = $amount;

    $daysNames = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
//Amount of days
    $dayCount = count($daysNames);
//Amount of recipes
    $totalRecipe = $amount * $dayCount;

    $limit = "LIMIT " . $totalRecipe;

    $result = $conn -> query("SELECT r.recipename, r.recipeid FROM recipe r JOIN categories c ON r.categoryid = c.categoryid WHERE username = '" . $_SESSION['username'] . "' AND r.state = 1 AND c.state = 1 ORDER BY rand() $limit;"); 
?>
<!-- Table with the diets-->
    <div class="d-flex flex-column-reverse p-2">  
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead class="text-light text-center">       
                    <tr>
                    <?php
                        for($i = 0; $i < count($daysNames); $i++) {
                            echo "<th scope='col'><h4>" . $daysNames[$i] . "</h4></th>";
                        }
                    ?>    
                    </tr>
                </thead>
                <tbody>
                <?php
//Users recipes                     
                    $recipes = [];
                    while($row = $result -> fetch_array()) {
                        $recipes[] = $row [0]; 
                    }
//Amount of recipes demanded are higher than the recipes availables                        
                    if(count($recipes) < $totalRecipe) {
                        $excess = $totalRecipe - count($recipes);
//New array with some of the recipes already added
                        $newArray = array_slice($recipes,0,$excess);               
//Completing the array so it can have the amount demanded
                        $recipes = array_merge($recipes, $newArray);
                    }
//Recipes chunks
                    $sliceRecipes = (array_chunk($recipes, $dayCount));
            
                    for($i = 0; $i < count($sliceRecipes); $i++) {
                        echo "<tr class='diet-elements'>";
                        for($j = 0; $j < count($sliceRecipes[$i]); $j++) {
                            echo "<td><a href='recipes?recipe=" . $sliceRecipes [$i][$j] . "&username=" . $_SESSION['username']. "'>" . $sliceRecipes [$i][$j] . "</a></td>";
                        } 
                        echo "</tr>"; 
                    }

//Creating an array with the days and its corresponding recipes to be store
                    $daysRecipes = [];

                    for($i = 0; $i < $dayCount; $i++) {
//The array is emptied                        
                        $recipesDaysNames = [];
//The day is added
                        $recipesDaysNames [] = $daysNames[$i];
//The recipes are added
                        for($j = 0; $j < $amount; $j++) {                            
                            array_push($recipesDaysNames, $sliceRecipes [$j][$i]);                         
                        }
//Each resulted array is added to the main one
                        $daysRecipes [] = $recipesDaysNames;
                    }
//Encripting the data
                    $data = base64_encode(serialize($daysRecipes));
                ?>
                </tbody>
            </table>
        </div>

<!--  Saving recipe form-->
        <div class="row text-center justify-content-center p-2 mt-4">
            <form class="col-auto" method="POST" action="<?php echo root;?>create"> 
                <input type="hidden" name="data" value = "<?php echo $data;?>">          
                <input class="form-control mb-3" type="text" name="diet" id="diet" placeholder="Escriba el nombre de la dieta">
                <input class="btn btn-primary" type="submit" value="Agregar" title="Generar">
            </form>
        </div>
    </div>
<?php
    }
?>
    <div class="row p-4 text-center">
        <form class="col" method="POST" action="<?php echo root;?>diet">
            <h3 class="mb-4">Cantidad de comidas</h3>
            <div class="mb-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="generate" id="three" value="3" <?php if($_SESSION["lastcheck"] == 3) { echo "checked";}?>>
                    <label class="form-check-label" for="three">3</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="generate" id="four" value="4" <?php if($_SESSION["lastcheck"] == 4) { echo "checked";}?>>
                    <label class="form-check-label" for="four">4</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="generate" id="five" value="5" <?php if($_SESSION["lastcheck"] == 5) { echo "checked";}?>>
                    <label class="form-check-label" for="five">5</label>
                </div>
            </div>
            <input class="btn btn-success" type="submit" value="Generar dieta" title="Generar">            
        </form>
    </div>
</main>
<?php
require_once ("views/partials/footer.php");

//Exiting connection
$conn -> close();
?>
